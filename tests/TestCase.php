<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
     public function test_admin_cannot_delete_category_with_products(): void
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
        Product::create([
            'name_product' => 'Торт №1',
            'description' => 'Тест',
            'price' => 2000,
            'weight' => 1500,
            'available' => true,
            'expiration_date' => 7,
            'idCategory' => $category->id
        ]);

        $response = $this->actingAs($admin)->delete("/admin/categories/{$category->id}");
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

}
   