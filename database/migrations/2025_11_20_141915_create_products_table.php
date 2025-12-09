<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID продукта
            $table->string('name_product', 100); // Название продукта
            $table->text('description')->nullable(); // Описание продукта
            $table->decimal('weight', 7, 2)->nullable(); // Вес продукта
            $table->decimal('price', 10, 2); // Цена продукта
            $table->boolean('available')->default(true); // Доступность продукта
            $table->integer('expiration_date');
            $table->foreignId('idCategory')->constrained('categories')->onDelete('cascade'); // ID категории
            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}