<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тестовый класс для проверки генерации PDF-отчетов
 * Соответствует тестовому сценарию TC-003
 */
class ReportGenerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group test-scenarios
     * @testdox Администратор может сгенерировать PDF-отчет по заказам за период
     */
    public function test_admin_can_generate_orders_pdf_report_for_period(): void
    {
        // === ARRANGE ===
        $admin = User::create([
            'first_name' => 'Админ',
            'last_name' => 'Тест',
            'phone' => '+79991234567',
            'login' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 2
        ]);

        $customer = User::create([
            'first_name' => 'Пользователь',
            'last_name' => 'Тест',
            'phone' => '+79997654321',
            'login' => 'user_test',
            'password' => bcrypt('password'),
            'role' => 0
        ]);

        Order::create([
            'idUser' => $customer->id,
            'total_amount' => 1000.00,
            'status' => 'Создан'
        ]);

        // === ACT ===
        $response = $this->actingAs($admin)->post('/admin/reports/orders', [
            'date_from' => now()->subDays(7)->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d')
        ]);

        // === ASSERT ===
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
