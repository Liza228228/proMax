<?php

namespace Tests\Feature\Manager;

use App\Models\Ingredient;
use App\Models\StockIngredient;
use App\Models\UnitType;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тестовый класс для проверки функционала перемещения ингредиентов между складами.
 * Соответствует тестовому сценарию TC-005.
 */
class WarehouseTransferTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group test-scenarios
     * @testdox Менеджер не может перевести больше ингредиентов между складами, чем есть в наличии
     */
    public function test_manager_cannot_transfer_more_ingredients_than_available(): void
    {
        $manager = User::create([
            'first_name' => 'Менеджер',
            'last_name' => 'Тест',
            'phone' => '+79991112233',
            'login' => 'manager_test',
            'password' => bcrypt('password'),
            'role' => 1
        ]);

        $warehouse1 = Warehouse::create([
            'name' => 'Склад 1',
            'city' => 'Иркутск',
            'street' => 'Ленина',
            'house' => '1',
            'is_main' => true
        ]);
        
        $warehouse2 = Warehouse::create([
            'name' => 'Склад 2',
            'city' => 'Иркутск',
            'street' => 'Карла Маркса',
            'house' => '2',
            'is_main' => false
        ]);

        $unitType = UnitType::create(['name' => 'Масса']);
        $unit = $unitType->units()->create(['name' => 'грамм', 'code' => 'г', 'is_base' => true, 'multiplier_to_base' => 1]);

        $ingredient = Ingredient::create([
            'name' => 'Мука',
            'description' => 'Тест',
            'unit_type_id' => $unitType->id,
            'expiration_date' => 365
        ]);

        StockIngredient::create([
            'idWarehouse' => $warehouse1->id,
            'idIngredient' => $ingredient->id,
            'quantity' => 100,
            'expiration_date' => now()->addDays(30)
        ]);

        $response = $this->actingAs($manager)->post("/manager/warehouses/{$warehouse1->id}/transfer", [
            'to_warehouse_id' => $warehouse2->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 200, // Больше, чем доступно
            'expiration_date' => now()->addDays(60)->format('Y-m-d'),
            'unit_id' => $ingredient->unitType->units->first()->id ?? 1
        ]);

        // Проверяем, что произошел редирект с ошибкой
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
