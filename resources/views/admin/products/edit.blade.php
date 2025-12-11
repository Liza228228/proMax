<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Редактирование продукта') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}" id="product-edit-form" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PATCH')

                        <!-- Name Product -->
                        <div>
                            <x-input-label for="name_product" :value="__('Название продукта')" />
                            <x-text-input id="name_product" class="block mt-1 w-full" type="text" name="name_product" :value="old('name_product', $product->name_product)" autofocus />
                            <div id="name_product_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('name_product')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Описание')" />
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('description', $product->description) }}</textarea>
                            <div id="description_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Weight -->
                            <div>
                                <x-input-label for="weight" :value="__('Вес (кг)')" />
                                <x-text-input id="weight" class="block mt-1 w-full" type="text" name="weight" :value="old('weight', $product->weight)" />
                                <div id="weight_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                                <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Цена (₽)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price" :value="old('price', $product->price)" />
                                <div id="price_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Текущее количество (только для отображения) -->
                            <div>
                                <x-input-label :value="__('Текущее количество')" />
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $product->total_quantity ?? 0 }} шт.</p>
                                <p class="mt-1 text-xs text-gray-500">Количество управляется через добавление продукции</p>
                            </div>

                            <!-- Срок годности -->
                            <div>
                                <x-input-label for="expiration_days" :value="__('Срок годности (дней)')" />
                                <x-text-input id="expiration_days" 
                                             class="block mt-1 w-full" 
                                             type="text" 
                                             name="expiration_days" 
                                             :value="old('expiration_days', $product->expiration_date ?? 7)" />
                                <p class="mt-1 text-sm text-gray-500">Укажите срок годности в количестве дней от текущей даты</p>
                                <div id="expiration_days_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                                <x-input-error :messages="$errors->get('expiration_days')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="idCategory" :value="__('Категория')" />
                            <select id="idCategory" 
                                    name="idCategory" 
                                    class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all">
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('idCategory', $product->idCategory) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_category }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="idCategory_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('idCategory')" class="mt-2" />
                        </div>

                        <!-- Existing Images -->
                        @if($product->images->count() > 0)
                            <div class="mt-4">
                                <x-input-label :value="__('Текущие изображения')" />
                                <div class="flex flex-nowrap gap-3 overflow-x-auto pb-2 mt-2" style="scrollbar-width: thin;">
                                    @foreach($product->images as $image)
                                        <div class="relative flex-shrink-0">
                                            <div class="relative">
                                                <img src="{{ asset($image->path) }}" 
                                                     alt="Изображение продукта" 
                                                     class="w-20 h-20 object-cover rounded border-2 {{ $image->is_primary ? 'border-blue-500' : 'border-gray-300' }}">
                                                <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs px-1 rounded {{ $image->is_primary ? '' : 'hidden' }}" 
                                                     id="badge-{{ $image->id }}">
                                                    Главное
                                                </div>
                                            </div>
                                            <button type="button" 
                                                    onclick="setPrimaryImage({{ $image->id }})"
                                                    class="mt-1 w-full text-xs px-2 py-1 {{ $image->is_primary ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300' }} rounded"
                                                    id="btn-{{ $image->id }}">
                                                {{ $image->is_primary ? 'Главное' : 'Сделать главным' }}
                                            </button>
                                            <label class="block mt-1 text-center">
                                                <input type="checkbox" 
                                                       name="delete_images[]" 
                                                       value="{{ $image->id }}"
                                                       class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                                <span class="ms-1 text-xs text-red-600">Удалить</span>
                                            </label>
                                            <input type="radio" 
                                                   name="primary_image" 
                                                   value="{{ $image->id }}" 
                                                   {{ $image->is_primary ? 'checked' : '' }}
                                                   class="hidden"
                                                   id="radio-{{ $image->id }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <script>
                                function setPrimaryImage(imageId) {
                                    // Убираем выделение со всех
                                    document.querySelectorAll('input[name="primary_image"]').forEach(radio => {
                                        const imgId = radio.value;
                                        const img = radio.closest('.flex-shrink-0').querySelector('img');
                                        const badge = document.getElementById(`badge-${imgId}`);
                                        const btn = document.getElementById(`btn-${imgId}`);
                                        
                                        if (img) {
                                            img.classList.remove('border-blue-500');
                                            img.classList.add('border-gray-300');
                                        }
                                        if (badge) {
                                            badge.classList.add('hidden');
                                        }
                                        if (btn) {
                                            btn.textContent = 'Сделать главным';
                                            btn.classList.remove('bg-blue-500', 'text-white');
                                            btn.classList.add('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
                                        }
                                        radio.checked = false;
                                    });
                                    
                                    // Выделяем выбранное
                                    const selectedRadio = document.getElementById(`radio-${imageId}`);
                                    const selectedImg = selectedRadio.closest('.flex-shrink-0').querySelector('img');
                                    const selectedBadge = document.getElementById(`badge-${imageId}`);
                                    const selectedBtn = document.getElementById(`btn-${imageId}`);
                                    
                                    selectedRadio.checked = true;
                                    if (selectedImg) {
                                        selectedImg.classList.remove('border-gray-300');
                                        selectedImg.classList.add('border-blue-500');
                                    }
                                    if (selectedBadge) {
                                        selectedBadge.classList.remove('hidden');
                                    }
                                    if (selectedBtn) {
                                        selectedBtn.textContent = 'Главное';
                                        selectedBtn.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
                                        selectedBtn.classList.add('bg-blue-500', 'text-white');
                                    }
                                }
                            </script>
                        @endif

                        <!-- New Images -->
                        <div class="mt-4">
                            <x-input-label for="images" :value="__('Добавить изображения (можно выбрать несколько)')" />
                            <input id="images" 
                                   type="file" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*"
                                   class="block mt-1 w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300" 
                                   onchange="previewNewImages(this)" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Выберите изображения и нажмите кнопку "Сделать главным" для выбора главного фото.</p>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                            
                            <!-- Preview New Images -->
                            <div id="newImagePreview" class="mt-4 flex flex-nowrap gap-3 overflow-x-auto pb-2"></div>
                            <input type="hidden" id="new_primary_image_index" name="new_primary_image_index" value="">
                        </div>

                        <script>
                            function previewNewImages(input) {
                                const preview = document.getElementById('newImagePreview');
                                preview.innerHTML = '';
                                
                                if (input.files && input.files.length > 0) {
                                    Array.from(input.files).forEach((file, index) => {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const div = document.createElement('div');
                                            div.className = 'relative flex-shrink-0';
                                            div.innerHTML = `
                                                <div class="relative">
                                                    <img src="${e.target.result}" 
                                                         alt="Preview" 
                                                         class="w-20 h-20 object-cover rounded border-2 border-gray-300"
                                                         id="new-preview-${index}">
                                                    <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs px-1 rounded hidden" id="new-primary-badge-${index}">
                                                        Главное
                                                    </div>
                                                </div>
                                                <button type="button" 
                                                        onclick="setNewPrimaryImage(${index})"
                                                        class="mt-1 w-full text-xs px-2 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded"
                                                        id="new-btn-${index}">
                                                    Сделать главным
                                                </button>
                                            `;
                                            preview.appendChild(div);
                                        };
                                        reader.readAsDataURL(file);
                                    });
                                }
                            }
                            
                            function setNewPrimaryImage(index) {
                                document.getElementById('new_primary_image_index').value = index;
                                // Убираем выделение со всех новых
                                const totalNewImages = document.querySelectorAll('#newImagePreview img').length;
                                for (let i = 0; i < totalNewImages; i++) {
                                    const img = document.getElementById(`new-preview-${i}`);
                                    const badge = document.getElementById(`new-primary-badge-${i}`);
                                    const btn = document.getElementById(`new-btn-${i}`);
                                    
                                    if (img) {
                                        img.classList.remove('border-blue-500');
                                        img.classList.add('border-gray-300');
                                    }
                                    if (badge) {
                                        badge.classList.add('hidden');
                                    }
                                    if (btn) {
                                        btn.textContent = 'Сделать главным';
                                        btn.classList.remove('bg-blue-500', 'text-white');
                                        btn.classList.add('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
                                    }
                                }
                                // Выделяем выбранное
                                const selectedImg = document.getElementById(`new-preview-${index}`);
                                const selectedBadge = document.getElementById(`new-primary-badge-${index}`);
                                const selectedBtn = document.getElementById(`new-btn-${index}`);
                                
                                if (selectedImg) {
                                    selectedImg.classList.remove('border-gray-300');
                                    selectedImg.classList.add('border-blue-500');
                                }
                                if (selectedBadge) {
                                    selectedBadge.classList.remove('hidden');
                                }
                                if (selectedBtn) {
                                    selectedBtn.textContent = 'Главное';
                                    selectedBtn.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-700', 'dark:hover:bg-gray-600', 'text-gray-700', 'dark:text-gray-300');
                                    selectedBtn.classList.add('bg-blue-500', 'text-white');
                                }
                            }
                        </script>

                        <!-- Ingredients (Recipe) -->
                        <div class="mt-4">
                            <x-input-label :value="__('Рецепт (Ингредиенты)')" />
                            <div id="ingredients-container" class="space-y-3 mt-2">
                                <!-- Будет заполнено через JavaScript -->
                            </div>
                            <button type="button" 
                                    onclick="addIngredientRow()" 
                                    class="mt-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md text-sm transition-colors">
                                + Добавить ингредиент
                            </button>
                            <x-input-error :messages="$errors->get('ingredients')" class="mt-2" />
                            <x-input-error :messages="$errors->get('ingredients.*')" class="mt-2" />
                            
                            <!-- Скрытый шаблон для опций ингредиентов -->
                            <template id="ingredient-options-template">
                                <option value="">Выберите ингредиент</option>
                                @foreach($ingredients as $ingredient)
                                    <option value="{{ $ingredient->id }}" data-unit-type-id="{{ $ingredient->unit_type_id ?? '' }}">
                                        {{ $ingredient->name }}
                                    </option>
                                @endforeach
                            </template>
                        </div>

                        <script>
                            // Управление ингредиентами
                            let ingredientRowCount = 0;
                            
                            // Данные о единицах измерения
                            const unitsData = @json($unitsData);
                            
                            // Данные об ингредиентах
                            const ingredientsData = @json($ingredientsData);
                            
                            // Данные текущего рецепта
                            const currentRecepts = @json($receptsData ?? []);

                            function addIngredientRow(ingredientId = '', quantity = '', unitId = '') {
                                const container = document.getElementById('ingredients-container');
                                const template = document.getElementById('ingredient-options-template');
                                const row = document.createElement('div');
                                row.className = 'flex gap-3 items-end';
                                row.id = 'ingredient-row-' + ingredientRowCount;
                                
                                // Создаем селект ингредиента
                                const selectDiv = document.createElement('div');
                                selectDiv.className = 'flex-1';
                                
                                const selectLabel = document.createElement('label');
                                selectLabel.className = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
                                selectLabel.textContent = 'Ингредиент';
                                
                                const select = document.createElement('select');
                                select.name = 'ingredients[' + ingredientRowCount + '][id]';
                                select.id = 'ingredient-select-' + ingredientRowCount;
                                select.className = 'block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all';
                                
                                // Клонируем опции из шаблона
                                const optionsClone = template.content.cloneNode(true);
                                select.appendChild(optionsClone);
                                
                                // Устанавливаем выбранное значение, если указано
                                if (ingredientId) {
                                    select.value = ingredientId;
                                }
                                
                                selectDiv.appendChild(selectLabel);
                                selectDiv.appendChild(select);
                                
                                // Создаем поле количества
                                const quantityDiv = document.createElement('div');
                                quantityDiv.className = 'w-32';
                                
                                const quantityLabel = document.createElement('label');
                                quantityLabel.className = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
                                quantityLabel.textContent = 'Количество';
                                
                                const quantityInput = document.createElement('input');
                                quantityInput.type = 'number';
                                quantityInput.name = 'ingredients[' + ingredientRowCount + '][quantity]';
                                quantityInput.value = quantity;
                                quantityInput.type = 'text';
                                quantityInput.className = 'block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all';
                                
                                quantityDiv.appendChild(quantityLabel);
                                quantityDiv.appendChild(quantityInput);
                                
                                // Создаем селект единицы измерения
                                const unitDiv = document.createElement('div');
                                unitDiv.className = 'w-40';
                                
                                const unitLabel = document.createElement('label');
                                unitLabel.className = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
                                unitLabel.textContent = 'Единица измерения';
                                
                                const unitSelect = document.createElement('select');
                                unitSelect.name = 'ingredients[' + ingredientRowCount + '][unit_id]';
                                unitSelect.id = 'unit-select-' + ingredientRowCount;
                                unitSelect.className = 'block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all';
                                
                                unitDiv.appendChild(unitLabel);
                                unitDiv.appendChild(unitSelect);
                                
                                // Функция обновления единиц измерения при выборе ингредиента
                                function updateUnitsForIngredient() {
                                    const selectedIngredientId = select.value;
                                    if (!selectedIngredientId) {
                                        unitSelect.innerHTML = '<option value="">Сначала выберите ингредиент</option>';
                                        return;
                                    }
                                    
                                    const selectedIngredient = ingredientsData.find(ing => ing.id == selectedIngredientId);
                                    if (!selectedIngredient) {
                                        unitSelect.innerHTML = '<option value="">Сначала выберите ингредиент</option>';
                                        return;
                                    }
                                    
                                    const ingredientUnitTypeId = selectedIngredient.unit_type_id;
                                    const compatibleUnits = unitsData.filter(unit => unit.unit_type_id == ingredientUnitTypeId);
                                    
                                    unitSelect.innerHTML = '<option value="">Выберите единицу</option>';
                                    compatibleUnits.forEach(unit => {
                                        const option = document.createElement('option');
                                        option.value = unit.id;
                                        option.textContent = unit.name;
                                        if (unitId && String(unit.id) === String(unitId)) {
                                            option.selected = true;
                                        }
                                        unitSelect.appendChild(option);
                                    });
                                }
                                
                                // Добавляем обработчик изменения ингредиента
                                select.addEventListener('change', function() {
                                    updateUnitsForIngredient();
                                    this.classList.remove('border-red-500');
                                });
                                
                                // Обработчик для количества
                                quantityInput.addEventListener('input', function() {
                                    this.classList.remove('border-red-500');
                                });
                                
                                // Запрет ввода минуса и некорректных символов в поле количества
                                quantityInput.addEventListener('keydown', function(e) {
                                    const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
                                    if (allowedKeys.includes(e.key)) return;
                                    if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) return;
                                    if (e.key === '.' && !this.value.includes('.')) return;
                                    if (!/^[0-9]$/.test(e.key)) e.preventDefault();
                                });
                                
                                quantityInput.addEventListener('paste', function(e) {
                                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                                    if (!/^\d+\.?\d*$/.test(pastedText) || pastedText.includes('-')) {
                                        e.preventDefault();
                                    }
                                });
                                
                                // Обработчик для единицы измерения
                                unitSelect.addEventListener('change', function() {
                                    this.classList.remove('border-red-500');
                                });
                                
                                // Если ингредиент уже выбран, обновляем единицы
                                if (ingredientId) {
                                    updateUnitsForIngredient();
                                }
                                
                                // Создаем кнопку удаления с правильным замыканием
                                const currentRowId = ingredientRowCount; // Сохраняем текущий ID в локальную переменную
                                const deleteBtn = document.createElement('button');
                                deleteBtn.type = 'button';
                                deleteBtn.onclick = function() { removeIngredientRow(currentRowId); };
                                deleteBtn.className = 'px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm transition-colors';
                                deleteBtn.textContent = 'Удалить';
                                
                                row.appendChild(selectDiv);
                                row.appendChild(quantityDiv);
                                row.appendChild(unitDiv);
                                row.appendChild(deleteBtn);
                                
                                container.appendChild(row);
                                ingredientRowCount++;
                            }

                            function removeIngredientRow(rowId) {
                                const container = document.getElementById('ingredients-container');
                                const rows = container.querySelectorAll('[id^="ingredient-row-"]');
                                
                                // Проверяем, что не удаляем последний ингредиент
                                if (rows.length <= 1) {
                                    alert('Нельзя удалить последний ингредиент. Продукт должен содержать хотя бы один ингредиент.');
                                    return;
                                }
                                
                                const row = document.getElementById('ingredient-row-' + rowId);
                                if (row) {
                                    row.remove();
                                }
                            }

                            // Загружаем текущий рецепт при загрузке страницы
                            document.addEventListener('DOMContentLoaded', function() {
                                if (currentRecepts && currentRecepts.length > 0) {
                                    currentRecepts.forEach(function(recept) {
                                        addIngredientRow(recept.ingredient_id, recept.quantity, recept.unit_id);
                                    });
                                } else {
                                    addIngredientRow();
                                }
                            });
                        </script>

                        <!-- Available -->
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="available" value="1" {{ old('available', $product->available) ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Доступен') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('available')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-6 mt-8 pt-6 border-t-2 border-rose-200">
                            <a href="{{ route('admin.products.index') }}" 
                               class="px-8 py-3 border-2 border-rose-300 text-gray-700 rounded-xl font-bold hover:bg-rose-50 shadow-md">
                                {{ __('Отмена') }}
                            </a>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white rounded-xl font-bold shadow-lg">
                                {{ __('Сохранить изменения') }}
                            </button>
                        </div>
                    </form>

                    <!-- Кастомная валидация формы -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('product-edit-form');
                            const nameProductInput = document.getElementById('name_product');
                            const descriptionInput = document.getElementById('description');
                            const weightInput = document.getElementById('weight');
                            const priceInput = document.getElementById('price');
                            const expirationDaysInput = document.getElementById('expiration_days');
                            const categorySelect = document.getElementById('idCategory');
                            
                            // Элементы для отображения ошибок
                            const nameProductError = document.getElementById('name_product_error');
                            const descriptionError = document.getElementById('description_error');
                            const weightError = document.getElementById('weight_error');
                            const priceError = document.getElementById('price_error');
                            const expirationDaysError = document.getElementById('expiration_days_error');
                            const categoryError = document.getElementById('idCategory_error');

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

                            // Проверка уникальности названия продукта
                            const productId = {{ $product->id }};
                            let nameCheckTimeout;
                            nameProductInput.addEventListener('input', function() {
                                const name = this.value.trim();
                                
                                clearTimeout(nameCheckTimeout);
                                
                                hideFieldError(nameProductError);
                                nameProductInput.classList.remove('border-red-500');
                                nameProductInput.classList.add('border-rose-300');

                                if (name.length === 0) {
                                    return;
                                }

                                nameCheckTimeout = setTimeout(function() {
                                    fetch('{{ route("admin.products.checkName") }}?name_product=' + encodeURIComponent(name) + '&product_id=' + productId, {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.exists) {
                                            showFieldError(nameProductError, 'Продукт с таким названием уже существует.');
                                            nameProductInput.classList.remove('border-rose-300');
                                            nameProductInput.classList.add('border-red-500');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Ошибка при проверке названия:', error);
                                    });
                                }, 500);
                            });

                            // Функция валидации числа
                            function validateNumber(value, fieldName, allowNegative = false) {
                                if (value === '' || value === null) {
                                    return { valid: false, error: `Поле "${fieldName}" обязательно для заполнения` };
                                }
                                value = value.toString().trim();
                                const numValue = parseFloat(value);
                                if (isNaN(numValue)) {
                                    return { valid: false, error: `Поле "${fieldName}" должно быть числом` };
                                }
                                if (!allowNegative && numValue < 0) {
                                    return { valid: false, error: `Поле "${fieldName}" не может быть отрицательным` };
                                }
                                return { valid: true, value: numValue };
                            }

                            // Обработка отправки формы
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                let hasErrors = false;
                                
                                // Проверка названия продукта (синхронная проверка на пустое поле)
                                const nameProduct = nameProductInput.value.trim();
                                if (!nameProduct) {
                                    showFieldError(nameProductError, 'Поле "Название продукта" обязательно для заполнения');
                                    nameProductInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(nameProductError);
                                    nameProductInput.classList.remove('border-red-500');
                                }

                                // Проверка цены
                                const priceValidation = validateNumber(priceInput.value, 'Цена', false);
                                if (!priceValidation.valid) {
                                    showFieldError(priceError, priceValidation.error);
                                    priceInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(priceError);
                                    priceInput.classList.remove('border-red-500');
                                }

                                // Проверка веса (если указан)
                                if (weightInput.value.trim()) {
                                    const weightValidation = validateNumber(weightInput.value, 'Вес', false);
                                    if (!weightValidation.valid) {
                                        showFieldError(weightError, weightValidation.error);
                                        weightInput.classList.add('border-red-500');
                                        hasErrors = true;
                                    } else {
                                        hideFieldError(weightError);
                                        weightInput.classList.remove('border-red-500');
                                    }
                                }

                                // Проверка срока годности
                                const expirationValidation = validateNumber(expirationDaysInput.value, 'Срок годности', false);
                                if (!expirationValidation.valid) {
                                    showFieldError(expirationDaysError, expirationValidation.error);
                                    expirationDaysInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (expirationValidation.value < 1) {
                                    showFieldError(expirationDaysError, 'Срок годности должен быть не менее 1 дня');
                                    expirationDaysInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(expirationDaysError);
                                    expirationDaysInput.classList.remove('border-red-500');
                                }

                                // Проверка категории
                                if (!categorySelect.value) {
                                    showFieldError(categoryError, 'Выберите категорию');
                                    categorySelect.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(categoryError);
                                    categorySelect.classList.remove('border-red-500');
                                }

                                // Проверка ингредиентов
                                const ingredientRows = document.querySelectorAll('[id^="ingredient-row-"]');
                                if (ingredientRows.length === 0) {
                                    hasErrors = true;
                                } else {
                                    ingredientRows.forEach((row, index) => {
                                        const ingredientSelect = row.querySelector('select[name*="[id]"]');
                                        const quantityInput = row.querySelector('input[name*="[quantity]"]');
                                        const unitSelect = row.querySelector('select[name*="[unit_id]"]');
                                        
                                        if (!ingredientSelect || !ingredientSelect.value) {
                                            ingredientSelect.classList.add('border-red-500');
                                            hasErrors = true;
                                        } else {
                                            ingredientSelect.classList.remove('border-red-500');
                                        }
                                        
                                        if (!quantityInput || !quantityInput.value.trim()) {
                                            quantityInput.classList.add('border-red-500');
                                            hasErrors = true;
                                        } else {
                                            const qtyValidation = validateNumber(quantityInput.value, 'Количество', false);
                                            if (!qtyValidation.valid || qtyValidation.value <= 0) {
                                                quantityInput.classList.add('border-red-500');
                                                hasErrors = true;
                                            } else {
                                                quantityInput.classList.remove('border-red-500');
                                            }
                                        }
                                        
                                        if (!unitSelect || !unitSelect.value) {
                                            unitSelect.classList.add('border-red-500');
                                            hasErrors = true;
                                        } else {
                                            unitSelect.classList.remove('border-red-500');
                                        }
                                    });
                                }

                                if (hasErrors) {
                                    // Прокрутка к первому полю с ошибкой
                                    const firstError = document.querySelector('.border-red-500');
                                    if (firstError) {
                                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    }
                                    return false;
                                }

                                // Если все синхронные проверки прошли, проверяем уникальность названия
                                if (nameProduct) {
                                    fetch('{{ route("admin.products.checkName") }}?name_product=' + encodeURIComponent(nameProduct) + '&product_id=' + productId, {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.exists) {
                                            showFieldError(nameProductError, 'Продукт с таким названием уже существует.');
                                            nameProductInput.classList.remove('border-rose-300');
                                            nameProductInput.classList.add('border-red-500');
                                            nameProductInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        } else {
                                            // Если уникальность подтверждена, отправляем форму
                                            form.submit();
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Ошибка при проверке названия:', error);
                                        // Если ошибка при проверке, все равно отправляем форму (серверная валидация сработает)
                                        form.submit();
                                    });
                                } else {
                                    // Если название пустое, форма уже не отправится из-за hasErrors
                                    form.submit();
                                }
                            });

                            // Очистка ошибок при вводе
                            nameProductInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(nameProductError);
                            });

                            priceInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(priceError);
                            });

                            weightInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(weightError);
                            });

                            expirationDaysInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(expirationDaysError);
                            });

                            categorySelect.addEventListener('change', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(categoryError);
                            });

                            // Запрет ввода минуса и некорректных символов в числовые поля
                            [priceInput, weightInput, expirationDaysInput].forEach(input => {
                                input.addEventListener('keydown', function(e) {
                                    const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
                                    if (allowedKeys.includes(e.key)) return;
                                    if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) return;
                                    if (e.key === '.' && !this.value.includes('.')) return;
                                    if (!/^[0-9]$/.test(e.key)) e.preventDefault();
                                });

                                input.addEventListener('paste', function(e) {
                                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                                    if (!/^\d+\.?\d*$/.test(pastedText) || pastedText.includes('-')) {
                                        e.preventDefault();
                                    }
                                });
                            });

                            // Для expiration_days запрещаем точку (только целые числа)
                            expirationDaysInput.addEventListener('keydown', function(e) {
                                if (e.key === '.' || e.key === ',') {
                                    e.preventDefault();
                                }
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

