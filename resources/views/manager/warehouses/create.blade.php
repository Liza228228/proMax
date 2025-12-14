<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Создание нового склада') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('manager.warehouses.store') }}" id="warehouse-form" novalidate>
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Название склада')" />
                            <input id="name" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="name" value="{{ old('name') }}" autofocus />
                            <div id="name-error" class="mt-2 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- City -->
                        <div class="mt-4">
                            <x-input-label for="city" :value="__('Город')" />
                            <input id="city" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="city" value="{{ old('city') }}" />
                            <div id="city-error" class="mt-2 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- Street -->
                        <div class="mt-4">
                            <x-input-label for="street" :value="__('Улица')" />
                            <input id="street" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="street" value="{{ old('street') }}" />
                            <div id="street-error" class="mt-2 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- House -->
                        <div class="mt-4">
                            <x-input-label for="house" :value="__('Дом')" />
                            <input id="house" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="house" value="{{ old('house') }}" />
                            <div id="house-error" class="mt-2 text-sm text-red-600 hidden"></div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('manager.warehouses.index') }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm mr-4">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                {{ __('Создать склад') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('warehouse-form');
            const nameInput = document.getElementById('name');
            const cityInput = document.getElementById('city');
            const streetInput = document.getElementById('street');
            const houseInput = document.getElementById('house');
            const nameError = document.getElementById('name-error');
            const cityError = document.getElementById('city-error');
            const streetError = document.getElementById('street-error');
            const houseError = document.getElementById('house-error');
            let checkTimeout;

            // Функция для отображения ошибки
            function showError(input, errorDiv, message) {
                errorDiv.textContent = message;
                errorDiv.classList.remove('hidden');
                input.classList.remove('border-rose-300');
                input.classList.add('border-red-500');
            }

            // Функция для скрытия ошибки
            function hideError(input, errorDiv) {
                errorDiv.classList.add('hidden');
                errorDiv.textContent = '';
                input.classList.remove('border-red-500');
                input.classList.add('border-rose-300');
            }

            // Валидация поля
            function validateField(input, errorDiv, fieldName) {
                const value = input.value.trim();
                hideError(input, errorDiv);
                
                if (!value) {
                    showError(input, errorDiv, `Поле "${fieldName}" обязательно для заполнения.`);
                    return false;
                }
                
                return true;
            }

            // Проверка уникальности названия склада и адреса при вводе
            function checkNameAndAddress() {
                const name = nameInput.value.trim();
                const city = cityInput.value.trim();
                const street = streetInput.value.trim();
                const house = houseInput.value.trim();
                
                clearTimeout(checkTimeout);
                
                hideError(nameInput, nameError);
                hideError(cityInput, cityError);
                hideError(streetInput, streetError);
                hideError(houseInput, houseError);

                if (name.length === 0 || city.length === 0) {
                    return;
                }

                checkTimeout = setTimeout(function() {
                    // Проверка названия в рамках города
                    fetch('{{ route("manager.warehouses.checkName") }}?name=' + encodeURIComponent(name) + '&city=' + encodeURIComponent(city), {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            showError(nameInput, nameError, 'Склад с таким названием уже существует в городе "' + city + '".');
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при проверке названия:', error);
                    });

                    // Проверка адреса (город + улица + дом)
                    if (city && street && house) {
                        fetch('{{ route("manager.warehouses.checkAddress") }}?city=' + encodeURIComponent(city) + '&street=' + encodeURIComponent(street) + '&house=' + encodeURIComponent(house), {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                showError(cityInput, cityError, 'Склад с таким адресом уже существует.');
                                showError(streetInput, streetError, '');
                                showError(houseInput, houseError, '');
                            }
                        })
                        .catch(error => {
                            console.error('Ошибка при проверке адреса:', error);
                        });
                    }
                }, 500);
            }

            nameInput.addEventListener('input', checkNameAndAddress);
            cityInput.addEventListener('input', checkNameAndAddress);
            streetInput.addEventListener('input', checkNameAndAddress);
            houseInput.addEventListener('input', checkNameAndAddress);

            // Валидация при вводе для всех полей
            cityInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    hideError(cityInput, cityError);
                }
            });

            streetInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    hideError(streetInput, streetError);
                }
            });

            houseInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    hideError(houseInput, houseError);
                }
            });

            // Валидация перед отправкой формы
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;

                // Валидация всех полей
                if (!validateField(nameInput, nameError, 'Название склада')) {
                    isValid = false;
                }

                if (!validateField(cityInput, cityError, 'Город')) {
                    isValid = false;
                }

                if (!validateField(streetInput, streetError, 'Улица')) {
                    isValid = false;
                }

                if (!validateField(houseInput, houseError, 'Дом')) {
                    isValid = false;
                }

                // Проверка уникальности названия и адреса перед отправкой
                if (isValid && nameInput.value.trim() && cityInput.value.trim()) {
                    const name = nameInput.value.trim();
                    const city = cityInput.value.trim();
                    const street = streetInput.value.trim();
                    const house = houseInput.value.trim();

                    // Проверка названия в рамках города
                    Promise.all([
                        fetch('{{ route("manager.warehouses.checkName") }}?name=' + encodeURIComponent(name) + '&city=' + encodeURIComponent(city)),
                        fetch('{{ route("manager.warehouses.checkAddress") }}?city=' + encodeURIComponent(city) + '&street=' + encodeURIComponent(street) + '&house=' + encodeURIComponent(house))
                    ])
                    .then(responses => Promise.all(responses.map(r => r.json())))
                    .then(([nameData, addressData]) => {
                        if (nameData.exists) {
                            showError(nameInput, nameError, 'Склад с таким названием уже существует в городе "' + city + '".');
                            isValid = false;
                        }
                        if (addressData.exists) {
                            showError(cityInput, cityError, 'Склад с таким адресом уже существует.');
                            showError(streetInput, streetError, '');
                            showError(houseInput, houseError, '');
                            isValid = false;
                        }

                        if (isValid) {
                            form.submit();
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при проверке:', error);
                        if (isValid) {
                            form.submit();
                        }
                    });
                } else if (isValid) {
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>










