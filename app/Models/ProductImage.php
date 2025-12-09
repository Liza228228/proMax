<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'is_primary',
        'idProduct',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Получить продукт
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct');
    }

    /**
     * Получить путь к изображению, если файл существует
     */
    public function getValidPathAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        $fullPath = public_path($this->path);
        return file_exists($fullPath) ? $this->path : null;
    }

    /**
     * Проверить, существует ли файл изображения
     */
    public function fileExists(): bool
    {
        if (!$this->path) {
            return false;
        }

        return file_exists(public_path($this->path));
    }
}










