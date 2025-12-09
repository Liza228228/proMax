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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <!-- Форма поиска и фильтрации -->
                    <form method="GET" action="{{ route('manager.orders.index') }}" class="mb-6 bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-xl border-2 border-rose-200">
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

                    <div class="mb-6 text-sm font-semibold text-rose-700">
                        Найдено: {{ $orders->total() }} заказов
                    </div>

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
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-rose-600 font-semibold">
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
    <div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
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
                    <p class="mt-2 text-xs text-gray-500">Текущий статус будет заменен на выбранный</p>
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

    <script>
        function openStatusModal(orderId, currentStatus) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const statusSelect = document.getElementById('order_status');
            
            form.action = `/manager/orders/${orderId}/status`;
            statusSelect.value = currentStatus;
            
            // Удаляем все опции
            statusSelect.innerHTML = '';
            
            // Добавляем только доступные статусы (исключаем "Создан" для принятых заказов)
            const availableStatuses = @json($statuses);
            availableStatuses.forEach(function(status) {
                // Если заказ уже принят, не показываем статус "Создан"
                if (currentStatus !== 'Создан' && status === 'Создан') {
                    return;
                }
                
                const option = document.createElement('option');
                option.value = status;
                option.textContent = status;
                if (status === currentStatus) {
                    option.selected = true;
                }
                statusSelect.appendChild(option);
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










