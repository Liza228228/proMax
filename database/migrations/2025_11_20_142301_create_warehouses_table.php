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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id(); // ID склада
            $table->string('name', 100); // Название склада
            $table->string('city', 100); // Город склада
            $table->string('street', 100); // Улица склада
            $table->string('house', 100); // Дом склада
            $table->boolean('is_main')->default(false); // Основной склад (нельзя изменять/удалять)
            $table->timestamps(); // created_at и updated_at
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
};