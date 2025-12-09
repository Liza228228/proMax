<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Product;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем все продукты для получения их ID
        $products = Product::all()->keyBy('name_product');

        if ($products->isEmpty()) {
            $this->command->error('Продукты не найдены. Убедитесь, что ProductSeeder выполнен первым.');
            return;
        }

        // Массив с изображениями для каждого продукта
        // Ключ - название продукта, значение - массив путей к изображениям
        $productImages = [
            // Торты
            'Торт "Наполеон"' => [
                'image/product/1/tor_nap1.jpg',
                'image/product/1/tor_nap2.webp',
            ],
            'Торт "Красный бархат"' => [
                'image/product/2/tor_bark1.jpg',
                'image/product/2/tor_bark2.jpg',
            ],
            'Торт "Медовик"' => [
                'image/product/3/tor_med1.jpg',
                'image/product/3/tor_med2.jpg',
            ],
            'Торт "Чизкейк"' => [
                'image/product/4/tor_chisk1.webp',
                'image/product/4/tor_chisk2.jpg',
            ],
            'Торт "Птичье молоко"' => [
                'image/product/5/tor_p_m1.jpg',
                'image/product/5/tor_p_m2.webp',
            ],

            // Печенья
            'Печенье "Овсяное"' => [
                'image/product/6/pe_ovs1.jpg',
                'image/product/6/pe_ovs2.jpg',
            ],
            'Печенье "Шоколадное"' => [
                'image/product/7/pe_shok1.webp',
                'image/product/7/pe_shok2.jpg',
            ],
            'Печенье "Имбирное"' => [
                'image/product/8/pe_imb1.jpg',
                'image/product/8/pe_imb2.jpg',
            ],
            'Печенье "Песочное"' => [
                'image/product/9/pe_pes1.jpg',
                'image/product/9/pe_pes2.jpg',
            ],
            'Печенье "Макаронс"' => [
                'image/product/10/pe_makrons1.jpg',
                'image/product/10/pe_makrons2.webp',
            ],

            // Десерты
            'Тирамису' => [
                'image/product/11/d_t1.jpg',
                'image/product/11/d_t2.jpg',
                'image/product/11/d_t3.jpg',
            ],
            'Панакота с ягодами' => [
                'image/product/12/d_p1.jpg',
                'image/product/12/d_p2.jpg',
                'image/product/12/d_p3.jpg',
            ],
            'Крем-брюле' => [
                'image/product/13/d_k_br1.png',
                'image/product/13/d_k_br2.webp',
            ],
            'Профитроли с заварным кремом' => [
                'image/product/14/d_prof1.jpg',
                'image/product/14/d_prof2.avif',
                'image/product/14/d_prof3.jpg',
            ],
            'Эклеры' => [
                'image/product/15/d_ek1.jpg',
                'image/product/15/d_ek2.jpg',
                'image/product/15/d_ek3.jpg',
            ],

            // Пироги
            'Пирог с яблоками' => [
                'image/product/16/pir_aple1.jpg',
                'image/product/16/pir_aple2.jpg',
            ],
            'Пирог с вишней' => [
                'image/product/17/pir_vish1.png',
                'image/product/17/pir_vish2.jpg',
            ],
            'Пирог с капустой' => [
                'image/product/18/pir_kap1.webp',
                'image/product/18/pir_kap2.jpg',
            ],
            'Пирог с мясом' => [
                'image/product/19/pir_maso1.jpg',
                'image/product/19/pir_maso2.jpg',
            ],
            'Пирог с творогом' => [
                'image/product/20/pir_tvor1.webp',
                'image/product/20/pir_tvor2.jpg',
            ],

            // Бенто-торты
            'Бенто-торт "Клубничный"' => [
                'image/product/21/b_k1.jpg',
                'image/product/21/b_k2.jpg',
                'image/product/21/b_k3.jpg',
            ],
            'Бенто-торт "Шоколадный"' => [
                'image/product/22/b_sh1.jpeg',
                'image/product/22/b_sh2.jpeg',
                'image/product/22/b_sh3.jpeg',
            ],
            'Бенто-торт "Ванильный"' => [
                'image/product/23/b_v1.jpg',
                'image/product/23/b_v2.png',
                'image/product/23/b_v3.webp',
            ],
            'Бенто-торт "Карамельный"' => [
                'image/product/24/b_kar1.jpg',
                'image/product/24/b_kar2.jpg',
                'image/product/24/b_kar3.jpg',
            ],
            'Бенто-торт "Фруктовый"' => [
                'image/product/25/b_f1.jpg',
                'image/product/25/b_f2.jpg',
                'image/product/25/b_f3.jpg',
            ],
        ];

        $totalImages = 0;

        // Создаем записи изображений для каждого продукта
        foreach ($productImages as $productName => $imagePaths) {
            $product = $products->get($productName);
            
            if (!$product) {
                $this->command->warn("Продукт не найден: {$productName}");
                continue;
            }

            // Удаляем старые записи изображений для этого продукта
            ProductImage::where('idProduct', $product->id)->delete();

            // Создаем записи для каждого изображения
            foreach ($imagePaths as $index => $imagePath) {
                // Проверяем существование файла
                $fullPath = public_path($imagePath);
                if (!file_exists($fullPath)) {
                    $this->command->warn("Файл не найден: {$imagePath}");
                    continue;
                }

                ProductImage::create([
                    'idProduct' => $product->id,
                    'path' => $imagePath,
                    'is_primary' => $index === 0 ? 1 : 0, // Первое изображение - основное
                ]);

                $totalImages++;
            }

            $this->command->info("✓ Загружено " . count($imagePaths) . " изображений для продукта: {$productName} (ID: {$product->id})");
        }
        
        $this->command->info("Сидер изображений продукции завершен. Всего загружено изображений: {$totalImages}");
    }
}

