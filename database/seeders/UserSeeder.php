<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        // Администратор (роль 2)
        User::updateOrCreate(
            ['login' => 'admin'],
            [
                'last_name' => 'Иванов',
                'first_name' => 'Иван',
                'phone' => '+7 (222) 222-22-22',
                'password' => Hash::make('11111111'),
                'role' => 2,
            ]
        );

        // Менеджер (роль 3)
        User::updateOrCreate(
            ['login' => 'menager'],
            [
                'last_name' => 'Федоров',
                'first_name' => 'Кирилл',
                'phone' => '+7 (333) 333-33-33',
                'password' => Hash::make('11111111'),
                'role' => 3,
            ]
        );

        // Обычный пользователь (роль 1)
        User::updateOrCreate(
            ['login' => 'user'],
            [
                'last_name' => 'Абросимова',
                'first_name' => 'Анастасия',
                'phone' => '+7 (111) 111-11-11',
                'password' => Hash::make('11111111'),
                'role' => 1,
            ]
        );
    }
}

