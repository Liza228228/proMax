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
        Schema::create('stocks_products', function (Blueprint $table) {
            $table->id(); // ID записи о запасе продукта
            $table->integer('quantity'); // Количество продукта
            $table->date('expiration_date'); // Срок годности продукта (дата)
            $table->foreignId('id_product')->constrained('products')->onDelete('cascade'); // ID продукта
            $table->timestamps(); // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stocks_products');
    }
};

