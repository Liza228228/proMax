<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('login', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            'login' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'integer', 'in:2,3'], // Только администратор (2) или менеджер (3)
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
            'password.string' => 'Поле "Пароль" должно быть строкой.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'role.required' => 'Поле "Роль" обязательно для заполнения.',
            'role.integer' => 'Поле "Роль" должно быть числом.',
            'role.in' => 'Роль должна быть администратором или менеджером.',
        ]);

        // Очистка телефона от форматирования для сохранения
        $phone = preg_replace('/\D/', '', $request->phone);
        if (strlen($phone) === 11 && $phone[0] === '7') {
            $phone = '+' . $phone;
        } elseif (strlen($phone) === 10) {
            $phone = '+7' . $phone;
        }

        User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'phone' => $phone,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно создан.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $currentUser = Auth::user();
        
        // Запрет редактирования обычных пользователей (роль 1)
        if ($user->role == 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Нельзя редактировать данные обычных пользователей.');
        }
        
        return view('admin.users.edit', compact('user', 'currentUser'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $currentUser = Auth::user();
        
        // Запрет редактирования обычных пользователей (роль 1)
        if ($user->role == 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Нельзя редактировать данные обычных пользователей.');
        }
        
        // Проверка: нельзя изменять роль администраторам (себе и другим)
        if ($user->role == 2 || ($currentUser && $currentUser->id == $user->id && $currentUser->role == 2)) {
            // Если пытаются изменить роль администратора, запрещаем изменение роли
            $request->merge(['role' => $user->role]);
        }

        $validationRules = [
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,'.$user->id, 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            'login' => ['required', 'string', 'max:20', 'unique:users,login,'.$user->id],
            'role' => ['required', 'integer', 'in:1,2,3'],
        ];

        // Если пароль указан, добавляем валидацию с подтверждением
        if ($request->filled('password')) {
            $validationRules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $request->validate($validationRules, [
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
            'password.string' => 'Поле "Пароль" должно быть строкой.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'role.required' => 'Поле "Роль" обязательно для заполнения.',
            'role.integer' => 'Поле "Роль" должно быть числом.',
            'role.in' => 'Роль должна быть пользователем, администратором или менеджером.',
        ]);

        // Очистка телефона от форматирования для сохранения
        $phone = preg_replace('/\D/', '', $request->phone);
        if (strlen($phone) === 11 && $phone[0] === '7') {
            $phone = '+' . $phone;
        } elseif (strlen($phone) === 10) {
            $phone = '+7' . $phone;
        }

        $data = [
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'phone' => $phone,
            'login' => $request->login,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно обновлен.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $currentUser = Auth::user();
        
        // Проверка: администратор не может удалить сам себя
        if ($currentUser && (int)$currentUser->id === (int)$user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Вы не можете удалить свой собственный аккаунт.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален.');
    }
}

