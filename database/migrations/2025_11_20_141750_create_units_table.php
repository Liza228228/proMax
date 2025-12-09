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
        Schema::create('units', function (Blueprint $table) {
            $table->id(); // ID единицы измерения
            $table->string('name', 45); // Название единицы измерения (например, грамм, миллилитр, штука)
            $table->string('code', 45)->unique(); // Код единицы измерения (например, g, ml, pcs)
            $table->foreignId('unit_type_id')->constrained('unit_types')->onDelete('cascade'); // ID типа единицы измерения
            $table->boolean('is_base')->default(true); // Является ли минимальной базовой единицей для своего типа
            $table->decimal('multiplier_to_base', 10, 4)->default(1); // Коэффициент к базовой единице (для минимальной = 1)
            $table->timestamps(); // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
};












