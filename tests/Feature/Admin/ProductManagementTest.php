<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\User;
use App\Models\UnitType;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Тестовый класс для проверки управления продукцией администратором.
 * Соответствует тестовым сценариям TC-002 и TC-004.
 */
class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group test-scenarios
     * @testdox Администратор может создать продукцию с изображениями и рецептом
     */
    public function test_admin_can_create_product_with_images_and_recipe(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'first_name' => 'Админ',
            'last_name' => 'Тест',
            'phone' => '+79991234567',
            'login' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 2
        ]);

        $category = Category::create(['name_category' => 'Печенья', 'available' => true]);
        
        $unitType = UnitType::create(['name' => 'Масса']);
        $unit = Unit::create(['unit_type_id' => $unitType->id, 'name' => 'грамм', 'code' => 'г', 'is_base' => true, 'multiplier_to_base' => 1]);
        
        $ingredient = Ingredient::create([
            'name' => 'Мука',
            'description' => 'Тест',
            'unit_type_id' => $unitType->id,
            'expiration_date' => 365
        ]);

        $image = UploadedFile::fake()->image('test.jpg');
        
        $response = $this->actingAs($admin)->post('/admin/products', [
            'name_product' => 'Печенье тестовое',
            'description' => 'Тест',
            'weight' => 300,
            'price' => 350.00,
            'idCategory' => $category->id,
            'expiration_days' => 14,
            'available' => 1,
            'images' => [$image],
            'ingredients' => [
                ['id' => $ingredient->id, 'quantity' => 200, 'unit_id' => $unit->id]
            ]
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['name_product' => 'Печенье тестовое']);
    }

    /**
     * @group test-scenarios
     * @testdox Администратор не может создать товар без обязательных изображений
     */
    public function test_admin_cannot_create_product_without_images(): void
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
        
        $unitType = UnitType::create(['name' => 'Масса']);
        $unit = Unit::create(['unit_type_id' => $unitType->id, 'name' => 'грамм', 'code' => 'г', 'is_base' => true, 'multiplier_to_base' => 1]);
        
        $ingredient = Ingredient::create([
            'name' => 'Мука',
            'description' => 'Тест',
            'unit_type_id' => $unitType->id,
            'expiration_date' => 365
        ]);

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name_product' => 'Торт тестовый',
            'description' => 'Тест',
            'weight' => 1500,
            'price' => 2500.00,
            'idCategory' => $category->id,
            'expiration_days' => 7,
            'available' => 1,
            'images' => [],
            'ingredients' => [
                ['id' => $ingredient->id, 'quantity' => 500, 'unit_id' => $unit->id]
            ]
        ]);

        $response->assertSessionHasErrors('images');
        $this->assertDatabaseMissing('products', ['name_product' => 'Торт тестовый']);
    }
}
