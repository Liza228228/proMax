<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Персональные данные
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Обновите информацию вашего профиля
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" id="profile-form">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="last_name" value="Фамилия" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="first_name" value="Имя" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="phone" value="Телефон" />
            <x-text-input 
                id="phone" 
                name="phone" 
                type="tel" 
                class="mt-1 block w-full" 
                :value="old('phone', $user->phone)" 
                required 
                autocomplete="tel" 
                placeholder="+7 (___) ___-__-__"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="login" value="Логин" />
            <x-text-input id="login" name="login" type="text" class="mt-1 block w-full" :value="old('login', $user->login)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('login')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Сохранить</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >Сохранено.</p>
            @endif
        </div>
    </form>

    <script>
        // Маска для телефона
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');
            const profileForm = document.getElementById('profile-form');
            
            if (phoneInput) {
                // Функция форматирования телефона
                function formatPhone(value) {
                    // Если значение уже в правильном формате, возвращаем его
                    if (/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/.test(value)) {
                        return value;
                    }
                    
                    value = value.replace(/\D/g, '');
                    
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
                        return formattedValue;
                    } else if (value.length > 0) {
                        return '+7 (' + value.substring(0, 3) + ') ' + value.substring(3, 6) + '-' + value.substring(6, 8) + '-' + value.substring(8, 10);
                    }
                    return value;
                }
                
                // Применить маску к существующему значению при загрузке
                if (phoneInput.value) {
                    phoneInput.value = formatPhone(phoneInput.value);
                }
                
                // Блокируем ввод букв и других нецифровых символов
                phoneInput.addEventListener('keydown', function(e) {
                    // Разрешаем специальные клавиши
                    const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Home', 'End', 'Enter'];
                    if (allowedKeys.includes(e.key)) {
                        return;
                    }
                    
                    // Разрешаем Ctrl/Cmd комбинации (копирование, вставка и т.д.)
                    if (e.ctrlKey || e.metaKey) {
                        return;
                    }
                    
                    // Блокируем все остальные символы, кроме цифр
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Применить маску при вводе
                phoneInput.addEventListener('input', function(e) {
                    e.target.value = formatPhone(e.target.value);
                });
                
                // Обрабатываем вставку текста - очищаем от букв и форматируем
                phoneInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const digitsOnly = pastedText.replace(/\D/g, '');
                    if (digitsOnly) {
                        e.target.value = formatPhone(digitsOnly);
                    }
                });
                
                // Номер сохраняется в формате +7 (000) 000-00-00, не удаляем форматирование при отправке
            }
        });
    </script>
</section>
