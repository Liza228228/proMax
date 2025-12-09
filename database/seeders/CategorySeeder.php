<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name_category' => 'Торты', 'available' => true],
            ['name_category' => 'Печенья', 'available' => true],
            ['name_category' => 'Десерты', 'available' => true],
            ['name_category' => 'Пироги', 'available' => true],
            ['name_category' => 'Бенто-торты', 'available' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name_category' => $category['name_category']],
                ['available' => $category['available']]
            );
        }
    }
}

