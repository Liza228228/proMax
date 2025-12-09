<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class StockIngredient extends Model
{
    use HasFactory;

    protected $table = 'stocks_ingredients';

    protected $fillable = [
        'quantity',
        'expiration_date',
        'idWarehouse',
        'idIngredient',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'expiration_date' => 'date',
    ];

    /**
     * Boot метод для проверки максимального значения
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stockIngredient) {
            // Максимальное значение для integer в MySQL
            $maxInteger = 2147483647;
            
            // Проверяем, не превышает ли значение максимальное для integer
            if ($stockIngredient->quantity > $maxInteger) {
                Log::warning('Попытка сохранить количество ингредиента, превышающее максимальное значение', [
                    'idWarehouse' => $stockIngredient->idWarehouse,
                    'idIngredient' => $stockIngredient->idIngredient,
                    'quantity' => $stockIngredient->quantity,
                    'maxInteger' => $maxInteger,
                ]);
                
                // Ограничиваем значение до максимума
                $stockIngredient->quantity = $maxInteger;
            }
        });
    }

    /**
     * Получить склад
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'idWarehouse');
    }

    /**
     * Получить ингредиент
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'idIngredient');
    }
}

