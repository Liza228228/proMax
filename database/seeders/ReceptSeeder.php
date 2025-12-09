<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recept;
use App\Models\Product;
use App\Models\Ingredient;

class ReceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем все ингредиенты
        $ingredients = [];
        $ingredientNames = [
            'Мука пшеничная', 'Сахар', 'Сахарная пудра', 'Масло сливочное',
            'Шоколад темный', 'Шоколад молочный', 'Какао-порошок', 'Разрыхлитель теста',
            'Сода пищевая', 'Ванильный сахар', 'Корица молотая', 'Орехи грецкие',
            'Миндаль', 'Кокосовая стружка', 'Молоко', 'Сливки 33%', 'Сметана',
            'Растительное масло', 'Яйца куриные'
        ];

        foreach ($ingredientNames as $name) {
            $ingredient = Ingredient::where('name', $name)->first();
            if ($ingredient) {
                $ingredients[$name] = $ingredient;
            }
        }

        // Получаем все продукты
        $products = [];
        $productNames = [
            'Торт "Наполеон"', 'Торт "Красный бархат"', 'Торт "Медовик"', 'Торт "Чизкейк"', 'Торт "Птичье молоко"',
            'Печенье "Овсяное"', 'Печенье "Шоколадное"', 'Печенье "Имбирное"', 'Печенье "Песочное"', 'Печенье "Макаронс"',
            'Тирамису', 'Панакота с ягодами', 'Крем-брюле', 'Профитроли с заварным кремом', 'Эклеры',
            'Пирог с яблоками', 'Пирог с вишней', 'Пирог с капустой', 'Пирог с мясом', 'Пирог с творогом',
            'Бенто-торт "Клубничный"', 'Бенто-торт "Шоколадный"', 'Бенто-торт "Ванильный"', 'Бенто-торт "Карамельный"', 'Бенто-торт "Фруктовый"'
        ];

        foreach ($productNames as $name) {
            $product = Product::where('name_product', $name)->first();
            if ($product) {
                $products[$name] = $product;
            }
        }

        // Проверяем наличие данных
        if (empty($ingredients) || empty($products)) {
            $this->command->error('Ингредиенты или продукты не найдены. Убедитесь, что IngredientSeeder и ProductSeeder выполнены первыми.');
            return;
        }

        // Рецепты для тортов (количество в граммах для массы, мл для объема, штуки)
        $recepts = [
            // Торт "Наполеон"
            ['product' => 'Торт "Наполеон"', 'ingredient' => 'Мука пшеничная', 'quantity' => 500], // 500 гр
            ['product' => 'Торт "Наполеон"', 'ingredient' => 'Масло сливочное', 'quantity' => 300], // 300 гр
            ['product' => 'Торт "Наполеон"', 'ingredient' => 'Сахар', 'quantity' => 200], // 200 гр
            ['product' => 'Торт "Наполеон"', 'ingredient' => 'Яйца куриные', 'quantity' => 4], // 4 шт
            ['product' => 'Торт "Наполеон"', 'ingredient' => 'Молоко', 'quantity' => 500], // 500 мл

            // Торт "Красный бархат"
            ['product' => 'Торт "Красный бархат"', 'ingredient' => 'Мука пшеничная', 'quantity' => 400],
            ['product' => 'Торт "Красный бархат"', 'ingredient' => 'Сахар', 'quantity' => 300],
            ['product' => 'Торт "Красный бархат"', 'ingredient' => 'Какао-порошок', 'quantity' => 50],
            ['product' => 'Торт "Красный бархат"', 'ingredient' => 'Масло сливочное', 'quantity' => 200],
            ['product' => 'Торт "Красный бархат"', 'ingredient' => 'Яйца куриные', 'quantity' => 3],
            ['product' => 'Торт "Красный бархат"', 'ingredient' => 'Сметана', 'quantity' => 300],

            // Торт "Медовик"
            ['product' => 'Торт "Медовик"', 'ingredient' => 'Мука пшеничная', 'quantity' => 600],
            ['product' => 'Торт "Медовик"', 'ingredient' => 'Сахар', 'quantity' => 300],
            ['product' => 'Торт "Медовик"', 'ingredient' => 'Масло сливочное', 'quantity' => 150],
            ['product' => 'Торт "Медовик"', 'ingredient' => 'Яйца куриные', 'quantity' => 3],
            ['product' => 'Торт "Медовик"', 'ingredient' => 'Сметана', 'quantity' => 500],
            ['product' => 'Торт "Медовик"', 'ingredient' => 'Сода пищевая', 'quantity' => 10],

            // Торт "Чизкейк"
            ['product' => 'Торт "Чизкейк"', 'ingredient' => 'Мука пшеничная', 'quantity' => 200],
            ['product' => 'Торт "Чизкейк"', 'ingredient' => 'Сахар', 'quantity' => 200],
            ['product' => 'Торт "Чизкейк"', 'ingredient' => 'Масло сливочное', 'quantity' => 100],
            ['product' => 'Торт "Чизкейк"', 'ingredient' => 'Сливки 33%', 'quantity' => 400],
            ['product' => 'Торт "Чизкейк"', 'ingredient' => 'Яйца куриные', 'quantity' => 4],

            // Торт "Птичье молоко"
            ['product' => 'Торт "Птичье молоко"', 'ingredient' => 'Мука пшеничная', 'quantity' => 150],
            ['product' => 'Торт "Птичье молоко"', 'ingredient' => 'Сахар', 'quantity' => 300],
            ['product' => 'Торт "Птичье молоко"', 'ingredient' => 'Яйца куриные', 'quantity' => 6],
            ['product' => 'Торт "Птичье молоко"', 'ingredient' => 'Шоколад темный', 'quantity' => 200],

            // Печенье "Овсяное"
            ['product' => 'Печенье "Овсяное"', 'ingredient' => 'Мука пшеничная', 'quantity' => 200],
            ['product' => 'Печенье "Овсяное"', 'ingredient' => 'Сахар', 'quantity' => 150],
            ['product' => 'Печенье "Овсяное"', 'ingredient' => 'Масло сливочное', 'quantity' => 100],
            ['product' => 'Печенье "Овсяное"', 'ingredient' => 'Орехи грецкие', 'quantity' => 50],
            ['product' => 'Печенье "Овсяное"', 'ingredient' => 'Яйца куриные', 'quantity' => 2],

            // Печенье "Шоколадное"
            ['product' => 'Печенье "Шоколадное"', 'ingredient' => 'Мука пшеничная', 'quantity' => 200],
            ['product' => 'Печенье "Шоколадное"', 'ingredient' => 'Сахар', 'quantity' => 120],
            ['product' => 'Печенье "Шоколадное"', 'ingredient' => 'Шоколад темный', 'quantity' => 150],
            ['product' => 'Печенье "Шоколадное"', 'ingredient' => 'Масло сливочное', 'quantity' => 100],
            ['product' => 'Печенье "Шоколадное"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],

            // Печенье "Имбирное"
            ['product' => 'Печенье "Имбирное"', 'ingredient' => 'Мука пшеничная', 'quantity' => 300],
            ['product' => 'Печенье "Имбирное"', 'ingredient' => 'Сахар', 'quantity' => 150],
            ['product' => 'Печенье "Имбирное"', 'ingredient' => 'Корица молотая', 'quantity' => 10],
            ['product' => 'Печенье "Имбирное"', 'ingredient' => 'Масло сливочное', 'quantity' => 100],
            ['product' => 'Печенье "Имбирное"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],
            ['product' => 'Печенье "Имбирное"', 'ingredient' => 'Сода пищевая', 'quantity' => 5],

            // Печенье "Песочное"
            ['product' => 'Печенье "Песочное"', 'ingredient' => 'Мука пшеничная', 'quantity' => 250],
            ['product' => 'Печенье "Песочное"', 'ingredient' => 'Сахар', 'quantity' => 100],
            ['product' => 'Печенье "Песочное"', 'ingredient' => 'Масло сливочное', 'quantity' => 150],
            ['product' => 'Печенье "Песочное"', 'ingredient' => 'Ванильный сахар', 'quantity' => 10],
            ['product' => 'Печенье "Песочное"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],

            // Печенье "Макаронс"
            ['product' => 'Печенье "Макаронс"', 'ingredient' => 'Миндаль', 'quantity' => 200],
            ['product' => 'Печенье "Макаронс"', 'ingredient' => 'Сахарная пудра', 'quantity' => 200],
            ['product' => 'Печенье "Макаронс"', 'ingredient' => 'Яйца куриные', 'quantity' => 3],

            // Тирамису
            ['product' => 'Тирамису', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Тирамису', 'ingredient' => 'Сахар', 'quantity' => 100],
            ['product' => 'Тирамису', 'ingredient' => 'Яйца куриные', 'quantity' => 4],
            ['product' => 'Тирамису', 'ingredient' => 'Сливки 33%', 'quantity' => 300],

            // Панакота с ягодами
            ['product' => 'Панакота с ягодами', 'ingredient' => 'Сливки 33%', 'quantity' => 250],
            ['product' => 'Панакота с ягодами', 'ingredient' => 'Сахар', 'quantity' => 50],
            ['product' => 'Панакота с ягодами', 'ingredient' => 'Молоко', 'quantity' => 100],

            // Крем-брюле
            ['product' => 'Крем-брюле', 'ingredient' => 'Сливки 33%', 'quantity' => 200],
            ['product' => 'Крем-брюле', 'ingredient' => 'Сахар', 'quantity' => 80],
            ['product' => 'Крем-брюле', 'ingredient' => 'Яйца куриные', 'quantity' => 3],
            ['product' => 'Крем-брюле', 'ingredient' => 'Ванильный сахар', 'quantity' => 5],

            // Профитроли с заварным кремом
            ['product' => 'Профитроли с заварным кремом', 'ingredient' => 'Мука пшеничная', 'quantity' => 150],
            ['product' => 'Профитроли с заварным кремом', 'ingredient' => 'Масло сливочное', 'quantity' => 100],
            ['product' => 'Профитроли с заварным кремом', 'ingredient' => 'Яйца куриные', 'quantity' => 4],
            ['product' => 'Профитроли с заварным кремом', 'ingredient' => 'Сливки 33%', 'quantity' => 200],
            ['product' => 'Профитроли с заварным кремом', 'ingredient' => 'Сахар', 'quantity' => 100],

            // Эклеры
            ['product' => 'Эклеры', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Эклеры', 'ingredient' => 'Масло сливочное', 'quantity' => 80],
            ['product' => 'Эклеры', 'ingredient' => 'Яйца куриные', 'quantity' => 3],
            ['product' => 'Эклеры', 'ingredient' => 'Сливки 33%', 'quantity' => 150],
            ['product' => 'Эклеры', 'ingredient' => 'Сахар', 'quantity' => 80],

            // Пирог с яблоками
            ['product' => 'Пирог с яблоками', 'ingredient' => 'Мука пшеничная', 'quantity' => 400],
            ['product' => 'Пирог с яблоками', 'ingredient' => 'Сахар', 'quantity' => 200],
            ['product' => 'Пирог с яблоками', 'ingredient' => 'Масло сливочное', 'quantity' => 200],
            ['product' => 'Пирог с яблоками', 'ingredient' => 'Корица молотая', 'quantity' => 5],
            ['product' => 'Пирог с яблоками', 'ingredient' => 'Яйца куриные', 'quantity' => 2],

            // Пирог с вишней
            ['product' => 'Пирог с вишней', 'ingredient' => 'Мука пшеничная', 'quantity' => 350],
            ['product' => 'Пирог с вишней', 'ingredient' => 'Сахар', 'quantity' => 180],
            ['product' => 'Пирог с вишней', 'ingredient' => 'Масло сливочное', 'quantity' => 180],
            ['product' => 'Пирог с вишней', 'ingredient' => 'Яйца куриные', 'quantity' => 2],

            // Пирог с капустой
            ['product' => 'Пирог с капустой', 'ingredient' => 'Мука пшеничная', 'quantity' => 400],
            ['product' => 'Пирог с капустой', 'ingredient' => 'Масло сливочное', 'quantity' => 150],
            ['product' => 'Пирог с капустой', 'ingredient' => 'Яйца куриные', 'quantity' => 3],
            ['product' => 'Пирог с капустой', 'ingredient' => 'Растительное масло', 'quantity' => 50],

            // Пирог с мясом
            ['product' => 'Пирог с мясом', 'ingredient' => 'Мука пшеничная', 'quantity' => 500],
            ['product' => 'Пирог с мясом', 'ingredient' => 'Масло сливочное', 'quantity' => 200],
            ['product' => 'Пирог с мясом', 'ingredient' => 'Яйца куриные', 'quantity' => 2],

            // Пирог с творогом
            ['product' => 'Пирог с творогом', 'ingredient' => 'Мука пшеничная', 'quantity' => 350],
            ['product' => 'Пирог с творогом', 'ingredient' => 'Сахар', 'quantity' => 150],
            ['product' => 'Пирог с творогом', 'ingredient' => 'Масло сливочное', 'quantity' => 150],
            ['product' => 'Пирог с творогом', 'ingredient' => 'Яйца куриные', 'quantity' => 3],
            ['product' => 'Пирог с творогом', 'ingredient' => 'Сметана', 'quantity' => 200],

            // Бенто-торты (упрощенные рецепты)
            ['product' => 'Бенто-торт "Клубничный"', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Бенто-торт "Клубничный"', 'ingredient' => 'Сахар', 'quantity' => 80],
            ['product' => 'Бенто-торт "Клубничный"', 'ingredient' => 'Масло сливочное', 'quantity' => 60],
            ['product' => 'Бенто-торт "Клубничный"', 'ingredient' => 'Сливки 33%', 'quantity' => 100],
            ['product' => 'Бенто-торт "Клубничный"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],

            ['product' => 'Бенто-торт "Шоколадный"', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Бенто-торт "Шоколадный"', 'ingredient' => 'Сахар', 'quantity' => 80],
            ['product' => 'Бенто-торт "Шоколадный"', 'ingredient' => 'Шоколад темный', 'quantity' => 100],
            ['product' => 'Бенто-торт "Шоколадный"', 'ingredient' => 'Масло сливочное', 'quantity' => 60],
            ['product' => 'Бенто-торт "Шоколадный"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],

            ['product' => 'Бенто-торт "Ванильный"', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Бенто-торт "Ванильный"', 'ingredient' => 'Сахар', 'quantity' => 80],
            ['product' => 'Бенто-торт "Ванильный"', 'ingredient' => 'Ванильный сахар', 'quantity' => 5],
            ['product' => 'Бенто-торт "Ванильный"', 'ingredient' => 'Масло сливочное', 'quantity' => 60],
            ['product' => 'Бенто-торт "Ванильный"', 'ingredient' => 'Сливки 33%', 'quantity' => 100],
            ['product' => 'Бенто-торт "Ванильный"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],

            ['product' => 'Бенто-торт "Карамельный"', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Бенто-торт "Карамельный"', 'ingredient' => 'Сахар', 'quantity' => 100],
            ['product' => 'Бенто-торт "Карамельный"', 'ingredient' => 'Масло сливочное', 'quantity' => 60],
            ['product' => 'Бенто-торт "Карамельный"', 'ingredient' => 'Сливки 33%', 'quantity' => 100],
            ['product' => 'Бенто-торт "Карамельный"', 'ingredient' => 'Орехи грецкие', 'quantity' => 30],
            ['product' => 'Бенто-торт "Карамельный"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],

            ['product' => 'Бенто-торт "Фруктовый"', 'ingredient' => 'Мука пшеничная', 'quantity' => 100],
            ['product' => 'Бенто-торт "Фруктовый"', 'ingredient' => 'Сахар', 'quantity' => 80],
            ['product' => 'Бенто-торт "Фруктовый"', 'ingredient' => 'Масло сливочное', 'quantity' => 60],
            ['product' => 'Бенто-торт "Фруктовый"', 'ingredient' => 'Сливки 33%', 'quantity' => 100],
            ['product' => 'Бенто-торт "Фруктовый"', 'ingredient' => 'Яйца куриные', 'quantity' => 1],
        ];

        foreach ($recepts as $recept) {
            if (isset($products[$recept['product']]) && isset($ingredients[$recept['ingredient']])) {
                Recept::firstOrCreate(
                    [
                        'idProduct' => $products[$recept['product']]->id,
                        'idIngredient' => $ingredients[$recept['ingredient']]->id,
                    ],
                    [
                        'quantity' => $recept['quantity'],
                    ]
                );
            }
        }
    }
}

