<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                {{ __('Перемещение товаров со склада: ') . $warehouse->name }}
            </h2>
            <a href="{{ route('manager.warehouses.show', $warehouse) }}" class="text-rose-600 hover:text-rose-800 font-semibold">
                ← Назад к складу
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Блок ошибок валидации -->
            <div id="validation-errors" class="hidden mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                <ul id="error-list" class="list-disc list-inside space-y-1 font-semibold"></ul>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">

                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-rose-700 mb-6">Форма перемещения ингредиентов</h3>
                    <form method="POST" action="{{ route('manager.warehouses.transfer.store', $warehouse) }}" id="transferForm" novalidate>
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Склад-источник (только для информации) -->
                            <div>
                                <x-input-label for="from_warehouse" :value="__('Склад-источник')" />
                                <input 
                                    id="from_warehouse" 
                                    class="block mt-1 w-full rounded-xl border-2 border-rose-300 bg-rose-50 px-4 py-2" 
                                    type="text" 
                                    value="{{ $warehouse->name }}" 
                                    disabled 
                                />
                                <p class="mt-1 text-xs text-rose-600 font-medium">
                                    Текущий склад
                                </p>
                            </div>

                            <!-- Склад-получатель -->
                            <div>
                                <x-input-label for="to_warehouse_id" :value="__('Склад-получатель')" />
                                <select id="to_warehouse_id" 
                                        name="to_warehouse_id"
                                        class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Выберите склад-получатель</option>
                                    @foreach($warehouses as $w)
                                        <option value="{{ $w->id }}" {{ old('to_warehouse_id') == $w->id ? 'selected' : '' }}>
                                            {{ $w->name }} ({{ $w->city }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('to_warehouse_id')" class="mt-2" />
                            </div>

                            <!-- Ингредиент -->
                            <div>
                                <x-input-label for="ingredient_id" :value="__('Ингредиент')" />
                                <select id="ingredient_id" 
                                        name="ingredient_id"
                                        class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Выберите ингредиент</option>
                                    @foreach($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}" 
                                                data-unit-type-id="{{ $ingredient->unitType->id ?? '' }}">
                                            {{ $ingredient->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('ingredient_id')" class="mt-2" />
                            </div>

                            <!-- Срок годности -->
                            <div>
                                <x-input-label for="expiration_date" :value="__('Срок годности')" />
                                <select id="expiration_date" 
                                        name="expiration_date"
                                        class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Сначала выберите ингредиент</option>
                                </select>
                                <x-input-error :messages="$errors->get('expiration_date')" class="mt-2" />
                            </div>

                            <!-- Единица измерения -->
                            <div>
                                <x-input-label for="unit_id" :value="__('Единица измерения')" />
                                <select id="unit_id" 
                                        name="unit_id"
                                        class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Выберите единицу измерения</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" 
                                                data-unit-type-id="{{ $unit->unitType->id ?? '' }}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('unit_id')" class="mt-2" />
                            </div>

                            <!-- Количество -->
                            <div>
                                <x-input-label for="quantity" :value="__('Количество')" />
                                <input id="quantity" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="text" name="quantity" value="{{ old('quantity') }}" />
                                <div id="quantity-error" class="hidden mt-2 text-sm text-red-600"></div>
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <!-- Доступное количество -->
                            <div>
                                <x-input-label :value="__('Доступно на складе')" />
                                <div id="available-quantity" class="block mt-1 w-full px-4 py-2 text-lg font-bold text-rose-700 bg-rose-50 rounded-xl border-2 border-rose-300 text-center shadow-sm hidden flex items-center justify-center" style="min-height: 42px;">
                                    <span id="available-quantity-value">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('manager.warehouses.show', $warehouse) }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                {{ __('Переместить') }}
                            </button>
                        </div>
                    </form>

                    <script>
                        // Данные о партиях ингредиентов с их сроками годности (в базовых единицах)
                        const stockBatches = @json($stockBatches);
                        
                        // Данные о единицах измерения
                        const unitsData = @json($unitsData);
                        
                        // Фильтрация единиц измерения на основе выбранного ингредиента
                        document.addEventListener('DOMContentLoaded', function() {
                            const ingredientSelect = document.getElementById('ingredient_id');
                            const expirationDateSelect = document.getElementById('expiration_date');
                            const unitSelect = document.getElementById('unit_id');
                            const quantityInput = document.getElementById('quantity');
                            const availableQuantityDiv = document.getElementById('available-quantity');
                            const availableQuantityValue = document.getElementById('available-quantity-value');
                            
                            // Сохраняем все опции единиц измерения (клонируем их)
                            const allUnitOptions = Array.from(unitSelect.options).map(function(option) {
                                return {
                                    value: option.value,
                                    text: option.text,
                                    unitTypeId: option.getAttribute('data-unit-type-id') || ''
                                };
                            });
                            
                            // Функция для обновления доступного количества
                            function updateAvailableQuantity() {
                                const selectedIngredientId = ingredientSelect.value;
                                const selectedExpirationDate = expirationDateSelect.value;
                                const selectedUnitId = unitSelect.value;
                                
                                if (!selectedIngredientId || !selectedExpirationDate || !selectedUnitId) {
                                    availableQuantityDiv.classList.add('hidden');
                                    return;
                                }
                                
                                const batches = stockBatches[selectedIngredientId];
                                // Обрабатываем случай, когда expiration_date = null (пустая строка)
                                // Используем специальный ключ __null__ для партий без даты
                                const batchKey = selectedExpirationDate === '' ? '__null__' : selectedExpirationDate;
                                const batch = batches[batchKey];
                                
                                if (!batches || !batch) {
                                    availableQuantityDiv.classList.add('hidden');
                                    return;
                                }
                                
                                const baseQuantity = batch.quantity;
                                if (!baseQuantity) {
                                    availableQuantityDiv.classList.add('hidden');
                                    return;
                                }
                                
                                const unitData = unitsData[selectedUnitId];
                                if (!unitData) {
                                    availableQuantityDiv.classList.add('hidden');
                                    return;
                                }
                                
                                // Конвертируем из базовых единиц в выбранную единицу
                                const availableQuantity = baseQuantity / unitData.multiplier_to_base;
                                
                                availableQuantityValue.textContent = availableQuantity.toFixed(3);
                                availableQuantityDiv.classList.remove('hidden');
                            }
                            
                            ingredientSelect.addEventListener('change', function() {
                                const selectedIngredient = this.options[this.selectedIndex];
                                const ingredientUnitTypeId = selectedIngredient.getAttribute('data-unit-type-id');
                                const selectedIngredientId = this.value;
                                
                                // Очищаем список сроков годности и заполняем новыми
                                expirationDateSelect.innerHTML = '<option value="">Выберите срок годности</option>';
                                
                                if (selectedIngredientId && stockBatches[selectedIngredientId]) {
                                    const batches = stockBatches[selectedIngredientId];
                                    // Сортируем по дате (сначала ближайшие)
                                    const sortedDates = Object.keys(batches).sort(function(a, b) {
                                        // Обрабатываем специальный ключ для null
                                        const aIsNull = a === '__null__';
                                        const bIsNull = b === '__null__';
                                        if (aIsNull) return 1;
                                        if (bIsNull) return -1;
                                        return a.localeCompare(b);
                                    });
                                    
                                    sortedDates.forEach(function(dateKey) {
                                        const batch = batches[dateKey];
                                        const option = document.createElement('option');
                                        // Используем пустую строку для партий без даты (__null__), иначе используем expiration_date из batch
                                        option.value = (dateKey === '__null__') ? '' : batch.expiration_date;
                                        option.textContent = batch.expiration_date_display;
                                        expirationDateSelect.appendChild(option);
                                    });
                                }
                                
                                // Очищаем список единиц измерения
                                unitSelect.innerHTML = '<option value="">Выберите единицу измерения</option>';
                                
                                if (ingredientUnitTypeId) {
                                    // Фильтруем единицы измерения по типу ингредиента
                                    allUnitOptions.forEach(function(option) {
                                        if (option.value === '') return; // Пропускаем пустую опцию
                                        
                                        if (option.unitTypeId === ingredientUnitTypeId) {
                                            const newOption = document.createElement('option');
                                            newOption.value = option.value;
                                            newOption.textContent = option.text;
                                            newOption.setAttribute('data-unit-type-id', option.unitTypeId);
                                            unitSelect.appendChild(newOption);
                                        }
                                    });
                                } else {
                                    // Если тип не указан, показываем все единицы
                                    allUnitOptions.forEach(function(option) {
                                        if (option.value === '') return; // Пропускаем пустую опцию
                                        
                                        const newOption = document.createElement('option');
                                        newOption.value = option.value;
                                        newOption.textContent = option.text;
                                        newOption.setAttribute('data-unit-type-id', option.unitTypeId);
                                        unitSelect.appendChild(newOption);
                                    });
                                }
                                
                                // Сбрасываем выбранные значения
                                expirationDateSelect.value = '';
                                unitSelect.value = '';
                                quantityInput.value = '';
                                availableQuantityDiv.classList.add('hidden');
                            });
                            
                            expirationDateSelect.addEventListener('change', function() {
                                unitSelect.value = '';
                                quantityInput.value = '';
                                updateAvailableQuantity();
                            });
                            
                            unitSelect.addEventListener('change', function() {
                                updateAvailableQuantity();
                            });

                            // Кастомная валидация формы
                            const form = document.getElementById('transferForm');
                            const validationErrors = document.getElementById('validation-errors');
                            const errorList = document.getElementById('error-list');
                            const toWarehouseSelect = document.getElementById('to_warehouse_id');
                            const quantityError = document.getElementById('quantity-error');

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

                                // Проверка склада-получателя
                                if (!toWarehouseSelect.value) {
                                    errors.push('Выберите склад-получатель');
                                    toWarehouseSelect.classList.add('border-red-500');
                                } else {
                                    toWarehouseSelect.classList.remove('border-red-500');
                                }

                                // Проверка ингредиента
                                if (!ingredientSelect.value) {
                                    errors.push('Выберите ингредиент');
                                    ingredientSelect.classList.add('border-red-500');
                                } else {
                                    ingredientSelect.classList.remove('border-red-500');
                                }

                                // Проверка срока годности
                                if (!expirationDateSelect.value && ingredientSelect.value) {
                                    errors.push('Выберите срок годности');
                                    expirationDateSelect.classList.add('border-red-500');
                                } else {
                                    expirationDateSelect.classList.remove('border-red-500');
                                }

                                // Проверка единицы измерения
                                if (!unitSelect.value) {
                                    errors.push('Выберите единицу измерения');
                                    unitSelect.classList.add('border-red-500');
                                } else {
                                    unitSelect.classList.remove('border-red-500');
                                }

                                // Проверка количества
                                const quantityValue = parseFloat(quantityInput.value);
                                quantityError.classList.add('hidden');
                                quantityError.textContent = '';
                                
                                if (!quantityInput.value) {
                                    errors.push('Укажите количество');
                                    quantityInput.classList.add('border-red-500');
                                } else if (isNaN(quantityValue) || quantityValue <= 0) {
                                    errors.push('Количество должно быть положительным числом');
                                    quantityInput.classList.add('border-red-500');
                                } else {
                                    // Проверка, не превышает ли количество доступное на складе
                                    const selectedIngredientId = ingredientSelect.value;
                                    const selectedExpirationDate = expirationDateSelect.value;
                                    const selectedUnitId = unitSelect.value;
                                    
                                    if (selectedIngredientId && selectedExpirationDate && selectedUnitId) {
                                        const batches = stockBatches[selectedIngredientId];
                                        const batchKey = selectedExpirationDate === '' ? '__null__' : selectedExpirationDate;
                                        const batch = batches[batchKey];
                                        
                                        if (batches && batch) {
                                            const baseQuantity = batch.quantity;
                                            const unitData = unitsData[selectedUnitId];
                                            
                                            if (unitData) {
                                                const availableQuantity = baseQuantity / unitData.multiplier_to_base;
                                                
                                                if (quantityValue > availableQuantity) {
                                                    const errorMessage = `Недостаточно ингредиента на складе. Доступно: ${availableQuantity.toFixed(3)}`;
                                                    errors.push(errorMessage);
                                                    quantityError.textContent = errorMessage;
                                                    quantityError.classList.remove('hidden');
                                                    quantityInput.classList.add('border-red-500');
                                                } else {
                                                    quantityInput.classList.remove('border-red-500');
                                                }
                                            } else {
                                                quantityInput.classList.remove('border-red-500');
                                            }
                                        } else {
                                            quantityInput.classList.remove('border-red-500');
                                        }
                                    } else {
                                        quantityInput.classList.remove('border-red-500');
                                    }
                                }

                                if (errors.length > 0) {
                                    e.preventDefault();
                                    showErrors(errors);
                                    return false;
                                }

                                hideErrors();
                            });

                            // Очистка ошибок при изменении полей
                            [toWarehouseSelect, ingredientSelect, expirationDateSelect, unitSelect, quantityInput].forEach(field => {
                                field.addEventListener('change', function() {
                                    this.classList.remove('border-red-500');
                                    hideErrors();
                                });
                            });

                            quantityInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                quantityError.classList.add('hidden');
                                quantityError.textContent = '';
                                hideErrors();
                                
                                // Проверка в реальном времени при вводе
                                const quantityValue = parseFloat(this.value);
                                const selectedIngredientId = ingredientSelect.value;
                                const selectedExpirationDate = expirationDateSelect.value;
                                const selectedUnitId = unitSelect.value;
                                
                                if (!isNaN(quantityValue) && quantityValue > 0 && selectedIngredientId && selectedExpirationDate && selectedUnitId) {
                                    const batches = stockBatches[selectedIngredientId];
                                    const batchKey = selectedExpirationDate === '' ? '__null__' : selectedExpirationDate;
                                    const batch = batches[batchKey];
                                    
                                    if (batches && batch) {
                                        const baseQuantity = batch.quantity;
                                        const unitData = unitsData[selectedUnitId];
                                        
                                        if (unitData) {
                                            const availableQuantity = baseQuantity / unitData.multiplier_to_base;
                                            
                                            if (quantityValue > availableQuantity) {
                                                quantityError.textContent = `Недостаточно ингредиента на складе. Доступно: ${availableQuantity.toFixed(3)}`;
                                                quantityError.classList.remove('hidden');
                                                this.classList.add('border-red-500');
                                            } else {
                                                quantityError.classList.add('hidden');
                                                quantityError.textContent = '';
                                                this.classList.remove('border-red-500');
                                            }
                                        }
                                    }
                                }
                            });

                            // Запрет ввода некорректных символов в количество
                            quantityInput.addEventListener('keydown', function(e) {
                                const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
                                if (allowedKeys.includes(e.key)) return;
                                if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) return;
                                if (e.key === '.' && !this.value.includes('.')) return;
                                if (!/^[0-9]$/.test(e.key)) e.preventDefault();
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

