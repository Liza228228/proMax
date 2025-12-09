<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stocks_ingredients', function (Blueprint $table) {
            $table->id(); // ID записи о запасе ингредиента на складе
            $table->integer('quantity'); // Количество ингредиента на складе
            $table->date('expiration_date')->nullable(); // Срок годности ингредиента (дата)
            $table->foreignId('idWarehouse')->constrained('warehouses')->onDelete('cascade'); // ID склада
            $table->foreignId('idIngredient')->constrained('ingredients')->onDelete('cascade'); // ID ингредиента
            $table->timestamps(); // created_at и updated_at

            // Обычный индекс для производительности (не уникальный, чтобы разрешить несколько партий с разными датами срока годности)
            $table->index(['idWarehouse', 'idIngredient', 'expiration_date'], 'stocks_ingredients_warehouse_ingredient_expiration_index');
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stocks_ingredients');
    }
};