<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'idUser',
    ];

    /**
     * Получить пользователя корзины
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    /**
     * Получить элементы корзины
     */
    public function items()
    {
        return $this->hasMany(CartItem::class, 'idCart');
    }

    /**
     * Получить или создать корзину для текущего пользователя/сессии
     */
    public static function getOrCreateCart()
    {
        if (auth()->check()) {
            // Для авторизованных пользователей
            $user = auth()->user();
            
            if (!$user || !$user->id) {
                // Если пользователь не найден, используем сессию
                $sessionId = session()->getId();
                $cart = self::where('session_id', $sessionId)->first();
                if (!$cart) {
                    $cart = self::create(['session_id' => $sessionId]);
                }
                return $cart;
            }
            
            // Проверяем, что пользователь существует в базе данных
            if (!User::where('id', $user->id)->exists()) {
                // Если пользователь не существует в БД, используем сессию
                $sessionId = session()->getId();
                $cart = self::where('session_id', $sessionId)->first();
                if (!$cart) {
                    $cart = self::create(['session_id' => $sessionId]);
                }
                return $cart;
            }
            
            $cart = self::where('idUser', $user->id)->first();
            if (!$cart) {
                try {
                    $cart = self::create(['idUser' => $user->id]);
                } catch (\Exception $e) {
                    // Если не удалось создать с idUser, используем сессию
                    \Log::error('Ошибка создания корзины для пользователя: ' . $e->getMessage());
                    $sessionId = session()->getId();
                    $cart = self::where('session_id', $sessionId)->first();
                    if (!$cart) {
                        $cart = self::create(['session_id' => $sessionId]);
                    }
                }
            }
        } else {
            // Для неавторизованных пользователей используем сессию
            $sessionId = session()->getId();
            $cart = self::where('session_id', $sessionId)->first();
            if (!$cart) {
                $cart = self::create(['session_id' => $sessionId]);
            }
        }
        return $cart;
    }

    /**
     * Получить количество товаров в корзине без создания корзины
     */
    public static function getCartCount()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user && $user->id) {
                $cart = self::where('idUser', $user->id)->first();
            } else {
                $sessionId = session()->getId();
                $cart = self::where('session_id', $sessionId)->first();
            }
        } else {
            $sessionId = session()->getId();
            $cart = self::where('session_id', $sessionId)->first();
        }
        
        if (!$cart) {
            return 0;
        }
        
        return $cart->items()->sum('quantity');
    }

    /**
     * Перенести корзину гостя в корзину авторизованного пользователя
     * 
     * @param string $guestSessionId ID сессии гостя (до регенерации)
     * @param int $userId ID пользователя
     * @return Cart Корзина пользователя после переноса
     */
    public static function mergeGuestCart($guestSessionId, $userId)
    {
        return DB::transaction(function () use ($guestSessionId, $userId) {
            \Log::info("Начало переноса корзины: session_id={$guestSessionId}, user_id={$userId}");
            
            // Находим корзину гостя по session_id (без idUser)
            // Загружаем товары сразу для оптимизации
            $guestCart = self::where('session_id', $guestSessionId)
                ->whereNull('idUser')
                ->with('items.product')
                ->first();

            if (!$guestCart) {
                \Log::info("Корзина гостя не найдена для session_id={$guestSessionId}");
                // Если корзины гостя нет, просто возвращаем корзину пользователя
                $userCart = self::where('idUser', $userId)->first();
                if (!$userCart) {
                    $userCart = self::create(['idUser' => $userId]);
                }
                return $userCart;
            }

            \Log::info("Найдена корзина гостя: cart_id={$guestCart->id}, items_count=" . $guestCart->items->count());

            // Проверяем, есть ли товары в корзине гостя
            $guestItemsCount = $guestCart->items()->count();
            if ($guestItemsCount === 0) {
                // Если корзина пуста, удаляем её и возвращаем корзину пользователя
                $guestCart->delete();
                $userCart = self::where('idUser', $userId)->first();
                if (!$userCart) {
                    $userCart = self::create(['idUser' => $userId]);
                }
                return $userCart;
            }

            // Получаем или создаем корзину пользователя
            $userCart = self::where('idUser', $userId)->first();
            if (!$userCart) {
                $userCart = self::create(['idUser' => $userId]);
            }

            // Переносим товары из корзины гостя в корзину пользователя
            $transferredItems = 0;
            foreach ($guestCart->items as $guestItem) {
                // Проверяем, есть ли уже такой товар в корзине пользователя
                $existingItem = CartItem::where('idCart', $userCart->id)
                    ->where('idProduct', $guestItem->idProduct)
                    ->first();

                if ($existingItem) {
                    // Если товар уже есть, суммируем количества
                    $newQuantity = $existingItem->quantity + $guestItem->quantity;
                    
                    // Проверяем доступность товара
                    $product = $guestItem->product;
                    if ($product && $newQuantity > $product->quantity) {
                        // Если превышает доступное количество, ограничиваем максимальным
                        $newQuantity = $product->quantity;
                    }

                    $existingItem->quantity = $newQuantity;
                    $existingItem->price = $guestItem->price; // Обновляем цену на актуальную
                    $existingItem->save();
                    $transferredItems++;
                } else {
                    // Если товара нет, создаем новый элемент
                    CartItem::create([
                        'idCart' => $userCart->id,
                        'idProduct' => $guestItem->idProduct,
                        'quantity' => $guestItem->quantity,
                        'price' => $guestItem->price,
                    ]);
                    $transferredItems++;
                }
            }

            \Log::info("Перенесено товаров: {$transferredItems} из корзины гостя в корзину пользователя user_id={$userId}");

            // Удаляем корзину гостя после переноса
            $guestCart->delete();
            \Log::info("Корзина гостя удалена: cart_id={$guestCart->id}");

            return $userCart;
        });
    }

    /**
     * Удалить корзину гостя (для пользователей с ролью, отличной от клиента)
     * 
     * @param string $guestSessionId ID сессии гостя (до регенерации)
     * @return void
     */
    public static function deleteGuestCart($guestSessionId)
    {
        $guestCart = self::where('session_id', $guestSessionId)
            ->whereNull('idUser')
            ->with('items')
            ->first();

        if ($guestCart) {
            \Log::info("Удаление корзины гостя: cart_id={$guestCart->id}, items_count=" . $guestCart->items->count());
            $guestCart->delete();
            \Log::info("Корзина гостя удалена: cart_id={$guestCart->id}");
        } else {
            \Log::info("Корзина гостя не найдена для session_id={$guestSessionId}");
        }
    }
}

