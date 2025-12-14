<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_product',
        'description',
        'weight',
        'price',
        'available',
        'expiration_date',
        'idCategory',
    ];

    protected $casts = [
        'available' => 'boolean',
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'expiration_date' => 'integer', // Количество дней срока годности
    ];

    /**
     * Boot метод для автоматического изменения доступности
     */
    protected static function boot()
    {
        parent::boot();

        // Автоматически обновляем доступность из stocks_products при сохранении
        static::saving(function ($product) {
            $totalQuantity = $product->stockProducts()
                ->where('expiration_date', '>=', Carbon::today())
                ->sum('quantity') ?? 0;
            
            if ($totalQuantity == 0) {
                $product->available = false;
            }
        });
    }

    /**
     * Получить ближайшую дату истечения срока годности
     */
    public function getExpirationDate(): ?Carbon
    {
        return $this->nearest_expiration_date;
    }

    /**
     * Проверить, истек ли срок годности (проверяем ближайшую дату)
     */
    public function isExpired(): bool
    {
        $nearestDate = $this->nearest_expiration_date;
        if (!$nearestDate) {
            return false;
        }

        return $nearestDate->isPast();
    }

    /**
     * Проверить, истекает ли срок годности в течение 2 дней
     */
    public function isExpiringSoon(): bool
    {
        $nearestDate = $this->nearest_expiration_date;
        if (!$nearestDate) {
            return false;
        }

        $today = Carbon::today();
        $daysUntilExpiration = $today->diffInDays($nearestDate, false);

        // Если срок годности истекает в течение 2 дней (включительно) и еще не истек
        return $daysUntilExpiration >= 0 && $daysUntilExpiration <= 2 && !$nearestDate->isPast();
    }

    /**
     * Получить количество дней до истечения срока годности
     */
    public function getDaysUntilExpiration(): ?int
    {
        $nearestDate = $this->nearest_expiration_date;
        if (!$nearestDate) {
            return null;
        }

        $today = Carbon::today();
        return $today->diffInDays($nearestDate, false);
    }

    /**
     * Получить категорию продукта
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'idCategory');
    }

    /**
     * Получить изображения продукта
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'idProduct');
    }

    /**
     * Получить основное изображение продукта
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'idProduct')->where('is_primary', 1);
    }

    /**
     * Получить рецепт продукта (ингредиенты)
     */
    public function recepts()
    {
        return $this->hasMany(Recept::class, 'idProduct');
    }

    /**
     * Получить ингредиенты продукта через рецепт
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recepts', 'idProduct', 'idIngredient')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Получить запасы продукта на складах
     */
    public function stockProducts()
    {
        return $this->hasMany(StockProduct::class, 'id_product');
    }

    /**
     * Получить общее количество продукта (только не просроченные)
     */
    public function getTotalQuantityAttribute(): int
    {
        // Используем уже загруженные stockProducts, если они есть
        if ($this->relationLoaded('stockProducts')) {
            $today = Carbon::today();
            return $this->stockProducts
                ->filter(function($stock) use ($today) {
                    if (!$stock->expiration_date) {
                        return false;
                    }
                    $expirationDate = Carbon::parse($stock->expiration_date)->startOfDay();
                    // Учитываем только товары с сроком годности строго больше текущей даты (не включая сегодня)
                    return $expirationDate->greaterThan($today);
                })
                ->sum('quantity') ?? 0;
        }
        
        // Если не загружены, делаем запрос
        return $this->stockProducts()
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '>', Carbon::today())
            ->sum('quantity') ?? 0;
    }

    /**
     * Получить ближайший срок годности (самую раннюю дату из не просроченных)
     */
    public function getNearestExpirationDateAttribute(): ?Carbon
    {
        // Используем уже загруженные stockProducts, если они есть
        if ($this->relationLoaded('stockProducts')) {
            $nearestStock = $this->stockProducts
                ->filter(function($stock) {
                    return Carbon::parse($stock->expiration_date)->isFuture() && $stock->quantity > 0;
                })
                ->sortBy('expiration_date')
                ->first();
            
            return $nearestStock ? Carbon::parse($nearestStock->expiration_date) : null;
        }
        
        // Если не загружены, делаем запрос
        $nearestStock = $this->stockProducts()
            ->where('expiration_date', '>=', Carbon::today())
            ->where('quantity', '>', 0)
            ->orderBy('expiration_date', 'asc')
            ->first();

        return $nearestStock ? Carbon::parse($nearestStock->expiration_date) : null;
    }
}
