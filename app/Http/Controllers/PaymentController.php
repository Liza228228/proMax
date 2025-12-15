<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use YooKassa\Client;
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Model\CurrencyCode;

class PaymentController extends Controller
{
    /**
     * Создание платежа через ЮKassa
     */
    public function create(Request $request)
    {
        // Проверяем авторизацию пользователя
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Необходимо войти в систему для оформления заказа');
        }

        $user = Auth::user();
        
        // Валидация выбранных товаров
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:cart_items,id',
        ], [
            'selected_items.required' => 'Необходимо выбрать хотя бы один товар для заказа.',
            'selected_items.array' => 'Выбранные товары должны быть в виде массива.',
            'selected_items.min' => 'Необходимо выбрать хотя бы один товар для заказа.',
            'selected_items.*.exists' => 'Один из выбранных товаров не найден в корзине.',
        ]);

        // Получаем корзину пользователя
        $cart = Cart::getOrCreateCart();
        
        // Получаем выбранные товары из корзины пользователя
        $selectedCartItems = CartItem::whereIn('id', $request->selected_items)
            ->where('idCart', $cart->id)
            ->with('product.stockProducts')
            ->get();

        if ($selectedCartItems->isEmpty()) {
            return redirect()->route('cart.checkout')->with('error', 'Не выбраны товары для заказа');
        }

        // Проверяем наличие товаров на складе
        foreach ($selectedCartItems as $item) {
            $availableQuantity = $item->product->total_quantity;
            if ($item->quantity > $availableQuantity) {
                return redirect()->route('cart.checkout')->with('error', 
                    'Недостаточно товара "' . $item->product->name_product . '" на складе. Доступно: ' . $availableQuantity);
            }
        }

        // Вычисляем итоговую сумму
        $totalAmount = $selectedCartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Проверяем, что сумма больше нуля
        if ($totalAmount <= 0) {
            return redirect()->route('cart.checkout')->with('error', 'Сумма заказа должна быть больше нуля');
        }

        // Проверяем минимальную сумму (минимум 1 рубль для ЮKassa)
        if ($totalAmount < 1.00) {
            return redirect()->route('cart.checkout')->with('error', 'Минимальная сумма заказа: 1 рубль');
        }

        try {
            // Проверяем, что пользователь существует в БД
            if (!$user || !$user->id) {
                \Log::error('User not authenticated or ID missing');
                return redirect()->route('cart.checkout')->with('error', 'Пользователь не авторизован');
            }

            // Загружаем пользователя из БД заново для гарантии актуальности
            $dbUser = \App\Models\User::find($user->id);
            if (!$dbUser) {
                \Log::error('User not found in database. Auth ID: ' . $user->id . ', Auth user object: ' . json_encode($user->toArray()));
                Auth::logout();
                return redirect()->route('login')->with('error', 'Ошибка: ваш аккаунт не найден в системе. Пожалуйста, войдите снова.');
            }
            
            // Используем пользователя из БД
            $user = $dbUser;

            // Создаем заказ в БД (со статусом "Создан", оплата будет после подтверждения)
            DB::beginTransaction();

            $order = Order::create([
                'idUser' => $user->id,
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'status' => 'Создан',
            ]);

            // Сразу создаем записи в order_items и удаляем товары из корзины
            // Количество на складе НЕ уменьшаем до оплаты
            foreach ($selectedCartItems as $cartItem) {
                // Создаем OrderItem сразу при создании заказа
                OrderItem::create([
                    'idOrder' => $order->id,
                    'idProduct' => $cartItem->idProduct,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Удаляем товар из корзины сразу при создании заказа
                $cartItem->delete();
            }

            DB::commit();

            // Инициализируем клиент ЮKassa
            $client = new Client();
            $client->setAuth(
                config('yookassa.shop_id'),
                config('yookassa.secret_key')
            );


            // Создаем платеж через ЮKassa
            $idempotenceKey = uniqid('', true);
            
            // Форматируем сумму для ЮKassa (строка с двумя знаками после запятой)
            $amountValue = number_format((float)$totalAmount, 2, '.', '');
            
            $payment = $client->createPayment([
                'amount' => [
                    'value' => $amountValue,
                    'currency' => CurrencyCode::RUB,
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => url('/payment/success?order_id=' . $order->id),
                ],
                'capture' => true,
                'description' => 'Заказ #' . $order->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                ],
            ], $idempotenceKey);

            // Сохраняем payment_id в кэше для проверки статуса
            $paymentId = $payment->getId();
            Cache::put('payment_id_' . $order->id, $paymentId, now()->addHours(24));

            // Перенаправляем пользователя на страницу оплаты ЮKassa
            return redirect()->away($payment->getConfirmation()->getConfirmationUrl());

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment creation error: ' . $e->getMessage());
            \Log::error('Payment creation error trace: ' . $e->getTraceAsString());
            return redirect()->route('cart.checkout')->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
        }
    }

    /**
     * Оплата существующего заказа (из личного кабинета)
     */
    public function payOrder(Request $request, $orderId)
    {
        // Проверяем авторизацию
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Необходимо войти в систему');
        }
        
        $currentUserId = Auth::id();
        
        // Преобразуем orderId в число для корректного сравнения
        $orderId = (int)$orderId;
        
        // Загружаем заказ без фильтра по пользователю сначала
        $order = Order::find($orderId);
        
        if (!$order) {
            \Log::error('Order not found: Order ID=' . $orderId);
            return redirect()->route('profile.edit')->with('error', 'Заказ не найден');
        }
        
        // Проверяем принадлежность заказа пользователю с использованием мягкого сравнения
        // Используем приведение к строкам для сравнения, чтобы избежать проблем с типами
        $orderUserId = (string)$order->idUser;
        $currentUserIdStr = (string)$currentUserId;
        
        \Log::info('PayOrder check: Order ID=' . $order->id . ', Order User ID=' . $orderUserId . ', Current User ID=' . $currentUserIdStr);
        
        if ($orderUserId !== $currentUserIdStr) {
            \Log::error('Order user mismatch: Order ID=' . $order->id . ', Order User ID=' . $orderUserId . ' (original: ' . $order->idUser . '), Current User ID=' . $currentUserIdStr . ' (original: ' . $currentUserId . ')');
            
            // Дополнительная проверка с числовым сравнением
            if ((int)$order->idUser !== (int)$currentUserId) {
                return redirect()->route('profile.edit')->with('error', 'Этот заказ принадлежит другому пользователю');
            }
        }
        
        // Загружаем товары заказа
        $order->load('items.product.stockProducts');
        
        \Log::info('PayOrder: Order validated. Order ID=' . $order->id . ', User ID=' . $currentUserId);

        // Проверяем, что заказ имеет статус "Создан"
        if ($order->status !== 'Создан') {
            return redirect()->route('profile.edit')->with('error', 'Этот заказ уже был оплачен или отменен. Текущий статус: ' . $order->status);
        }

        // Проверяем наличие товаров в заказе
        $orderItems = $order->items;
        
        if ($orderItems->isEmpty()) {
            \Log::error('Order has no items. Order ID: ' . $order->id);
            return redirect()->route('profile.edit')->with('error', 'В заказе нет товаров. Пожалуйста, обратитесь в поддержку.');
        }

        // Проверяем доступность товаров перед оплатой
        foreach ($orderItems as $orderItem) {
            $product = $orderItem->product;
            if (!$product) {
                \Log::error('Product not found for order item. Order ID: ' . $order->id . ', OrderItem ID: ' . $orderItem->id . ', Product ID: ' . $orderItem->idProduct);
                return redirect()->route('profile.edit')->with('error', 'Товар в заказе не найден. Пожалуйста, обратитесь в поддержку.');
            }
            
            // Проверяем, что товар доступен
            if (!$product->available) {
                return redirect()->route('profile.edit')->with('error', 
                    'Товар "' . $product->name_product . '" недоступен для оплаты.');
            }
            
            // Проверяем наличие товара на складе
            $availableQuantity = $product->total_quantity;
            if ($availableQuantity < $orderItem->quantity) {
                return redirect()->route('profile.edit')->with('error', 
                    'Товар "' . $product->name_product . '" недоступен в нужном количестве. Доступно: ' . $availableQuantity);
            }
        }

        // Проверяем минимальную сумму (минимум 1 рубль для ЮKassa)
        if ($order->total_amount < 1.00) {
            return redirect()->route('profile.edit')->with('error', 'Минимальная сумма заказа: 1 рубль');
        }

        try {
            // Инициализируем клиент ЮKassa
            $client = new Client();
            $client->setAuth(
                config('yookassa.shop_id'),
                config('yookassa.secret_key')
            );

            // Создаем платеж через ЮKassa
            $idempotenceKey = uniqid('order_' . $order->id . '_', true);
            
            // Форматируем сумму для ЮKassa (строка с двумя знаками после запятой)
            $amountValue = number_format((float)$order->total_amount, 2, '.', '');
            
            \Log::info('Creating payment for order. Order ID: ' . $order->id . ', Amount: ' . $amountValue);
            
            $payment = $client->createPayment([
                'amount' => [
                    'value' => $amountValue,
                    'currency' => CurrencyCode::RUB,
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => url('/payment/success?order_id=' . $order->id),
                ],
                'capture' => true,
                'description' => 'Оплата заказа #' . $order->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->idUser,
                ],
            ], $idempotenceKey);

            // Сохраняем payment_id в кэше для проверки статуса
            $paymentId = $payment->getId();
            Cache::put('payment_id_' . $order->id, $paymentId, now()->addHours(24));
            
            \Log::info('Payment created successfully. Order ID: ' . $order->id . ', Payment ID: ' . $paymentId);
            
            // Получаем URL для перенаправления
            $confirmationUrl = $payment->getConfirmation()->getConfirmationUrl();
            \Log::info('Redirecting to payment URL: ' . $confirmationUrl);

            // Перенаправляем пользователя на страницу оплаты ЮKassa (точно так же, как при создании из корзины)
            return redirect()->away($confirmationUrl);

        } catch (\Exception $e) {
            \Log::error('Payment order error: ' . $e->getMessage());
            \Log::error('Payment order error trace: ' . $e->getTraceAsString());
            \Log::error('Order details: ID=' . $order->id . ', User ID=' . $order->idUser . ', Amount=' . $order->total_amount . ', Status=' . $order->status);
            return redirect()->route('profile.edit')->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
        }
    }

    /**
     * Обработка успешной оплаты (возврат пользователя после оплаты)
     */
    public function success(Request $request)
    {
        $orderId = $request->input('order_id');
        
        if (!$orderId) {
            return redirect()->route('cart.index')->with('info', 'Спасибо за заказ! Мы обрабатываем ваш платеж.');
        }

        $order = Order::with('items.product.stockProducts')->find($orderId);
        
        // Если заказ уже оплачен (статус "Принят")
        if ($order && $order->status === 'Принят') {
            return redirect()->route('profile.edit')->with('success', 
                'Заказ #' . $order->id . ' уже оплачен! Итоговая сумма: ' . number_format($order->total_amount, 0, '.', ' ') . ' ₽. Заказ доступен только для самовывоза по адресу: г. Иркутск, ул. Ленина, д. 5а');
        }
        
        // Если заказ еще не обработан, проверяем статус платежа и обрабатываем
        if ($order && $order->status === 'Создан') {
            try {
                $paymentId = Cache::get('payment_id_' . $order->id);
                
                if ($paymentId) {
                    // Проверяем статус платежа через API ЮKassa
                    try {
                        $client = new Client();
                        $client->setAuth(
                            config('yookassa.shop_id'),
                            config('yookassa.secret_key')
                        );
                        
                        $payment = $client->getPaymentInfo($paymentId);
                        
                        // Если платеж успешен, обрабатываем заказ
                        if ($payment->getStatus() === PaymentStatus::SUCCEEDED) {
                            DB::beginTransaction();
                            
                            try {
                                // Товары уже добавлены в OrderItem при создании заказа
                                // Теперь уменьшаем количество товара на складе и меняем статус
                                foreach ($order->items as $orderItem) {
                                    $product = $orderItem->product;
                                    if ($product) {
                                        // Списываем товар из stocks_products (FIFO - сначала ближайший срок годности)
                                        $this->deductProductFromStock($product, $orderItem->quantity);
                                        
                                        // Обновляем доступность продукта
                                        $totalQuantity = $product->stockProducts()
                                            ->where('expiration_date', '>=', Carbon::today())
                                            ->sum('quantity') ?? 0;
                                        
                                        $product->available = $totalQuantity > 0;
                                        $product->save();
                                    }
                                }

                                // Обновляем статус заказа на "Принят"
                                $order->update(['status' => 'Принят']);
                                
                                // Очищаем кэш
                                Cache::forget('payment_id_' . $order->id);
                                
                                DB::commit();
                                
                                // Перенаправляем в личный кабинет с сообщением об успехе
                                return redirect()->route('profile.edit')->with('success', 
                                    'Заказ #' . $order->id . ' успешно оформлен и оплачен. Итоговая сумма: ' . number_format($order->total_amount, 0, '.', ' ') . ' р. Заказ доступен только для самовывоза по адресу: г. Иркутск, ул. Ленина, д. 5а');
                            } catch (\Exception $e) {
                                DB::rollBack();
                                \Log::error('Error processing order in success: ' . $e->getMessage());
                                throw $e;
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error checking payment status in success: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error in success method: ' . $e->getMessage());
            }
        }
        
        return view('payment.success', compact('order'));
    }

    /**
     * Обработка webhook от ЮKassa
     */
    public function webhook(Request $request)
    {
        try {
            $requestBody = json_decode(file_get_contents('php://input'), true);

            if (!isset($requestBody['event']) || $requestBody['event'] !== 'payment.succeeded') {
                return response()->json(['status' => 'ok']);
            }

            $paymentId = $requestBody['object']['id'] ?? null;
            if (!$paymentId) {
                return response()->json(['status' => 'error'], 400);
            }

            $client = new Client();
            $client->setAuth(
                config('yookassa.shop_id'),
                config('yookassa.secret_key')
            );
            $payment = $client->getPaymentInfo($paymentId);
            $orderId = $payment->getMetadata()->offsetGet('order_id');

            if ($orderId && $payment->getStatus() === PaymentStatus::SUCCEEDED) {
                $order = Order::find($orderId);
                
                if ($order && $order->status === 'Создан') {
                    DB::beginTransaction();

                    try {
                        // Товары уже добавлены в OrderItem при создании заказа
                        // Теперь уменьшаем количество товара на складе и меняем статус
                        $orderItems = OrderItem::where('idOrder', $order->id)->with('product.stockProducts')->get();
                        
                        if ($orderItems->isEmpty()) {
                            \Log::warning('Order items not found for order: ' . $orderId);
                            DB::rollBack();
                        } else {
                            foreach ($orderItems as $orderItem) {
                                $product = $orderItem->product;
                                if ($product) {
                                    // Списываем товар из stocks_products (FIFO - сначала ближайший срок годности)
                                    $this->deductProductFromStock($product, $orderItem->quantity);
                                    
                                    // Обновляем доступность продукта
                                    $totalQuantity = $product->stockProducts()
                                        ->where('expiration_date', '>=', Carbon::today())
                                        ->sum('quantity') ?? 0;
                                    
                                    $product->available = $totalQuantity > 0;
                                    $product->save();
                                }
                            }

                            // Обновляем статус заказа на "Принят"
                            $order->update(['status' => 'Принят']);
                            
                            // Очищаем кэш
                            Cache::forget('payment_id_' . $order->id);
                            
                            DB::commit();
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        \Log::error('Webhook transaction error: ' . $e->getMessage());
                        throw $e;
                    }
                } else {
                    \Log::info('Order already processed or not found. Order ID: ' . $orderId . ', Status: ' . ($order ? $order->status : 'not found'));
                }
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Списать товар из stocks_products (FIFO - сначала ближайший срок годности)
     */
    private function deductProductFromStock(Product $product, int $quantityToDeduct): void
    {
        // Получаем все не просроченные партии товара, отсортированные по сроку годности (FIFO)
        $stockProducts = StockProduct::where('id_product', $product->id)
            ->where('expiration_date', '>=', Carbon::today())
            ->where('quantity', '>', 0)
            ->orderBy('expiration_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingQuantity = $quantityToDeduct;

        foreach ($stockProducts as $stockProduct) {
            if ($remainingQuantity <= 0) {
                break;
            }

            if ($stockProduct->quantity <= $remainingQuantity) {
                // Списываем всю партию
                $remainingQuantity -= $stockProduct->quantity;
                $stockProduct->delete();
            } else {
                // Списываем часть партии
                $stockProduct->quantity -= $remainingQuantity;
                $stockProduct->save();
                $remainingQuantity = 0;
            }
        }

        if ($remainingQuantity > 0) {
            throw new \Exception("Недостаточно товара '{$product->name_product}' на складе. Требуется: {$quantityToDeduct}, доступно: " . ($quantityToDeduct - $remainingQuantity));
        }
    }
}
