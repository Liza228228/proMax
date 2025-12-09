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
        Schema::create('recepts', function (Blueprint $table) {
            $table->id(); // ID рецепта
            $table->integer('quantity'); // Количество ингредиента в рецепте
            $table->foreignId('idIngredient')->constrained('ingredients')->onDelete('cascade'); // ID ингредиента
            $table->foreignId('idProduct')->constrained('products')->onDelete('cascade'); // ID продукта
            $table->timestamps(); // created_at и updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('recepts');
    }
};