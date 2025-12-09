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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id(); // ID элемента корзины
            $table->integer('quantity'); // Количество товара в корзине
            $table->decimal('price', 10, 2); // Цена товара
            $table->foreignId('idCart')->constrained('carts')->onDelete('cascade'); // ID корзины
            $table->foreignId('idProduct')->constrained('products')->onDelete('cascade'); // ID продукта
            $table->timestamps(); // created_at и updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
