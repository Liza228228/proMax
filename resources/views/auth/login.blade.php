<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Блок ошибок валидации -->
    <div id="validation-errors" class="hidden mb-4 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-4 py-3 rounded-xl shadow-lg" role="alert">
        <ul id="error-list" class="list-disc list-inside space-y-1 text-sm font-semibold"></ul>
    </div>

    <form method="POST" action="{{ route('login') }}" id="login-form" novalidate>
        @csrf

        <!-- Login -->
        <div>
            <x-input-label for="login" :value="__('Логин')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-rose-600 hover:text-rose-800 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500" href="{{ route('register', request()->has('redirect') ? ['redirect' => request()->get('redirect')] : []) }}">
                {{ __('Нет аккаунта? Зарегистрироваться') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Войти') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            const loginInput = document.getElementById('login');
            const passwordInput = document.getElementById('password');
            const validationErrors = document.getElementById('validation-errors');
            const errorList = document.getElementById('error-list');

            // Функция отображения ошибок
            function showErrors(errors) {
                errorList.innerHTML = '';
                errors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
                validationErrors.classList.remove('hidden');
                validationErrors.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Функция скрытия ошибок
            function hideErrors() {
                validationErrors.classList.add('hidden');
                errorList.innerHTML = '';
            }

            // Обработка отправки формы
            form.addEventListener('submit', function(e) {
                const errors = [];
                
                // Проверка логина
                if (!loginInput.value.trim()) {
                    errors.push('Поле "Логин" обязательно для заполнения');
                    loginInput.classList.add('border-red-500');
                } else {
                    loginInput.classList.remove('border-red-500');
                }

                // Проверка пароля
                if (!passwordInput.value) {
                    errors.push('Поле "Пароль" обязательно для заполнения');
                    passwordInput.classList.add('border-red-500');
                } else {
                    passwordInput.classList.remove('border-red-500');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    showErrors(errors);
                    return false;
                }
                
                hideErrors();
            });

            // Очистка ошибок при вводе
            loginInput.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                hideErrors();
            });

            passwordInput.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                hideErrors();
            });
        });
    </script>
</x-guest-layout>
