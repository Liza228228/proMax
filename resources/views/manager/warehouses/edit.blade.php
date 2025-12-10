<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Редактирование склада') }}
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
                    <form method="POST" action="{{ route('manager.warehouses.update', $warehouse) }}" id="warehouse-edit-form" novalidate>
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Название склада')" />
                            <input id="name" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="name" value="{{ old('name', $warehouse->name) }}" autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- City -->
                        <div class="mt-4">
                            <x-input-label for="city" :value="__('Город')" />
                            <input id="city" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="city" value="{{ old('city', $warehouse->city) }}" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <!-- Street -->
                        <div class="mt-4">
                            <x-input-label for="street" :value="__('Улица')" />
                            <input id="street" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="street" value="{{ old('street', $warehouse->street) }}" />
                            <x-input-error :messages="$errors->get('street')" class="mt-2" />
                        </div>

                        <!-- House -->
                        <div class="mt-4">
                            <x-input-label for="house" :value="__('Дом')" />
                            <input id="house" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="house" value="{{ old('house', $warehouse->house) }}" />
                            <x-input-error :messages="$errors->get('house')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('manager.warehouses.index') }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm mr-4">
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
            const form = document.getElementById('warehouse-edit-form');
            const nameInput = document.getElementById('name');
            const cityInput = document.getElementById('city');
            const streetInput = document.getElementById('street');
            const houseInput = document.getElementById('house');
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
                
                // Проверка названия
                if (!nameInput.value.trim()) {
                    errors.push('Поле "Название склада" обязательно для заполнения');
                    nameInput.classList.add('border-red-500');
                } else if (nameInput.value.trim().length > 100) {
                    errors.push('Поле "Название склада" не должно превышать 100 символов');
                    nameInput.classList.add('border-red-500');
                } else {
                    nameInput.classList.remove('border-red-500');
                }

                // Проверка города
                if (!cityInput.value.trim()) {
                    errors.push('Поле "Город" обязательно для заполнения');
                    cityInput.classList.add('border-red-500');
                } else if (cityInput.value.trim().length > 100) {
                    errors.push('Поле "Город" не должно превышать 100 символов');
                    cityInput.classList.add('border-red-500');
                } else {
                    cityInput.classList.remove('border-red-500');
                }

                // Проверка улицы
                if (!streetInput.value.trim()) {
                    errors.push('Поле "Улица" обязательно для заполнения');
                    streetInput.classList.add('border-red-500');
                } else if (streetInput.value.trim().length > 100) {
                    errors.push('Поле "Улица" не должно превышать 100 символов');
                    streetInput.classList.add('border-red-500');
                } else {
                    streetInput.classList.remove('border-red-500');
                }

                // Проверка дома
                if (!houseInput.value.trim()) {
                    errors.push('Поле "Дом" обязательно для заполнения');
                    houseInput.classList.add('border-red-500');
                } else if (houseInput.value.trim().length > 100) {
                    errors.push('Поле "Дом" не должно превышать 100 символов');
                    houseInput.classList.add('border-red-500');
                } else {
                    houseInput.classList.remove('border-red-500');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    showErrors(errors);
                    return false;
                }
                
                hideErrors();
            });

            // Очистка ошибок при вводе
            [nameInput, cityInput, streetInput, houseInput].forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                    hideErrors();
                });
            });
        });
    </script>
</x-app-layout>










