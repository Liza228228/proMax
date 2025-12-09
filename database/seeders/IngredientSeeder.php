<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\UnitType;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем типы единиц измерения
        $massType = UnitType::where('name', 'Масса')->first();
        $volumeType = UnitType::where('name', 'Объём')->first();
        $pieceType = UnitType::where('name', 'Штуки')->first();

        // Проверяем, что типы единиц измерения существуют
        if (!$massType || !$volumeType || !$pieceType) {
            $this->command->error('Типы единиц измерения не найдены. Убедитесь, что UnitTypeSeeder выполнен первым.');
            return;
        }

        $ingredients = [
            // Ингредиенты с единицей измерения "Масса"
            [
                'name' => 'Мука пшеничная',
                'description' => 'Пшеничная мука высшего сорта',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Сахар',
                'description' => 'Сахар-песок белый',
                'unit_type_id' => $massType->id,
                'expiration_date' => 730, // 2 года
            ],
            [
                'name' => 'Сахарная пудра',
                'description' => 'Сахарная пудра для декора',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Масло сливочное',
                'description' => 'Сливочное масло 82,5%',
                'unit_type_id' => $massType->id,
                'expiration_date' => 30, // 30 дней
            ],
            [
                'name' => 'Шоколад темный',
                'description' => 'Темный шоколад для выпечки',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Шоколад молочный',
                'description' => 'Молочный шоколад для декора',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Какао-порошок',
                'description' => 'Какао-порошок натуральный',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Разрыхлитель теста',
                'description' => 'Пекарский порошок',
                'unit_type_id' => $massType->id,
                'expiration_date' => 730, // 2 года
            ],
            [
                'name' => 'Сода пищевая',
                'description' => 'Пищевая сода',
                'unit_type_id' => $massType->id,
                'expiration_date' => 1095, // 3 года
            ],
            [
                'name' => 'Ванильный сахар',
                'description' => 'Ванильный сахар',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Корица молотая',
                'description' => 'Молотая корица',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],
            [
                'name' => 'Орехи грецкие',
                'description' => 'Грецкие орехи очищенные',
                'unit_type_id' => $massType->id,
                'expiration_date' => 180, // 6 месяцев
            ],
            [
                'name' => 'Миндаль',
                'description' => 'Миндаль очищенный',
                'unit_type_id' => $massType->id,
                'expiration_date' => 180, // 6 месяцев
            ],
            [
                'name' => 'Кокосовая стружка',
                'description' => 'Кокосовая стружка',
                'unit_type_id' => $massType->id,
                'expiration_date' => 365, // 1 год
            ],

            // Ингредиенты с единицей измерения "Объём"
            [
                'name' => 'Молоко',
                'description' => 'Молоко пастеризованное',
                'unit_type_id' => $volumeType->id,
                'expiration_date' => 5, // 5 дней
            ],
            [
                'name' => 'Сливки 33%',
                'description' => 'Сливки жирные для крема',
                'unit_type_id' => $volumeType->id,
                'expiration_date' => 5, // 5 дней
            ],
            [
                'name' => 'Сметана',
                'description' => 'Сметана 20%',
                'unit_type_id' => $volumeType->id,
                'expiration_date' => 7, // 7 дней
            ],
            [
                'name' => 'Растительное масло',
                'description' => 'Подсолнечное масло рафинированное',
                'unit_type_id' => $volumeType->id,
                'expiration_date' => 180, // 6 месяцев
            ],

            // Ингредиенты с единицей измерения "Штуки"
            [
                'name' => 'Яйца куриные',
                'description' => 'Куриные яйца категории С0',
                'unit_type_id' => $pieceType->id,
                'expiration_date' => 30, // 30 дней
            ],
        ];

        foreach ($ingredients as $ingredientData) {
            Ingredient::firstOrCreate(
                ['name' => $ingredientData['name']],
                [
                    'description' => $ingredientData['description'],
                    'unit_type_id' => $ingredientData['unit_type_id'],
                    'expiration_date' => $ingredientData['expiration_date'],
                ]
            );
        }
    }
}

