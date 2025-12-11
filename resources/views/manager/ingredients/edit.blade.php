<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Редактирование ингредиента') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('manager.ingredients.update', $ingredient) }}" id="ingredientForm" novalidate>
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" :value="__('Название')" />
                            <input
                                id="name"
                                class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                                type="text"
                                name="name"
                                value="{{ old('name', $ingredient->name) }}"
                                autofocus
                            />
                            <div id="name-error" class="mt-2 text-sm text-red-600 hidden"></div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Описание')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                            >{{ old('description', $ingredient->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="unit_type_id" :value="__('Тип единицы измерения')" />
                            <select
                                id="unit_type_id"
                                name="unit_type_id"
                                class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                                required
                            >
                                <option value="">Выберите тип единицы измерения</option>
                                @foreach($unitTypes as $unitType)
                                    <option value="{{ $unitType->id }}" {{ old('unit_type_id', $ingredient->unit_type_id) == $unitType->id ? 'selected' : '' }}>
                                        {{ $unitType->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('unit_type_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="expiration_days" :value="__('Срок годности (дней)')" />
                            <input
                                id="expiration_days"
                                class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                                type="number"
                                name="expiration_days"
                                value="{{ old('expiration_days', $ingredient->expiration_date ?? 7) }}"
                                min="1"
                                step="1"
                                required
                            />
                            <p class="mt-1 text-sm text-gray-500">Укажите срок годности в количестве дней</p>
                            <x-input-error :messages="$errors->get('expiration_days')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('manager.ingredients.index') }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm mr-4">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                {{ __('Сохранить изменения') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ingredientForm');
            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('name-error');
            const ingredientId = {{ $ingredient->id }};
            let checkTimeout;

            // Проверка уникальности названия при вводе
            nameInput.addEventListener('input', function() {
                const name = this.value.trim();
                
                // Очищаем предыдущий таймаут
                clearTimeout(checkTimeout);
                
                // Очищаем ошибку
                nameError.classList.add('hidden');
                nameError.textContent = '';
                nameInput.classList.remove('border-red-500');
                nameInput.classList.add('border-rose-300');

                if (name.length === 0) {
                    return;
                }

                // Проверяем через 500ms после остановки ввода
                checkTimeout = setTimeout(function() {
                    fetch('{{ route("manager.ingredients.checkName") }}?name=' + encodeURIComponent(name) + '&ingredient_id=' + ingredientId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            nameError.textContent = 'Ингредиент с таким названием уже существует.';
                            nameError.classList.remove('hidden');
                            nameInput.classList.remove('border-rose-300');
                            nameInput.classList.add('border-red-500');
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при проверке названия:', error);
                    });
                }, 500);
            });

            // Валидация перед отправкой формы
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const name = nameInput.value.trim();
                let isValid = true;

                // Очищаем предыдущие ошибки
                nameError.classList.add('hidden');
                nameError.textContent = '';
                nameInput.classList.remove('border-red-500');
                nameInput.classList.add('border-rose-300');

                // Проверка на пустое поле
                if (!name) {
                    nameError.textContent = 'Поле "Название" обязательно для заполнения.';
                    nameError.classList.remove('hidden');
                    nameInput.classList.remove('border-rose-300');
                    nameInput.classList.add('border-red-500');
                    isValid = false;
                }

                // Проверка уникальности перед отправкой
                if (isValid && name) {
                    fetch('{{ route("manager.ingredients.checkName") }}?name=' + encodeURIComponent(name) + '&ingredient_id=' + ingredientId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            nameError.textContent = 'Ингредиент с таким названием уже существует.';
                            nameError.classList.remove('hidden');
                            nameInput.classList.remove('border-rose-300');
                            nameInput.classList.add('border-red-500');
                            isValid = false;
                        }

                        if (isValid) {
                            form.submit();
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при проверке названия:', error);
                        // Если ошибка при проверке, все равно отправляем форму (серверная валидация сработает)
                        form.submit();
                    });
                } else {
                    if (isValid) {
                        form.submit();
                    }
                }
            });
        });
    </script>

</x-app-layout>


