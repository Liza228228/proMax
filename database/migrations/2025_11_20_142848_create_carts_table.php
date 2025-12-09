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
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // ID корзины
            $table->string('session_id', 255)->nullable(); // ID сессии (для неавторизованных пользователей)
            $table->unsignedBigInteger('idUser')->nullable(); // ID пользователя
            $table->timestamps(); // created_at и updated_at
            
            // Создаем внешний ключ отдельно для лучшего контроля
            $table->foreign('idUser')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
