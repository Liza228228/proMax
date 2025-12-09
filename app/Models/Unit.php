<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit_type_id',
        'is_base',
        'multiplier_to_base',
    ];

    /**
     * Получить тип единицы измерения
     */
    public function unitType()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    /**
     * Получить ингредиенты с этой единицей измерения
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'idUnit');
    }
}

