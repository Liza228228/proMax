<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Редактирование пользователя') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Блок ошибок валидации -->
            <div id="validation-errors" class="hidden mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                <ul id="error-list" class="list-disc list-inside space-y-1 font-semibold"></ul>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="user-edit-form" novalidate>
                        @csrf
                        @method('PATCH')

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Фамилия')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $user->last_name)" autofocus />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- First Name -->
                        <div class="mt-4">
                            <x-input-label for="first_name" :value="__('Имя')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $user->first_name)" />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Телефон')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->formatted_phone)" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Login -->
                        <div class="mt-4">
                            <x-input-label for="login" :value="__('Логин')" />
                            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login', $user->login)" />
                            <x-input-error :messages="$errors->get('login')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Пароль (оставьте пустым, чтобы не изменять)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mt-4" id="password_confirmation_wrapper" style="display: none;">
                            <x-input-label for="password_confirmation" :value="__('Подтверждение пароля')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Роль')" />
                            @php
                                $isAdmin = $user->role == 2;
                                $isManager = $user->role == 3;
                                $isCurrentUser = $currentUser && $currentUser->id == $user->id;
                                $canChangeRole = !$isAdmin && !($isCurrentUser && $currentUser->role == 2);
                            @endphp
                            <select id="role" name="role" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all" {{ !$canChangeRole ? 'disabled' : '' }}>
                                @if(!$isManager)
                                    <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Пользователь</option>
                                @endif
                                <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Администратор</option>
                                <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Менеджер</option>
                            </select>
                            @if(!$canChangeRole)
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <p class="mt-1 text-xs text-gray-500">Роль администратора нельзя изменить</p>
                            @endif
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm mr-4">
                                {{ __('Отмена') }}
                            </a>
                            <x-primary-button>
                                {{ __('Сохранить изменения') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <script src="https://unpkg.com/imask"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('user-edit-form');
                            const lastNameInput = document.getElementById('last_name');
                            const firstNameInput = document.getElementById('first_name');
                            const phoneInput = document.getElementById('phone');
                            const loginInput = document.getElementById('login');
                            const passwordInput = document.getElementById('password');
                            const passwordConfirmationInput = document.getElementById('password_confirmation');
                            const passwordConfirmationWrapper = document.getElementById('password_confirmation_wrapper');
                            const roleSelect = document.getElementById('role');
                            const validationErrors = document.getElementById('validation-errors');
                            const errorList = document.getElementById('error-list');

                            // Маска для телефона
                            var phoneMask = IMask(phoneInput, {
                                mask: '+{7} (000) 000-00-00'
                            });

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

                            // Показываем поле подтверждения пароля, если введен пароль
                            passwordInput.addEventListener('input', function() {
                                if (this.value.length > 0) {
                                    passwordConfirmationWrapper.style.display = 'block';
                                } else {
                                    passwordConfirmationWrapper.style.display = 'none';
                                    passwordConfirmationInput.value = '';
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
                                if (!phoneInput.value.trim()) {
                                    errors.push('Поле "Телефон" обязательно для заполнения');
                                    phoneInput.classList.add('border-red-500');
                                } else if (!phonePattern.test(phoneInput.value)) {
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

                                // Проверка пароля (если указан)
                                if (passwordInput.value) {
                                    if (passwordInput.value.length < 8) {
                                        errors.push('Пароль должен содержать минимум 8 символов');
                                        passwordInput.classList.add('border-red-500');
                                    } else {
                                        passwordInput.classList.remove('border-red-500');
                                    }

                                    if (!passwordConfirmationInput.value) {
                                        errors.push('Подтвердите пароль');
                                        passwordConfirmationInput.classList.add('border-red-500');
                                    } else if (passwordInput.value !== passwordConfirmationInput.value) {
                                        errors.push('Пароли не совпадают');
                                        passwordInput.classList.add('border-red-500');
                                        passwordConfirmationInput.classList.add('border-red-500');
                                    } else {
                                        passwordConfirmationInput.classList.remove('border-red-500');
                                    }
                                }

                                // Проверка роли
                                if (!roleSelect.value) {
                                    errors.push('Выберите роль');
                                    roleSelect.classList.add('border-red-500');
                                } else {
                                    roleSelect.classList.remove('border-red-500');
                                }

                                if (errors.length > 0) {
                                    e.preventDefault();
                                    showErrors(errors);
                                    return false;
                                }
                                
                                hideErrors();
                            });

                            // Проверка роли при изменении
                            roleSelect.addEventListener('change', function() {
                                this.classList.remove('border-red-500');
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

