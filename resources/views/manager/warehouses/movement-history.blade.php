<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                {{ __('История перемещений: ') . $warehouse->name }}
            </h2>
            <a href="{{ route('manager.warehouses.show', $warehouse) }}" class="text-rose-600 hover:text-rose-800 font-semibold">
                ← Назад к складу
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

            <!-- Блок ошибок валидации -->
            <div id="validation-errors" class="hidden mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                <ul id="error-list" class="list-disc list-inside space-y-1 font-semibold"></ul>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <!-- Информация о складе -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                        <h3 class="text-lg font-bold text-rose-700 mb-2">Склад: {{ $warehouse->name }}</h3>
                        <p class="text-sm text-rose-600 font-medium">{{ $warehouse->city }}, {{ $warehouse->street }}, д. {{ $warehouse->house }}</p>
                    </div>

                    <!-- Форма фильтров -->
                    <form method="GET" action="{{ route('manager.warehouses.movementHistory', $warehouse) }}" id="movement-history-filter-form" class="mb-6 bg-gradient-to-r from-rose-50 to-pink-50 p-6 rounded-xl border-2 border-rose-200" novalidate>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <!-- Тип перемещения -->
                            <div>
                                <label for="movement_type" class="block text-sm font-bold text-rose-700 mb-2">Тип перемещения</label>
                                <select id="movement_type" 
                                        name="movement_type" 
                                        class="block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Все перемещения</option>
                                    <option value="incoming" {{ request('movement_type') == 'incoming' ? 'selected' : '' }}>Входящие</option>
                                    <option value="outgoing" {{ request('movement_type') == 'outgoing' ? 'selected' : '' }}>Исходящие</option>
                                </select>
                            </div>

                            <!-- Тип операции (начисления/списания) -->
                            <div>
                                <label for="operation_type" class="block text-sm font-bold text-rose-700 mb-2">Начисления и списания</label>
                                <select id="operation_type" 
                                        name="operation_type" 
                                        class="block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Все операции</option>
                                    <option value="accrual" {{ request('operation_type') == 'accrual' ? 'selected' : '' }}>Начисления</option>
                                    <option value="writeoff" {{ request('operation_type') == 'writeoff' ? 'selected' : '' }}>Списания</option>
                                </select>
                            </div>

                            <!-- Поиск по ингредиенту -->
                            <div>
                                <label for="search" class="block text-sm font-bold text-rose-700 mb-2">Поиск по ингредиенту</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Название ингредиента..."
                                       class="block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                            </div>


                            <!-- Склад-получатель (для исходящих) -->
                            <div>
                                <label for="to_warehouse_id" class="block text-sm font-bold text-rose-700 mb-2">Склад-получатель</label>
                                <select id="to_warehouse_id" 
                                        name="to_warehouse_id" 
                                        class="block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Все склады</option>
                                    @foreach($warehouses as $w)
                                        <option value="{{ $w->id }}" {{ request('to_warehouse_id') == $w->id ? 'selected' : '' }}>
                                            {{ $w->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Дата от -->
                            <div>
                                <label for="date_from" class="block text-sm font-bold text-rose-700 mb-2">Дата от</label>
                                <input type="date" 
                                       id="date_from" 
                                       name="date_from" 
                                       value="{{ request('date_from') }}" 
                                       class="block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                            </div>

                            <!-- Дата до -->
                            <div>
                                <label for="date_to" class="block text-sm font-bold text-rose-700 mb-2">Дата до</label>
                                <input type="date" 
                                       id="date_to" 
                                       name="date_to" 
                                       value="{{ request('date_to') }}" 
                                       class="block w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                Применить фильтры
                            </button>
                            <a href="{{ route('manager.warehouses.movementHistory', $warehouse) }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm inline-flex items-center">
                                Сбросить
                            </a>
                        </div>
                    </form>

                    <!-- Таблица истории перемещений -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rose-200">
                            <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Дата и время
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Ингредиент
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Количество
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Тип
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Откуда / Куда
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-rose-200">
                                @forelse ($movements as $movement)
                                    <tr class="hover:bg-rose-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $movement->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $movement->ingredient->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ number_format($movement->display_quantity, 3, '.', ' ') }} {{ $movement->display_unit->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($movement->from_warehouse_id == $warehouse->id)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-300">
                                                    Исходящее
                                                </span>
                                            @elseif($movement->to_warehouse_id == $warehouse->id)
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-300">
                                                    Входящее
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @if($movement->from_warehouse_id == $warehouse->id)
                                                <!-- Исходящее перемещение -->
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-black">{{ $warehouse->name }}</span>
                                                    <span class="text-rose-600 font-bold">→</span>
                                                    @if($movement->toWarehouse)
                                                        <span class="font-bold text-black">{{ $movement->toWarehouse->name }}</span>
                                                    @else
                                                        <span class="text-rose-600 italic font-medium">Списание</span>
                                                        @if($movement->product)
                                                            <span class="text-xs text-rose-600 ml-2">
                                                                ({{ $movement->product->name_product }})
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @elseif($movement->to_warehouse_id == $warehouse->id)
                                                <!-- Входящее перемещение или начисление -->
                                                <div class="flex items-center gap-2">
                                                    @if($movement->fromWarehouse)
                                                        <span class="font-bold text-black">{{ $movement->fromWarehouse->name }}</span>
                                                        <span class="text-rose-600 font-bold">→</span>
                                                        <span class="font-bold text-black">{{ $warehouse->name }}</span>
                                                    @else
                                                        <span class="text-green-600 italic font-medium">Начисление</span>
                                                        <span class="text-rose-600 font-bold">→</span>
                                                        <span class="font-bold text-black">{{ $warehouse->name }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-rose-600 font-semibold">
                                            Перемещения не найдены
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    @if($movements->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Показано {{ $movements->firstItem() ?? 0 }} - {{ $movements->lastItem() ?? 0 }} из {{ $movements->total() }} движений
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $movements->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Валидация фильтра дат -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('movement-history-filter-form');
            const dateFromInput = document.getElementById('date_from');
            const dateToInput = document.getElementById('date_to');
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
                
                // Прокрутка к ошибкам
                validationErrors.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Функция скрытия ошибок
            function hideErrors() {
                validationErrors.classList.add('hidden');
                errorList.innerHTML = '';
            }

            // Функция валидации дат
            function validateDates() {
                const errors = [];
                const dateFrom = dateFromInput.value;
                const dateTo = dateToInput.value;

                // Если обе даты заполнены, проверяем их соотношение
                if (dateFrom && dateTo) {
                    const dateFromObj = new Date(dateFrom);
                    const dateToObj = new Date(dateTo);

                    if (dateFromObj > dateToObj) {
                        errors.push('Дата "от" не может быть больше даты "до"');
                        dateFromInput.classList.add('border-red-500');
                        dateToInput.classList.add('border-red-500');
                    } else {
                        dateFromInput.classList.remove('border-red-500');
                        dateToInput.classList.remove('border-red-500');
                    }
                }

                return errors;
            }

            // Обработка отправки формы
            form.addEventListener('submit', function(e) {
                const errors = validateDates();

                if (errors.length > 0) {
                    e.preventDefault();
                    showErrors(errors);
                    return false;
                } else {
                    hideErrors();
                }
            });

            // Валидация при изменении дат
            dateFromInput.addEventListener('change', function() {
                const errors = validateDates();
                if (errors.length > 0) {
                    showErrors(errors);
                } else {
                    hideErrors();
                }
            });

            dateToInput.addEventListener('change', function() {
                const errors = validateDates();
                if (errors.length > 0) {
                    showErrors(errors);
                } else {
                    hideErrors();
                }
            });

            // Очистка ошибок при вводе
            dateFromInput.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                if (errorList.children.length > 0) {
                    hideErrors();
                }
            });

            dateToInput.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                if (errorList.children.length > 0) {
                    hideErrors();
                }
            });
        });
    </script>
</x-app-layout>

