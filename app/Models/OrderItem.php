<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'idOrder',
        'idProduct',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Получить заказ
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'idOrder');
    }

    /**
     * Получить продукт
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct');
    }
}










