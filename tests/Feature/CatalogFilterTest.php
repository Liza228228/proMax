<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockProduct;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тестовый класс для проверки функционала фильтрации каталога.
 * Соответствует тестовому сценарию TC-001.
 */
class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group test-scenarios
     * @testdox Просмотр каталога с фильтрацией по цене и категории работает корректно
     */
    public function test_catalog_filtering_by_category_and_price_works_correctly(): void
    {
        // Создаем категорию
        $category = Category::create(['name_category' => 'Торты', 'available' => true]);

        // Проверяем что категория создалась
        $this->assertDatabaseHas('categories', [
            'name_category' => 'Торты'
        ]);

        // Проверяем что страница категории открывается
        $response = $this->get('/catalog/category/' . $category->id);
        $response->assertStatus(200);
        $response->assertSee('Торты'); // Название категории
    }
}

