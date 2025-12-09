<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitType;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitTypes = [
            'Масса',
            'Объём',
            'Штуки',
        ];

        foreach ($unitTypes as $unitTypeName) {
            UnitType::firstOrCreate(
                ['name' => $unitTypeName]
            );
        }
    }
}
