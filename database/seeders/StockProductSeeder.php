<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockProduct;
use Carbon\Carbon;

class StockProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('Продукты не найдены. Убедитесь, что ProductSeeder выполнен первым.');
            return;
        }

        foreach ($products as $product) {
            // Получаем срок годности в днях из продукта
            $expirationDays = $product->expiration_date ?? 7;
            
            // Создаем несколько партий продукта с разным количеством и датами
            $batches = [
                [
                    'quantity' => rand(5, 15),
                    'days_offset' => 0, // Сегодня
                ],
                [
                    'quantity' => rand(3, 10),
                    'days_offset' => -2, // 2 дня назад
                ],
                [
                    'quantity' => rand(2, 8),
                    'days_offset' => -5, // 5 дней назад
                ],
            ];

            foreach ($batches as $batch) {
                // Вычисляем дату истечения срока годности
                // Текущая дата + смещение + срок годности продукта
                $expirationDate = Carbon::today()
                    ->addDays($batch['days_offset'])
                    ->addDays($expirationDays);

                // Создаем запись в stocks_products только если срок годности еще не истек
                if ($expirationDate->isFuture() || $expirationDate->isToday()) {
                    StockProduct::create([
                        'id_product' => $product->id,
                        'quantity' => $batch['quantity'],
                        'expiration_date' => $expirationDate,
                    ]);
                }
            }

            // Обновляем доступность продукта
            $totalQuantity = $product->stockProducts()
                ->where('expiration_date', '>=', Carbon::today())
                ->sum('quantity');
            
            $product->available = $totalQuantity > 0;
            $product->save();
        }

        $this->command->info("Создано записей в stocks_products для " . $products->count() . " продуктов.");
    }
}

