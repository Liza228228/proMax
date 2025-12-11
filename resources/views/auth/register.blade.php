<x-guest-layout>
    <!-- Блок ошибок валидации -->
    <div id="validation-errors" class="hidden mb-4 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-4 py-3 rounded-xl shadow-lg" role="alert">
        <ul id="error-list" class="list-disc list-inside space-y-1 text-sm font-semibold"></ul>
    </div>

    <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
        @csrf

        <!-- Last Name -->
        <div>
            <x-input-label for="last_name" :value="__('Фамилия')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" autofocus autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('Имя')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Телефон')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="+7 (___) ___-__-__" style="font-family: monospace;" />
            <div id="phone-error" class="hidden mt-2 text-sm text-red-600"></div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Login -->
        <div class="mt-4">
            <x-input-label for="login" :value="__('Логин')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-rose-600 hover:text-rose-800 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500" href="{{ route('login') }}">
                {{ __('Уже зарегистрированы?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Зарегистрироваться') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('register-form');
            const lastNameInput = document.getElementById('last_name');
            const firstNameInput = document.getElementById('first_name');
            const phoneInput = document.getElementById('phone');
            const loginInput = document.getElementById('login');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
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

            // Маска для телефона
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.startsWith('8')) {
                    value = '7' + value.substring(1);
                }
                
                if (value.startsWith('7')) {
                    let formattedValue = '+7';
                    if (value.length > 1) {
                        formattedValue += ' (' + value.substring(1, 4);
                    }
                    if (value.length >= 4) {
                        formattedValue += ') ' + value.substring(4, 7);
                    }
                    if (value.length >= 7) {
                        formattedValue += '-' + value.substring(7, 9);
                    }
                    if (value.length >= 9) {
                        formattedValue += '-' + value.substring(9, 11);
                    }
                    e.target.value = formattedValue;
                } else if (value.length > 0) {
                    e.target.value = '+7 (' + value.substring(0, 3) + ') ' + value.substring(3, 6) + '-' + value.substring(6, 8) + '-' + value.substring(8, 10);
                }
            });
            
            phoneInput.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && e.target.value.length <= 4) {
                    e.target.value = '';
                }
            });

            // Обработка отправки формы
            form.addEventListener('submit', function(e) {
                const errors = [];
                
                // Проверка фамилии
                if (!lastNameInput.value.trim()) {
                    errors.push('Поле "Фамилия" обязательно для заполнения');
                    lastNameInput.classList.add('border-red-500');
                } else if (lastNameInput.value.trim().length > 100) {
                    errors.push('Поле "Фамилия" не должно превышать 100 символов');
                    lastNameInput.classList.add('border-red-500');
                } else {
                    lastNameInput.classList.remove('border-red-500');
                }

                // Проверка имени
                if (!firstNameInput.value.trim()) {
                    errors.push('Поле "Имя" обязательно для заполнения');
                    firstNameInput.classList.add('border-red-500');
                } else if (firstNameInput.value.trim().length > 100) {
                    errors.push('Поле "Имя" не должно превышать 100 символов');
                    firstNameInput.classList.add('border-red-500');
                } else {
                    firstNameInput.classList.remove('border-red-500');
                }

                // Проверка телефона
                const phonePattern = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
                const phone = phoneInput.value.trim();
                if (!phone) {
                    errors.push('Поле "Телефон" обязательно для заполнения');
                    phoneInput.classList.add('border-red-500');
                } else if (!phonePattern.test(phone)) {
                    errors.push('Телефон должен быть в формате +7 (XXX) XXX-XX-XX');
                    phoneInput.classList.add('border-red-500');
                } else {
                    phoneInput.classList.remove('border-red-500');
                }

                // Проверка логина
                if (!loginInput.value.trim()) {
                    errors.push('Поле "Логин" обязательно для заполнения');
                    loginInput.classList.add('border-red-500');
                } else if (loginInput.value.trim().length > 20) {
                    errors.push('Поле "Логин" не должно превышать 20 символов');
                    loginInput.classList.add('border-red-500');
                } else {
                    loginInput.classList.remove('border-red-500');
                }

                // Проверка пароля
                if (!passwordInput.value) {
                    errors.push('Поле "Пароль" обязательно для заполнения');
                    passwordInput.classList.add('border-red-500');
                } else if (passwordInput.value.length < 8) {
                    errors.push('Пароль должен содержать минимум 8 символов');
                    passwordInput.classList.add('border-red-500');
                } else {
                    passwordInput.classList.remove('border-red-500');
                }

                // Проверка подтверждения пароля
                if (!passwordConfirmationInput.value) {
                    errors.push('Поле "Подтвердите пароль" обязательно для заполнения');
                    passwordConfirmationInput.classList.add('border-red-500');
                } else if (passwordInput.value !== passwordConfirmationInput.value) {
                    errors.push('Пароли не совпадают');
                    passwordInput.classList.add('border-red-500');
                    passwordConfirmationInput.classList.add('border-red-500');
                } else {
                    passwordConfirmationInput.classList.remove('border-red-500');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    showErrors(errors);
                    return false;
                }
                
                hideErrors();
            });

            // Очистка ошибок при вводе
            [lastNameInput, firstNameInput, phoneInput, loginInput, passwordInput, passwordConfirmationInput].forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    hideErrors();
                });
            });
        });
    </script>
</x-guest-layout>
