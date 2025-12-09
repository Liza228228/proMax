<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Получить единицы измерения этого типа
     */
    public function units()
    {
        return $this->hasMany(Unit::class, 'unit_type_id');
    }

    /**
     * Получить ингредиенты этого типа единицы измерения
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'unit_type_id');
    }
}
