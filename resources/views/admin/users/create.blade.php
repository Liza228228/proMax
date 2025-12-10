<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Создание нового пользователя') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="user-create-form" novalidate>
                        @csrf

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Фамилия')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" autofocus />
                            <div id="last_name_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- First Name -->
                        <div class="mt-4">
                            <x-input-label for="first_name" :value="__('Имя')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" />
                            <div id="first_name_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Телефон')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                            <div id="phone_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Login -->
                        <div class="mt-4">
                            <x-input-label for="login" :value="__('Логин')" />
                            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" />
                            <div id="login_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('login')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Пароль')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <div id="password_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Подтверждение пароля')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <div id="password_confirmation_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Роль')" />
                            <select id="role" name="role" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all">
                                <option value="">Выберите роль</option>
                                <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Администратор</option>
                                <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Менеджер</option>
                            </select>
                            <div id="role_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm mr-4">
                                {{ __('Отмена') }}
                            </a>
                            <x-primary-button>
                                {{ __('Создать пользователя') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <script src="https://unpkg.com/imask"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('user-create-form');
                            const lastNameInput = document.getElementById('last_name');
                            const firstNameInput = document.getElementById('first_name');
                            const phoneInput = document.getElementById('phone');
                            const loginInput = document.getElementById('login');
                            const passwordInput = document.getElementById('password');
                            const passwordConfirmationInput = document.getElementById('password_confirmation');
                            const roleSelect = document.getElementById('role');
                            
                            // Элементы для отображения ошибок под каждым полем
                            const lastNameError = document.getElementById('last_name_error');
                            const firstNameError = document.getElementById('first_name_error');
                            const phoneError = document.getElementById('phone_error');
                            const loginError = document.getElementById('login_error');
                            const passwordError = document.getElementById('password_error');
                            const passwordConfirmationError = document.getElementById('password_confirmation_error');
                            const roleError = document.getElementById('role_error');

                            // Маска для телефона
                            var phoneMask = IMask(phoneInput, {
                                mask: '+{7} (000) 000-00-00'
                            });

                            // Функция показа ошибки под полем
                            function showFieldError(errorElement, message) {
                                errorElement.textContent = message;
                                errorElement.classList.remove('hidden');
                            }

                            // Функция скрытия ошибки под полем
                            function hideFieldError(errorElement) {
                                errorElement.classList.add('hidden');
                                errorElement.textContent = '';
                            }

                            // Функция скрытия всех ошибок
                            function hideAllErrors() {
                                hideFieldError(lastNameError);
                                hideFieldError(firstNameError);
                                hideFieldError(phoneError);
                                hideFieldError(loginError);
                                hideFieldError(passwordError);
                                hideFieldError(passwordConfirmationError);
                                hideFieldError(roleError);
                            }

                            // Обработка отправки формы
                            form.addEventListener('submit', function(e) {
                                let hasErrors = false;
                                hideAllErrors();
                                
                                // Проверка фамилии
                                if (!lastNameInput.value.trim()) {
                                    showFieldError(lastNameError, 'Поле "Фамилия" обязательно для заполнения');
                                    lastNameInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (lastNameInput.value.trim().length > 100) {
                                    showFieldError(lastNameError, 'Поле "Фамилия" не должно превышать 100 символов');
                                    lastNameInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(lastNameError);
                                    lastNameInput.classList.remove('border-red-500');
                                }

                                // Проверка имени
                                if (!firstNameInput.value.trim()) {
                                    showFieldError(firstNameError, 'Поле "Имя" обязательно для заполнения');
                                    firstNameInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (firstNameInput.value.trim().length > 100) {
                                    showFieldError(firstNameError, 'Поле "Имя" не должно превышать 100 символов');
                                    firstNameInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(firstNameError);
                                    firstNameInput.classList.remove('border-red-500');
                                }

                                // Проверка телефона
                                const phonePattern = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
                                if (!phoneInput.value.trim()) {
                                    showFieldError(phoneError, 'Поле "Телефон" обязательно для заполнения');
                                    phoneInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (!phonePattern.test(phoneInput.value)) {
                                    showFieldError(phoneError, 'Телефон должен быть в формате +7 (XXX) XXX-XX-XX');
                                    phoneInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(phoneError);
                                    phoneInput.classList.remove('border-red-500');
                                }

                                // Проверка логина
                                if (!loginInput.value.trim()) {
                                    showFieldError(loginError, 'Поле "Логин" обязательно для заполнения');
                                    loginInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (loginInput.value.trim().length > 20) {
                                    showFieldError(loginError, 'Поле "Логин" не должно превышать 20 символов');
                                    loginInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(loginError);
                                    loginInput.classList.remove('border-red-500');
                                }

                                // Проверка пароля
                                if (!passwordInput.value) {
                                    showFieldError(passwordError, 'Поле "Пароль" обязательно для заполнения');
                                    passwordInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (passwordInput.value.length < 8) {
                                    showFieldError(passwordError, 'Пароль должен содержать минимум 8 символов');
                                    passwordInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(passwordError);
                                    passwordInput.classList.remove('border-red-500');
                                }

                                // Проверка подтверждения пароля
                                if (!passwordConfirmationInput.value) {
                                    showFieldError(passwordConfirmationError, 'Поле "Подтверждение пароля" обязательно для заполнения');
                                    passwordConfirmationInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (passwordInput.value !== passwordConfirmationInput.value) {
                                    showFieldError(passwordConfirmationError, 'Пароли не совпадают');
                                    passwordInput.classList.add('border-red-500');
                                    passwordConfirmationInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(passwordConfirmationError);
                                    passwordConfirmationInput.classList.remove('border-red-500');
                                }

                                // Проверка роли
                                if (!roleSelect.value) {
                                    showFieldError(roleError, 'Выберите роль');
                                    roleSelect.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(roleError);
                                    roleSelect.classList.remove('border-red-500');
                                }

                                if (hasErrors) {
                                    e.preventDefault();
                                    // Прокрутка к первой ошибке
                                    const firstError = document.querySelector('.border-red-500');
                                    if (firstError) {
                                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    }
                                    return false;
                                }
                            });

                            // Очистка ошибок при вводе
                            lastNameInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(lastNameError);
                            });
                            
                            firstNameInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(firstNameError);
                            });
                            
                            phoneInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(phoneError);
                            });
                            
                            loginInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(loginError);
                            });
                            
                            passwordInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(passwordError);
                                // Если пароль изменился, проверяем подтверждение
                                if (passwordConfirmationInput.value && passwordInput.value !== passwordConfirmationInput.value) {
                                    showFieldError(passwordConfirmationError, 'Пароли не совпадают');
                                    passwordConfirmationInput.classList.add('border-red-500');
                                } else if (passwordInput.value === passwordConfirmationInput.value && passwordConfirmationInput.value) {
                                    hideFieldError(passwordConfirmationError);
                                    passwordConfirmationInput.classList.remove('border-red-500');
                                }
                            });
                            
                            passwordConfirmationInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                if (passwordInput.value && passwordInput.value === this.value) {
                                    hideFieldError(passwordConfirmationError);
                                } else if (passwordInput.value) {
                                    showFieldError(passwordConfirmationError, 'Пароли не совпадают');
                                } else {
                                    hideFieldError(passwordConfirmationError);
                                }
                            });
                            
                            roleSelect.addEventListener('change', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(roleError);
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>










