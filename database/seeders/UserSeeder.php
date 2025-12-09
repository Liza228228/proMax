<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Администратор (роль 2)
        User::updateOrCreate(
            ['login' => '2'],
            [
                'last_name' => '2',
                'first_name' => '2',
                'phone' => '+7 (222) 222-22-22',
                'password' => Hash::make('11111111'),
                'role' => 2,
            ]
        );

        // Менеджер (роль 3)
        User::updateOrCreate(
            ['login' => '3'],
            [
                'last_name' => '3',
                'first_name' => '3',
                'phone' => '+7 (333) 333-33-33',
                'password' => Hash::make('11111111'),
                'role' => 3,
            ]
        );

        // Обычный пользователь (роль 1)
        User::updateOrCreate(
            ['login' => '1'],
            [
                'last_name' => '1',
                'first_name' => '1',
                'phone' => '+7 (111) 111-11-11',
                'password' => Hash::make('11111111'),
                'role' => 1,
            ]
        );
    }
}

