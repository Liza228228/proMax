<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockProduct extends Model
{
    use HasFactory;

    protected $table = 'stocks_products';

    protected $fillable = [
        'quantity',
        'expiration_date',
        'id_product',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'quantity' => 'integer',
    ];

    /**
     * Получить продукт
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    /**
     * Проверить, истек ли срок годности
     */
    public function isExpired(): bool
    {
        if (!$this->expiration_date) {
            return false;
        }

        return Carbon::parse($this->expiration_date)->isPast();
    }
}

