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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id(); // ID ингредиента
            $table->string('name', 100); // Название ингредиента
            $table->text('description')->nullable(); // Описание ингредиента
            $table->integer('expiration_date')->nullable(); // Срок годности ингредиента (количество дней)
            $table->foreignId('unit_type_id')->constrained('unit_types')->onDelete('restrict'); // ID типа единицы измерения
            $table->timestamps(); // created_at и updated_at
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
};












