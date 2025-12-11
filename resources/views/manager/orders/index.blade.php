<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Управление заказами') }}
        </h2>
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

            <!-- Блок ошибок валидации -->
            <div id="validation-errors" class="hidden mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                <ul id="error-list" class="list-disc list-inside space-y-1 font-semibold"></ul>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <!-- Форма поиска и фильтрации -->
                    <form method="GET" action="{{ route('manager.orders.index') }}" id="orders-filter-form" class="mb-6 bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-xl border-2 border-rose-200" novalidate>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <!-- Поиск -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-semibold text-rose-700 mb-2">Поиск</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Имя, фамилия, логин..."
                                       class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            </div>

                            <!-- Фильтр по статусу -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-rose-700 mb-2">Статус</label>
                                <select id="status" 
                                        name="status" 
                                        class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                    <option value="">Все статусы</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Фильтр по дате -->
                            <div>
                                <label for="date_from" class="block text-sm font-semibold text-rose-700 mb-2">Дата от</label>
                                <input type="date" 
                                       id="date_from" 
                                       name="date_from" 
                                       value="{{ request('date_from') }}" 
                                       class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="date_to" class="block text-sm font-semibold text-rose-700 mb-2">Дата до</label>
                                <input type="date" 
                                       id="date_to" 
                                       name="date_to" 
                                       value="{{ request('date_to') }}" 
                                       class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                Применить фильтры
                            </button>
                            <a href="{{ route('manager.orders.index') }}" 
                               class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm">
                                Сбросить
                            </a>
                        </div>
                    </form>

                    <!-- Список заказов -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rose-200">
                            <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Пользователь
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Дата заказа
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Состав заказа
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Сумма
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Статус
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-rose-200">
                                @forelse ($orders as $order)
                                    <tr class="hover:bg-rose-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order->user->first_name ?? '' }} {{ $order->user->last_name ?? '' }}
                                            <br>
                                            <span class="text-rose-600">{{ $order->user->login ?? '' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $order->order_date->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @if($order->items && $order->items->count() > 0)
                                                <div class="space-y-1">
                                                    @foreach($order->items as $item)
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-gray-900">{{ $item->product->name_product ?? 'Товар удален' }}</span>
                                                            <span class="text-gray-600 ml-2">x{{ $item->quantity }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Товары отсутствуют</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                                            {{ number_format($order->total_amount, 2, '.', ' ') }} ₽
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                                @if($order->status === 'Создан') bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 border-2 border-gray-300
                                                @elseif($order->status === 'Принят') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border-2 border-blue-300
                                                @elseif($order->status === 'Готов к выдаче') bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-2 border-green-300
                                                @elseif($order->status === 'Выполнен') bg-gradient-to-r from-purple-100 to-violet-100 text-purple-700 border-2 border-purple-300
                                                @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($order->status === 'Создан')
                                                <span class="text-gray-500 text-sm italic">Заказ еще не принят</span>
                                            @else
                                                <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}')" 
                                                        class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-4 py-2 rounded-xl shadow-md">
                                                    Изменить статус
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-rose-600 font-semibold">
                                            Заказы не найдены
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    @if($orders->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Показано {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} из {{ $orders->total() }} заказов
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для изменения статуса -->
    <div id="statusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border-2 border-rose-200 max-w-md w-full mx-4 transform transition-all">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Изменить статус заказа</h3>
            </div>
            <form id="statusForm" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="order_status" class="block text-sm font-semibold text-rose-700 mb-2">Выберите новый статус</label>
                    <select id="order_status" 
                            name="status" 
                            class="block w-full px-4 py-3 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm bg-white text-gray-900 font-medium transition-all"
                            required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 justify-end pt-4 border-t border-rose-200">
                    <button type="button" 
                            onclick="closeStatusModal()"
                            class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm transition-all">
                        Отмена
                    </button>
                    <button type="submit" 
                            class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md transition-all">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Валидация фильтра дат -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('orders-filter-form');
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

    <!-- Модальное окно статуса -->
    <script>
        function openStatusModal(orderId, currentStatus) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const statusSelect = document.getElementById('order_status');
            
            form.action = `/manager/orders/${orderId}/status`;
            statusSelect.value = currentStatus;
            
            // Удаляем все опции
            statusSelect.innerHTML = '';
            
            // Определяем порядок статусов
            const statusOrder = {
                'Создан': 0,
                'Принят': 1,
                'Готов к выдаче': 2,
                'Выполнен': 3
            };
            
            const currentStatusOrder = statusOrder[currentStatus] || 0;
            
            // Добавляем только доступные статусы (нельзя вернуться на предыдущие статусы)
            const availableStatuses = @json($statuses);
            availableStatuses.forEach(function(status) {
                const statusOrderValue = statusOrder[status] || 0;
                
                // Показываем только текущий статус и следующие за ним
                // Нельзя вернуться на предыдущие статусы
                if (statusOrderValue >= currentStatusOrder) {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = status;
                    if (status === currentStatus) {
                        option.selected = true;
                    }
                    statusSelect.appendChild(option);
                }
            });
            
            modal.classList.remove('hidden');
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            modal.classList.add('hidden');
        }

        // Закрытие модального окна при клике вне его
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
</x-app-layout>










