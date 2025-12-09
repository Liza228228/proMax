<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'street',
        'house',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    /**
     * Получить ингредиенты на складе
     */
    public function stockIngredients()
    {
        return $this->hasMany(StockIngredient::class, 'idWarehouse');
    }
}










