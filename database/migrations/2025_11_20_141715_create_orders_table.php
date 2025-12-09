<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID заказа
            $table->foreignId('idUser')->constrained('users')->onDelete('cascade'); // ID пользователя
            $table->timestamp('order_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Дата заказа
            $table->decimal('total_amount', 10, 2); // Общая сумма заказа
            $table->enum('status', ['Создан', 'Принят', 'Готов к выдаче', 'Выполнен'])->default('Создан'); // Статус заказа
            $table->timestamps(); // created_at и updated_at
        });
    }


    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
