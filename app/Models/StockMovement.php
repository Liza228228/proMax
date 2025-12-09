<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stocks_movement';

    protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'ingredient_id',
        'quantity',
        'product_id',
    ];

    /**
     * Получить склад-источник
     */
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    /**
     * Получить склад-получатель
     */
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    /**
     * Получить ингредиент
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }

    /**
     * Получить продукт (если движение связано с производством)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
