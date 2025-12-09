<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create()

        // Заполняем типы единиц измерения
        $this->call([
            UnitTypeSeeder::class,
            UnitSeeder::class,
            WarehouseSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            IngredientSeeder::class,
            ProductSeeder::class,
            ReceptSeeder::class,
            ProductImageSeeder::class,
            StockProductSeeder::class,
        ]);
    }
}
