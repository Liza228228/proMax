<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    public function create(): View
    {
        return view('auth.register');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            'login' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ], [
            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'last_name.string' => 'Поле "Фамилия" должно быть строкой.',
            'last_name.max' => 'Поле "Фамилия" не должно превышать 100 символов.',
            'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
            'first_name.string' => 'Поле "Имя" должно быть строкой.',
            'first_name.max' => 'Поле "Имя" не должно превышать 100 символов.',
            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',
            'phone.max' => 'Поле "Телефон" не должно превышать 20 символов.',
            'phone.unique' => 'Пользователь с таким телефоном уже зарегистрирован.',
            'phone.regex' => 'Телефон должен быть в формате +7 (XXX) XXX-XX-XX.',
            'login.required' => 'Поле "Логин" обязательно для заполнения.',
            'login.string' => 'Поле "Логин" должно быть строкой.',
            'login.max' => 'Поле "Логин" не должно превышать 20 символов.',
            'login.unique' => 'Пользователь с таким логином уже зарегистрирован.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.string' => 'Поле "Пароль" должно быть строкой.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
        ]);

        // Очистка телефона от форматирования для сохранения
        $phone = preg_replace('/\D/', '', $request->phone);
        if (strlen($phone) === 11 && $phone[0] === '7') {
            $phone = '+' . $phone;
        } elseif (strlen($phone) === 10) {
            $phone = '+7' . $phone;
        }

        // Сохраняем session_id ДО создания пользователя и логина для переноса корзины гостя
        $sessionId = $request->session()->getId();

        $user = User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'phone' => $phone,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'role' => 1, // Роль 1 - обычный пользователь
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Переносим корзину гостя в корзину нового пользователя
        // При регистрации сессия не регенерируется, но нужно сохранить session_id до логина
        if ($sessionId && $user->id) {
            try {
                Cart::mergeGuestCart($sessionId, $user->id);
            } catch (\Exception $e) {
                // Логируем ошибку, но не прерываем процесс регистрации
                \Log::error('Ошибка при переносе корзины при регистрации: ' . $e->getMessage());
            }
        }

        // Всех пользователей перенаправляем на главную страницу
        return redirect(route('index', absolute: false));
    }
}
