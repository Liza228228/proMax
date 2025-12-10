<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\UnitType;

class UnitSeeder extends Seeder
{

    public function run(): void
    {
        // Получаем типы единиц измерения
        $massType = UnitType::where('name', 'Масса')->first();
        $volumeType = UnitType::where('name', 'Объём')->first();
        $pieceType = UnitType::where('name', 'Штуки')->first();

        if (!$massType || !$volumeType || !$pieceType) {
            $this->command->error('Типы единиц измерения не найдены. Сначала запустите UnitTypeSeeder.');
            return;
        }

        // Масса: грамм (базовая), килограмм
        Unit::firstOrCreate(
            ['code' => 'гр'],
            [
                'name' => 'Граммы',
                'unit_type_id' => $massType->id,
                'is_base' => true,
                'multiplier_to_base' => 1,
            ]
        );

        Unit::firstOrCreate(
            ['code' => 'кг'],
            [
                'name' => 'Килограммы',
                'unit_type_id' => $massType->id,
                'is_base' => false,
                'multiplier_to_base' => 1000, // 1 кг = 1000 гр
            ]
        );

        // Объём: миллилитр (базовая), литр
        Unit::firstOrCreate(
            ['code' => 'мл'],
            [
                'name' => 'Миллилитры',
                'unit_type_id' => $volumeType->id,
                'is_base' => true,
                'multiplier_to_base' => 1,
            ]
        );

        Unit::firstOrCreate(
            ['code' => 'л'],
            [
                'name' => 'Литры',
                'unit_type_id' => $volumeType->id,
                'is_base' => false,
                'multiplier_to_base' => 1000, // 1 л = 1000 мл
            ]
        );

        // Штуки
        Unit::firstOrCreate(
            ['code' => 'шт'],
            [
                'name' => 'Штуки',
                'unit_type_id' => $pieceType->id,
                'is_base' => true,
                'multiplier_to_base' => 1,
            ]
        );
    }
}

