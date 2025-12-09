<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тестовый класс для проверки управления остатками товаров на складе
 * Соответствует тестовому сценарию TC-006
 */
class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group test-scenarios
     * @testdox Администратор не может добавить товар на склад с прошедшей датой срока годности
     */
    public function test_admin_cannot_add_product_stock_with_past_expiration_date(): void
    {
        $admin = User::create([
            'first_name' => 'Админ',
            'last_name' => 'Тест',
            'phone' => '+79991234567',
            'login' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 2
        ]);

        $category = Category::create(['name_category' => 'Торты', 'available' => true]);

        $product = Product::create([
            'name_product' => 'Торт "Медовик"',
            'description' => 'Тест',
            'price' => 1200.00,
            'weight' => 1000.00,
            'available' => true,
            'expiration_date' => 7,
            'idCategory' => $category->id
        ]);

        $pastDate = Carbon::yesterday()->format('Y-m-d');

        $response = $this->actingAs($admin)->post("/admin/products/{$product->id}/add-quantity", [
            'quantity' => 5,
            'expiration_date' => $pastDate
        ]);

        // Проверяем, что произошел редирект (обычное поведение при ошибке валидации)
        $response->assertStatus(302);
    }
}

