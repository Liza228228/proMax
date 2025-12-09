<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Last Name -->
        <div>
            <x-input-label for="last_name" :value="__('Фамилия')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('Имя')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Телефон')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" placeholder="+7 (___) ___-__-__" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Login -->
        <div class="mt-4">
            <x-input-label for="login" :value="__('Логин')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

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
        // Маска для телефона
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            
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
        });
    </script>
</x-guest-layout>
