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
        Schema::create('product_images', function (Blueprint $table) {
           $table->id(); // ID изображения продукта
            $table->string('path', 255); // Путь к изображению
            $table->tinyInteger('is_primary')->default(0); // Является ли изображение основным (1 - да, 0 - нет)
            $table->foreignId('idProduct')->constrained('products')->onDelete('cascade'); // ID продукта
            $table->timestamps(); // created_at и updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
};
