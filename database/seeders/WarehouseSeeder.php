<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем основной склад "Кондитерская"
        Warehouse::firstOrCreate(
            ['name' => 'Кондитерская'],
            [
                'city' => 'Иркутск',
                'street' => 'Ленина',
                'house' => '5а',
                'is_main' => true,
            ]
        );
    }
}

