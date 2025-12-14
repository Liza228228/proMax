<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartController extends Controller
{

    /**
     * Добавить товар в корзину
     */
    public function add(Request $request, Product $product)
    {
        // Запрещаем администраторам и менеджерам добавлять товары в корзину
        if (Auth::check() && (Auth::user()->role == 2 || Auth::user()->role == 3)) {
            return redirect()->back()->with('error', 'Администраторы и менеджеры не могут добавлять товары в корзину');
        }

        // Загружаем stockProducts для корректного подсчета количества (только не просроченные)
        $product->load('stockProducts');
        
        // Проверяем доступность товара (только не просроченные из stocks_products)
        $today = Carbon::today();
        $availableQuantity = $product->stockProducts
            ->filter(function($stock) use ($today) {
                if (!$stock->expiration_date) {
                    return false;
                }
                $expirationDate = Carbon::parse($stock->expiration_date)->startOfDay();
                // Учитываем только товары с сроком годности строго больше текущей даты (не включая сегодня)
                return $expirationDate->greaterThan($today);
            })
            ->sum('quantity') ?? 0;
            
        if (!$product->available || $availableQuantity <= 0) {
            return redirect()->back()->with('error', 'Товар недоступен для заказа');
        }

        // Получаем или создаем корзину
        $cart = Cart::getOrCreateCart();

        // Проверяем, есть ли уже этот товар в корзине
        $cartItem = CartItem::where('idCart', $cart->id)
            ->where('idProduct', $product->id)
            ->first();

        $quantity = $request->input('quantity', 1);
        
        if ($cartItem) {
            // Если товар уже есть, увеличиваем количество
            $newQuantity = $cartItem->quantity + $quantity;
            
            // Проверяем, не превышает ли количество доступное
            if ($newQuantity > $availableQuantity) {
                return redirect()->back()->with('error', 'Недостаточно товара на складе. Доступно: ' . $availableQuantity);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->price = $product->price; // Обновляем цену на случай изменения
            $cartItem->save();
        } else {
            // Если товара нет, создаем новый элемент
            if ($quantity > $availableQuantity) {
                return redirect()->back()->with('error', 'Недостаточно товара на складе. Доступно: ' . $availableQuantity);
            }

            CartItem::create([
                'idCart' => $cart->id,
                'idProduct' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        // Если запрошено перенаправление на оформление заказа
        if ($request->has('redirect_to_checkout')) {
            // Проверяем авторизацию
            if (!Auth::check()) {
                // Если не авторизован, сохраняем intended URL и перенаправляем на логин
                session()->put('url.intended', route('cart.checkout'));
                return redirect()->route('login');
            }
            return redirect()->route('cart.checkout')->with('success', 'Продукция добавлена в корзину');
        }

        return redirect()->back()->with('success', 'Продукция добавлена в корзину');
    }

    /**
     * Удалить товар из корзины
     */
    public function remove(CartItem $cartItem)
    {
        // Проверяем, что товар принадлежит текущей корзине
        $cart = Cart::getOrCreateCart();
        if ($cartItem->idCart !== $cart->id) {
            return redirect()->route('cart.index')->with('error', 'Продукция не найдена в вашей корзине');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Продукция удалена из корзины');
    }

    /**
     * Обновить количество товара в корзине
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Проверяем, что товар принадлежит текущей корзине
        $cart = Cart::getOrCreateCart();
        if ($cartItem->idCart !== $cart->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Продукция не найдена в вашей корзине'], 404);
            }
            return redirect()->route('cart.index')->with('error', 'Продукция не найдена в вашей корзине');
        }

        $quantity = $request->input('quantity', 1);

        if ($quantity <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Количество должно быть больше нуля'], 400);
            }
            return redirect()->route('cart.index')->with('error', 'Количество должно быть больше нуля');
        }

        $product = $cartItem->product;
        
        // Загружаем stockProducts для корректного подсчета количества (только не просроченные)
        $product->load('stockProducts');
        $today = Carbon::today();
        $availableQuantity = $product->stockProducts
            ->filter(function($stock) use ($today) {
                if (!$stock->expiration_date) {
                    return false;
                }
                $expirationDate = Carbon::parse($stock->expiration_date)->startOfDay();
                // Учитываем только товары с сроком годности строго больше текущей даты (не включая сегодня)
                return $expirationDate->greaterThan($today);
            })
            ->sum('quantity') ?? 0;
        
        if ($quantity > $availableQuantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Недостаточно товара на складе. Доступно: ' . $availableQuantity], 400);
            }
            return redirect()->route('cart.index')->with('error', 'Недостаточно товара на складе. Доступно: ' . $availableQuantity);
        }

        $cartItem->quantity = $quantity;
        $cartItem->price = $product->price; // Обновляем цену
        $cartItem->save();

        // Пересчитываем общую сумму корзины
        $cart = Cart::getOrCreateCart();
        $items = $cart->items()->with('product')->get();
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'item_total' => $cartItem->total,
                'cart_total' => $total,
                'message' => 'Количество обновлено'
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Количество обновлено');
    }

    /**
     * Показать корзину
     */
    public function index()
    {
        // Запрещаем администраторам и менеджерам просматривать корзину
        if (Auth::check() && (Auth::user()->role == 2 || Auth::user()->role == 3)) {
            return redirect()->route('catalog.index')->with('error', 'Администраторы и менеджеры не могут использовать корзину');
        }

        $cart = Cart::getOrCreateCart();
        $items = $cart->items()->with('product.images', 'product.stockProducts')->get();
        
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Получить количество товаров в корзине (для AJAX)
     */
    public function count(): JsonResponse
    {
        $cart = Cart::getOrCreateCart();
        $totalItems = $cart->items()->sum('quantity');

        return response()->json([
            'count' => $totalItems
        ]);
    }

    /**
     * Показать страницу оформления заказа
     */
    public function checkout()
    {
        // Запрещаем администраторам и менеджерам оформлять заказы
        if (Auth::check() && (Auth::user()->role == 2 || Auth::user()->role == 3)) {
            return redirect()->route('catalog.index')->with('error', 'Администраторы и менеджеры не могут оформлять заказы');
        }

        $cart = Cart::getOrCreateCart();
        $items = $cart->items()->with('product.images', 'product.stockProducts')->get();
        
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }

        $total = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.checkout', compact('items', 'total'));
    }

}

