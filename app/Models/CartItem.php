<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'idCart',
        'idProduct',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Получить корзину элемента
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'idCart');
    }

    /**
     * Получить продукт элемента
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct');
    }

    /**
     * Получить общую стоимость элемента
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}










