<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit_type_id',
        'quantity',
        'min_quantity',
        'expiration_date',
        'idUnit',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'min_quantity' => 'decimal:3',
        'expiration_date' => 'integer', // Срок годности в количестве дней
    ];

    /**
     * Получить тип единицы измерения
     */
    public function unitType()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    /**
     * Получить единицу измерения
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'idUnit');
    }

    /**
     * Получить запасы на складах
     */
    public function stockIngredients()
    {
        return $this->hasMany(StockIngredient::class, 'idIngredient');
    }

    /**
     * Получить рецепты, в которых используется ингредиент
     */
    public function recepts()
    {
        return $this->hasMany(Recept::class, 'idIngredient');
    }
}










