<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Если передан параметр redirect, сохраняем его в сессии
        if ($request->has('redirect')) {
            $redirectParam = $request->get('redirect');
            
            // Определяем URL для редиректа в зависимости от параметра
            $redirectUrl = match($redirectParam) {
                'cart' => route('cart.index'),
                'checkout' => route('cart.checkout'),
                default => null
            };
            
            // Сохраняем URL в сессии, если он определен
            if ($redirectUrl) {
                session()->put('url.intended', $redirectUrl);
            }
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Сохраняем старый session_id ДО аутентификации для переноса корзины
        $oldSessionId = $request->session()->getId();
        
        // Аутентифицируем пользователя
        $request->authenticate();
        
        // Получаем пользователя
        $user = Auth::user();
        
        // Регенерируем сессию (это создаст новый session_id)
        $request->session()->regenerate();

        // Обрабатываем корзину гостя в зависимости от роли пользователя
        if ($user && $user->id && $oldSessionId) {
            try {
                // Если пользователь - клиент (роль 1), переносим корзину
                if ($user->isUser()) {
                    Cart::mergeGuestCart($oldSessionId, $user->id);
                } else {
                    // Если пользователь не клиент (администратор, менеджер и т.д.), удаляем корзину гостя
                    Cart::deleteGuestCart($oldSessionId);
                }
            } catch (\Exception $e) {
                // Логируем ошибку, но не прерываем процесс авторизации
                \Log::error('Ошибка при обработке корзины: ' . $e->getMessage());
            }
        }

        // Всех пользователей перенаправляем на главную страницу
        return redirect()->intended(route('index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
