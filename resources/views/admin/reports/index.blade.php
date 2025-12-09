<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            Создание отчетов
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Отчет по заказам -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-rose-700">
                            Отчет по заказам
                        </h3>
                        <p class="text-sm text-gray-700 font-medium">
                            Список всех заказов за период с детальной информацией и статистикой
                        </p>
                    </div>

                    <form action="{{ route('admin.reports.orders') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label for="orders_date_from" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата начала
                            </label>
                            <input type="date" id="orders_date_from" name="date_from" required
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                        </div>
                        <div>
                            <label for="orders_date_to" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата окончания
                            </label>
                            <input type="date" id="orders_date_to" name="date_to" required
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-md">
                                Сформировать PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Финансовый отчет -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-rose-700">
                            Финансовый отчет
                        </h3>
                        <p class="text-sm text-gray-700 font-medium">
                            Анализ выручки, статистика продаж и топ товаров
                        </p>
                    </div>

                    <form action="{{ route('admin.reports.finance') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label for="finance_date_from" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата начала
                            </label>
                            <input type="date" id="finance_date_from" name="date_from" required
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                        </div>
                        <div>
                            <label for="finance_date_to" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата окончания
                            </label>
                            <input type="date" id="finance_date_to" name="date_to" required
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-md">
                                Сформировать PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Отчет по складу -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-rose-700">
                            Отчет по складу
                        </h3>
                        <p class="text-sm text-gray-700 font-medium">
                            Информация о складах, ингредиентах и операциях за период
                        </p>
                    </div>

                    <form action="{{ route('admin.reports.warehouse') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @csrf
                        <div>
                            <label for="report_type" class="block text-sm font-semibold text-rose-700 mb-2">
                                Тип отчета
                            </label>
                            <select id="report_type" name="report_type" required
                                    class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                <option value="all">Вся информация</option>
                                <option value="stock">Остаток склада</option>
                                <option value="operations">Операции</option>
                            </select>
                        </div>
                        <div>
                            <label for="warehouse_id" class="block text-sm font-semibold text-rose-700 mb-2">
                                Склад
                            </label>
                            <select id="warehouse_id" name="warehouse_id"
                                    class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                <option value="">Все склады</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->city }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="warehouse_date_from_container">
                            <label for="warehouse_date_from" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата начала (необязательно)
                            </label>
                            <input type="date" id="warehouse_date_from" name="date_from"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                        </div>
                        <div id="warehouse_date_to_container">
                            <label for="warehouse_date_to" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата окончания (необязательно)
                            </label>
                            <input type="date" id="warehouse_date_to" name="date_to"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-md">
                                Сформировать PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Установить текущую дату по умолчанию
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
            
            // Установить даты по умолчанию для форм с обязательными датами (заказы и финансы)
            document.querySelectorAll('#orders_date_from, #finance_date_from').forEach(input => {
                input.value = firstDayOfMonth;
            });
            
            document.querySelectorAll('#orders_date_to, #finance_date_to').forEach(input => {
                input.value = today;
            });
            
            // Для отчета по складам даты необязательны, но можно установить по умолчанию
            const warehouseDateFrom = document.getElementById('warehouse_date_from');
            const warehouseDateTo = document.getElementById('warehouse_date_to');
            if (warehouseDateFrom && !warehouseDateFrom.value) {
                warehouseDateFrom.value = firstDayOfMonth;
            }
            if (warehouseDateTo && !warehouseDateTo.value) {
                warehouseDateTo.value = today;
            }
            
            // Функция для скрытия/показа полей дат в зависимости от типа отчета
            function toggleDateFields() {
                const reportType = document.getElementById('report_type').value;
                const dateFromContainer = document.getElementById('warehouse_date_from_container');
                const dateToContainer = document.getElementById('warehouse_date_to_container');
                
                if (reportType === 'stock') {
                    // Скрываем поля дат для типа "Остаток склада"
                    dateFromContainer.style.display = 'none';
                    dateToContainer.style.display = 'none';
                } else {
                    // Показываем поля дат для других типов отчетов
                    dateFromContainer.style.display = 'block';
                    dateToContainer.style.display = 'block';
                }
            }
            
            // Привязываем обработчик изменения типа отчета
            const reportTypeSelect = document.getElementById('report_type');
            if (reportTypeSelect) {
                reportTypeSelect.addEventListener('change', toggleDateFields);
                // Вызываем функцию при загрузке страницы
                toggleDateFields();
            }
        });
    </script>
</x-app-layout>

