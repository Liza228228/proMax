<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем категории
        $tortCategory = Category::where('name_category', 'Торты')->first();
        $cookieCategory = Category::where('name_category', 'Печенья')->first();
        $dessertCategory = Category::where('name_category', 'Десерты')->first();
        $pieCategory = Category::where('name_category', 'Пироги')->first();
        $bentoCategory = Category::where('name_category', 'Бенто-торты')->first();

        // Проверяем, что категории существуют
        if (!$tortCategory || !$cookieCategory || !$dessertCategory || !$pieCategory || !$bentoCategory) {
            $this->command->error('Категории не найдены. Убедитесь, что CategorySeeder выполнен первым.');
            return;
        }

        $products = [
            // Торты
            [
                'name_product' => 'Торт "Наполеон"',
                'description' => 'Классический торт Наполеон с нежным заварным кремом. Состоит из множества слоев тонкого теста.',
                'weight' => 1500.00,
                'price' => 2500.00,
                'available' => true,
                'expiration_date' => 7, // Срок годности 7 дней
                'idCategory' => $tortCategory->id,
            ],
            [
                'name_product' => 'Торт "Красный бархат"',
                'description' => 'Нежный бисквит с крем-чизом и ягодами. Яркий и праздничный торт.',
                'weight' => 1800.00,
                'price' => 3200.00,
                'available' => true,
                'expiration_date' => 7,
                'idCategory' => $tortCategory->id,
            ],
            [
                'name_product' => 'Торт "Медовик"',
                'description' => 'Традиционный медовый торт с нежным сметанным кремом.',
                'weight' => 1600.00,
                'price' => 2800.00,
                'available' => true,
                'expiration_date' => 7,
                'idCategory' => $tortCategory->id,
            ],
            [
                'name_product' => 'Торт "Чизкейк"',
                'description' => 'Классический чизкейк с ягодным топпингом. Нежный и воздушный.',
                'weight' => 1200.00,
                'price' => 2900.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $tortCategory->id,
            ],
            [
                'name_product' => 'Торт "Птичье молоко"',
                'description' => 'Воздушный бисквит с суфле и шоколадной глазурью.',
                'weight' => 1400.00,
                'price' => 2700.00,
                'available' => true,
                'expiration_date' => 7,
                'idCategory' => $tortCategory->id,
            ],

            // Печенья
            [
                'name_product' => 'Печенье "Овсяное"',
                'description' => 'Домашнее овсяное печенье с изюмом и орехами.',
                'weight' => 300.00,
                'price' => 350.00,
                'available' => true,
                'expiration_date' => 14,
                'idCategory' => $cookieCategory->id,
            ],
            [
                'name_product' => 'Печенье "Шоколадное"',
                'description' => 'Хрустящее шоколадное печенье с кусочками шоколада.',
                'weight' => 250.00,
                'price' => 380.00,
                'available' => true,
                'expiration_date' => 14,
                'idCategory' => $cookieCategory->id,
            ],
            [
                'name_product' => 'Печенье "Имбирное"',
                'description' => 'Ароматное имбирное печенье с глазурью. Идеально к чаю.',
                'weight' => 200.00,
                'price' => 320.00,
                'available' => true,
                'expiration_date' => 14,
                'idCategory' => $cookieCategory->id,
            ],
            [
                'name_product' => 'Печенье "Песочное"',
                'description' => 'Нежное песочное печенье с ванилью.',
                'weight' => 280.00,
                'price' => 300.00,
                'available' => true,
                'expiration_date' => 14,
                'idCategory' => $cookieCategory->id,
            ],
            [
                'name_product' => 'Печенье "Макаронс"',
                'description' => 'Французское миндальное печенье с различными начинками.',
                'weight' => 150.00,
                'price' => 450.00,
                'available' => true,
                'expiration_date' => 7,
                'idCategory' => $cookieCategory->id,
            ],

            // Десерты
            [
                'name_product' => 'Тирамису',
                'description' => 'Классический итальянский десерт с кофе и маскарпоне.',
                'weight' => 200.00,
                'price' => 550.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $dessertCategory->id,
            ],
            [
                'name_product' => 'Панакота с ягодами',
                'description' => 'Нежный молочный десерт с ягодным соусом.',
                'weight' => 180.00,
                'price' => 420.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $dessertCategory->id,
            ],
            [
                'name_product' => 'Крем-брюле',
                'description' => 'Классический французский десерт с хрустящей карамелью.',
                'weight' => 150.00,
                'price' => 480.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $dessertCategory->id,
            ],
            [
                'name_product' => 'Профитроли с заварным кремом',
                'description' => 'Воздушные профитроли с нежным заварным кремом и шоколадом.',
                'weight' => 250.00,
                'price' => 520.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $dessertCategory->id,
            ],
            [
                'name_product' => 'Эклеры',
                'description' => 'Заварные пирожные с различными начинками.',
                'weight' => 120.00,
                'price' => 380.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $dessertCategory->id,
            ],

            // Пироги
            [
                'name_product' => 'Пирог с яблоками',
                'description' => 'Домашний яблочный пирог с корицей.',
                'weight' => 800.00,
                'price' => 1200.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $pieCategory->id,
            ],
            [
                'name_product' => 'Пирог с вишней',
                'description' => 'Сочный пирог с вишней и нежным тестом.',
                'weight' => 750.00,
                'price' => 1150.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $pieCategory->id,
            ],
            [
                'name_product' => 'Пирог с капустой',
                'description' => 'Сытный пирог с капустой и яйцом.',
                'weight' => 900.00,
                'price' => 980.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $pieCategory->id,
            ],
            [
                'name_product' => 'Пирог с мясом',
                'description' => 'Сытный пирог с мясной начинкой.',
                'weight' => 1000.00,
                'price' => 1400.00,
                'available' => true,
                'expiration_date' => 3,
                'idCategory' => $pieCategory->id,
            ],
            [
                'name_product' => 'Пирог с творогом',
                'description' => 'Нежный пирог с творожной начинкой.',
                'weight' => 850.00,
                'price' => 1100.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $pieCategory->id,
            ],

            // Бенто-торты
            [
                'name_product' => 'Бенто-торт "Клубничный"',
                'description' => 'Мини-торт с клубникой и кремом. Идеально для одного человека.',
                'weight' => 300.00,
                'price' => 650.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $bentoCategory->id,
            ],
            [
                'name_product' => 'Бенто-торт "Шоколадный"',
                'description' => 'Небольшой шоколадный торт с орехами.',
                'weight' => 320.00,
                'price' => 680.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $bentoCategory->id,
            ],
            [
                'name_product' => 'Бенто-торт "Ванильный"',
                'description' => 'Нежный ванильный мини-торт с ягодами.',
                'weight' => 300.00,
                'price' => 620.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $bentoCategory->id,
            ],
            [
                'name_product' => 'Бенто-торт "Карамельный"',
                'description' => 'Мини-торт с карамелью и орехами.',
                'weight' => 310.00,
                'price' => 700.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $bentoCategory->id,
            ],
            [
                'name_product' => 'Бенто-торт "Фруктовый"',
                'description' => 'Свежий мини-торт с различными фруктами.',
                'weight' => 290.00,
                'price' => 640.00,
                'available' => true,
                'expiration_date' => 5,
                'idCategory' => $bentoCategory->id,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['name_product' => $productData['name_product']],
                [
                    'description' => $productData['description'],
                    'weight' => $productData['weight'],
                    'price' => $productData['price'],
                    'available' => $productData['available'],
                    'expiration_date' => $productData['expiration_date'] ?? 7, // По умолчанию 7 дней
                    'idCategory' => $productData['idCategory'],
                ]
            );

            // Ищем реальные изображения в папке продукта
            $this->loadProductImages($product);
        }
    }

    /**
     * Загружает реальные изображения из папки продукта
     */
    private function loadProductImages(Product $product): void
    {
        $productFolder = 'image/product/' . $product->id;
        $productFolderPath = public_path($productFolder);

        // Проверяем, существует ли папка
        if (!file_exists($productFolderPath) || !is_dir($productFolderPath)) {
            $this->command->warn("Папка не найдена для продукта: {$product->name_product} (ID: {$product->id})");
            return;
        }

        // Разрешенные расширения изображений
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        
        // Получаем все файлы из папки
        $files = scandir($productFolderPath);
        $imageFiles = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $productFolderPath . '/' . $file;
            
            // Проверяем, что это файл, а не папка
            if (!is_file($filePath)) {
                continue;
            }

            // Получаем расширение файла
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            // Проверяем, что это изображение
            if (in_array($extension, $allowedExtensions)) {
                $imageFiles[] = $file;
            }
        }

        // Сортируем файлы по имени для предсказуемого порядка
        sort($imageFiles);

        if (empty($imageFiles)) {
            $this->command->warn("Изображения не найдены в папке для продукта: {$product->name_product} (ID: {$product->id})");
            return;
        }

        // Удаляем старые записи изображений для этого продукта
        ProductImage::where('idProduct', $product->id)->delete();

        // Создаем записи для каждого найденного изображения
        foreach ($imageFiles as $index => $imageFile) {
            $imagePath = $productFolder . '/' . $imageFile;
            
            ProductImage::create([
                'idProduct' => $product->id,
                'path' => $imagePath,
                'is_primary' => $index === 0 ? 1 : 0, // Первое изображение - основное
            ]);
        }

        $this->command->info("Загружено " . count($imageFiles) . " изображений для продукта: {$product->name_product} (ID: {$product->id})");
    }
}

