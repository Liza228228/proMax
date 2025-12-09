<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recept extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'idIngredient',
        'idProduct',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Получить ингредиент
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'idIngredient');
    }

    /**
     * Получить продукт
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct');
    }
}










