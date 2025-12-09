<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Редактирование пользователя') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Фамилия')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $user->last_name)" required autofocus />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- First Name -->
                        <div class="mt-4">
                            <x-input-label for="first_name" :value="__('Имя')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $user->first_name)" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Телефон')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->formatted_phone)" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Login -->
                        <div class="mt-4">
                            <x-input-label for="login" :value="__('Логин')" />
                            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login', $user->login)" required />
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
                                $isCurrentUser = $currentUser && $currentUser->id == $user->id;
                                $canChangeRole = !$isAdmin && !($isCurrentUser && $currentUser->role == 2);
                            @endphp
                            <select id="role" name="role" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all" required {{ !$canChangeRole ? 'disabled' : '' }}>
                                <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Пользователь</option>
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
                        var phoneInput = document.getElementById('phone');
                        var phoneMask = IMask(phoneInput, {
                            mask: '+{7} (000) 000-00-00'
                        });

                        // Показываем поле подтверждения пароля, если введен пароль
                        document.getElementById('password').addEventListener('input', function() {
                            var passwordConfirmationWrapper = document.getElementById('password_confirmation_wrapper');
                            if (this.value.length > 0) {
                                passwordConfirmationWrapper.style.display = 'block';
                                document.getElementById('password_confirmation').required = true;
                            } else {
                                passwordConfirmationWrapper.style.display = 'none';
                                document.getElementById('password_confirmation').required = false;
                                document.getElementById('password_confirmation').value = '';
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

