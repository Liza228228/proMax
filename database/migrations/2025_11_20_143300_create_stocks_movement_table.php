<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks_movement', function (Blueprint $table) {
            $table->id(); // ID записи движения по складам
            // Откуда списываем (может быть null при внешнем пополнении)
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            // Куда поступает (может быть null при окончательном списании в производство/утилизацию)
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            // Ингредиент, по которому происходит движение
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            // Количество в базовых единицах (например, граммах)
            $table->integer('quantity');
            // Опционально: к какому продукту относится движение (например, списание при производстве)
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_movement');
    }
};


