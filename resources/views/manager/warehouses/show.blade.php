<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                {{ __('Склад: ') . $warehouse->name }}
            </h2>
            <a href="{{ route('manager.warehouses.index') }}" class="text-rose-600 hover:text-rose-800 font-semibold">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-400 text-yellow-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('warning') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <div class="font-semibold mb-2">Исправьте следующие ошибки:</div>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Информация о складе -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Информация о складе</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Название</p>
                                    <p class="font-medium">{{ $warehouse->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Адрес</p>
                                    <p class="font-medium">{{ $warehouse->city }}, {{ $warehouse->street }}, д. {{ $warehouse->house }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Ингредиентов на складе</p>
                                    <p class="font-medium">{{ \App\Models\StockIngredient::where('idWarehouse', $warehouse->id)->count() }}</p>
                                </div>
                                @if($warehouse->is_main)
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Тип склада</p>
                                        <p class="font-medium text-amber-600">
                                            <span class="px-2 py-1 text-xs font-semibold bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 rounded-full border border-amber-300">
                                                Основной склад
                                            </span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 flex flex-col gap-2">
                                <a href="{{ route('manager.warehouses.transfer', $warehouse) }}" 
                                   class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-4 py-2 rounded-xl text-center shadow-md">
                                    Переместить товары
                                </a>
                                <a href="{{ route('manager.warehouses.movementHistory', $warehouse) }}" 
                                   class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-4 py-2 rounded-xl text-center shadow-md">
                                    История перемещений
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ингредиенты на складе и форма добавления -->
                <div class="lg:col-span-2">
                    <!-- Форма добавления ингредиента -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200 mb-6">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold text-rose-700 mb-4">Добавить ингредиент на склад</h3>
                            <form method="POST" action="{{ route('manager.warehouses.addIngredient', $warehouse) }}" id="ingredientForm">
                                @csrf
                                
                                <!-- Основные поля формы -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <!-- Ингредиент (только выбор существующего) -->
                                    <div>
                                        <x-input-label for="idIngredient" :value="__('Ингредиент')" />
                                        <select id="idIngredient" 
                                                name="idIngredient"
                                                class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                            <option value="">Выберите ингредиент</option>
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}" 
                                                        data-unit-type-id="{{ $ingredient->unitType->id ?? '' }}">
                                                    {{ $ingredient->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('idIngredient')" class="mt-2" />
                                    </div>

                                    <!-- Единица измерения (только выбор из уже существующих) -->
                                    <div>
                                        <x-input-label for="unit_id" :value="__('Единица измерения')" />
                                        <select id="unit_id" 
                                                name="unit_id"
                                                required
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
                                        <input id="quantity" class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" type="number" step="0.001" name="quantity" value="{{ old('quantity') }}" required />
                                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                        {{ __('Добавить ингредиент') }}
                                    </button>
                                </div>
                            </form>

                            <script>
                                // Фильтрация единиц измерения на основе выбранного ингредиента
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ingredientSelect = document.getElementById('idIngredient');
                                    const unitSelect = document.getElementById('unit_id');
                                    const ingredientForm = document.getElementById('ingredientForm');
                                    
                                    // Сохраняем все опции единиц измерения (клонируем их)
                                    const allUnitOptions = Array.from(unitSelect.options).map(function(option) {
                                        return {
                                            value: option.value,
                                            text: option.text,
                                            unitTypeId: option.getAttribute('data-unit-type-id') || ''
                                        };
                                    });
                                    
                                    ingredientSelect.addEventListener('change', function() {
                                        const selectedIngredient = this.options[this.selectedIndex];
                                        const ingredientUnitTypeId = selectedIngredient.getAttribute('data-unit-type-id');
                                        
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
                                        
                                        // Сбрасываем выбранное значение единицы измерения
                                        unitSelect.value = '';
                                    });
                                });
                            </script>
                        </div>
                    </div>

                    <!-- Список ингредиентов на складе -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <div class="p-6 text-gray-900">
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-rose-700">Ингредиенты на складе</h3>
                                    @if(isset($stockIngredients) && $stockIngredients->total() > 0)
                                        <form method="GET" action="{{ route('manager.warehouses.show', $warehouse) }}" class="flex gap-2 items-end">
                                            <input type="hidden" name="expiration_filter" value="{{ request('expiration_filter') }}">
                                            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
                                            <div>
                                                <label for="search_ingredient" class="block text-xs font-semibold text-rose-700 mb-1">Поиск</label>
                                                <input type="text" 
                                                       id="search_ingredient"
                                                       name="search_ingredient" 
                                                       value="{{ request('search_ingredient') }}"
                                                       placeholder="Поиск ингредиента..."
                                                       class="w-64 rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                            </div>
                                            <button type="submit" 
                                                    class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-4 py-2 rounded-xl shadow-md">
                                                Поиск
                                            </button>
                                            @if(request('search_ingredient') || request('expiration_filter') || request('date_filter'))
                                                <a href="{{ route('manager.warehouses.show', $warehouse) }}" 
                                                   class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-4 py-2 rounded-xl hover:bg-rose-50 shadow-sm">
                                                    Сбросить
                                                </a>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                                
                                <!-- Кнопки фильтрации по сроку годности (Актуальное/Просроченное) -->
                                <div class="flex gap-2 mb-2">
                                    <form method="GET" action="{{ route('manager.warehouses.show', $warehouse) }}" class="inline">
                                        <input type="hidden" name="search_ingredient" value="{{ request('search_ingredient') }}">
                                        <input type="hidden" name="date_filter" value="{{ request('date_filter', 'with_date') }}">
                                        <input type="hidden" name="expiration_filter" value="actual">
                                        <button type="submit" 
                                                class="px-4 py-2 rounded-xl font-bold shadow-md transition-colors {{ (request('expiration_filter', 'actual') === 'actual') ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' : 'bg-white border-2 border-green-300 text-green-700 hover:bg-green-50' }}">
                                            Актуальное
                                        </button>
                                    </form>
                                    <form method="GET" action="{{ route('manager.warehouses.show', $warehouse) }}" class="inline">
                                        <input type="hidden" name="search_ingredient" value="{{ request('search_ingredient') }}">
                                        <input type="hidden" name="date_filter" value="{{ request('date_filter', 'with_date') }}">
                                        <input type="hidden" name="expiration_filter" value="expired">
                                        <button type="submit" 
                                                class="px-4 py-2 rounded-xl font-bold shadow-md transition-colors {{ request('expiration_filter', 'actual') === 'expired' ? 'bg-gradient-to-r from-red-500 to-rose-500 text-white' : 'bg-white border-2 border-red-300 text-red-700 hover:bg-red-50' }}">
                                            Просроченное
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Кнопки фильтрации по режиму отображения (Раздельно/Суммировать) -->
                                <div class="flex gap-2 mb-4">
                                    <form method="GET" action="{{ route('manager.warehouses.show', $warehouse) }}" class="inline">
                                        <input type="hidden" name="search_ingredient" value="{{ request('search_ingredient') }}">
                                        <input type="hidden" name="expiration_filter" value="{{ request('expiration_filter', 'actual') }}">
                                        <input type="hidden" name="date_filter" value="with_date">
                                        <button type="submit" 
                                                class="px-4 py-2 rounded-xl font-bold shadow-md transition-colors {{ (request('date_filter', 'with_date') === 'with_date') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white' : 'bg-white border-2 border-blue-300 text-blue-700 hover:bg-blue-50' }}">
                                            Раздельно
                                        </button>
                                    </form>
                                    <form method="GET" action="{{ route('manager.warehouses.show', $warehouse) }}" class="inline">
                                        <input type="hidden" name="search_ingredient" value="{{ request('search_ingredient') }}">
                                        <input type="hidden" name="expiration_filter" value="{{ request('expiration_filter', 'actual') }}">
                                        <input type="hidden" name="date_filter" value="without_date">
                                        <button type="submit" 
                                                class="px-4 py-2 rounded-xl font-bold shadow-md transition-colors {{ request('date_filter', 'with_date') === 'without_date' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white' : 'bg-white border-2 border-purple-300 text-purple-700 hover:bg-purple-50' }}">
                                            Суммировать
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if(isset($stockIngredients) && $stockIngredients->total() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-rose-200">
                                        <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Ингредиент
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Количество
                                                </th>
                                                @if(request('date_filter') !== 'without_date')
                                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                        Срок годности
                                                    </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-rose-200">
                                            @forelse($stockIngredients as $stock)
                                                @php
                                                    $isExpired = $stock->expiration_date && \Carbon\Carbon::parse($stock->expiration_date)->isPast();
                                                    $expirationDate = $stock->expiration_date ? \Carbon\Carbon::parse($stock->expiration_date) : null;
                                                    $daysUntilExpiration = $expirationDate ? \Carbon\Carbon::today()->diffInDays($expirationDate, false) : null;
                                                    $isExpiringSoon = $expirationDate && !$isExpired && $daysUntilExpiration >= 0 && $daysUntilExpiration <= 2;
                                                @endphp
                                                <tr class="hover:bg-rose-50 {{ $isExpired ? 'bg-red-50' : ($isExpiringSoon ? 'bg-yellow-50' : '') }}">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $stock->ingredient->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                        @if(isset($stock->display_quantity) && isset($stock->display_unit))
                                                            {{ number_format($stock->display_quantity, 3, '.', ' ') }} {{ $stock->display_unit->code }}
                                                        @else
                                                            {{ number_format($stock->quantity, 0, '.', ' ') }} г
                                                        @endif
                                                    </td>
                                                    @if(request('date_filter') !== 'without_date')
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            @if($expirationDate)
                                                                {{ $expirationDate->format('d.m.Y') }}
                                                                @if($isExpiringSoon)
                                                                    <span class="ml-2 inline-block px-2 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-300">
                                                                        ⚠️ Истекает через {{ $daysUntilExpiration }} {{ $daysUntilExpiration == 1 ? 'день' : ($daysUntilExpiration == 2 ? 'дня' : 'дней') }}
                                                                    </span>
                                                                @elseif($isExpired)
                                                                    <span class="ml-2 inline-block px-2 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-300">
                                                                        Просрочено
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="text-gray-400">Не указан</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ request('date_filter') === 'without_date' ? '2' : '3' }}" class="px-6 py-4 text-center text-sm text-rose-600 font-semibold">
                                                        Ингредиенты не найдены
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Пагинация -->
                                @if($stockIngredients->hasPages())
                                    <div class="mt-6 flex items-center justify-between">
                                        <div class="text-sm text-gray-700">
                                            Показано {{ $stockIngredients->firstItem() ?? 0 }} - {{ $stockIngredients->lastItem() ?? 0 }} из {{ $stockIngredients->total() }} ингредиентов
                                        </div>
                                        <div class="flex items-center gap-2">
                                            {{ $stockIngredients->links() }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-rose-600 font-semibold text-center py-8">На складе пока нет ингредиентов</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

