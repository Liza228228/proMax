<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // ID элемента заказа
            $table->integer('quantity'); // Количество товара
            $table->decimal('price', 10, 2); // Цена товара
            $table->foreignId('idOrder')->constrained('orders')->onDelete('cascade'); // ID заказа
            $table->foreignId('idProduct')->constrained('products')->onDelete('cascade'); // ID продукта
            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
