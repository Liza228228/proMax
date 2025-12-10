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

                    <form action="{{ route('admin.reports.orders') }}" method="POST" id="orders-form" class="grid grid-cols-1 md:grid-cols-3 gap-4" novalidate>
                        @csrf
                        <div>
                            <label for="orders_date_from" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата начала
                            </label>
                            <input type="date" id="orders_date_from" name="date_from"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            <div id="orders_date_from_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('date_from')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="orders_date_to" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата окончания
                            </label>
                            <input type="date" id="orders_date_to" name="date_to"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            <div id="orders_date_to_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('date_to')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
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

                    <form action="{{ route('admin.reports.finance') }}" method="POST" id="finance-form" class="grid grid-cols-1 md:grid-cols-3 gap-4" novalidate>
                        @csrf
                        <div>
                            <label for="finance_date_from" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата начала
                            </label>
                            <input type="date" id="finance_date_from" name="date_from"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            <div id="finance_date_from_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('date_from')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="finance_date_to" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата окончания
                            </label>
                            <input type="date" id="finance_date_to" name="date_to"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            <div id="finance_date_to_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('date_to')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
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

                    <form action="{{ route('admin.reports.warehouse') }}" method="POST" id="warehouse-form" class="grid grid-cols-1 md:grid-cols-5 gap-4" novalidate>
                        @csrf
                        <div>
                            <label for="report_type" class="block text-sm font-semibold text-rose-700 mb-2">
                                Тип отчета
                            </label>
                            <select id="report_type" name="report_type"
                                    class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                <option value="all">Вся информация</option>
                                <option value="stock">Остаток склада</option>
                                <option value="operations">Операции</option>
                            </select>
                            <div id="report_type_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('report_type')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
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
                            <div id="warehouse_date_from_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('date_from')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="warehouse_date_to_container">
                            <label for="warehouse_date_to" class="block text-sm font-semibold text-rose-700 mb-2">
                                Дата окончания (необязательно)
                            </label>
                            <input type="date" id="warehouse_date_to" name="date_to"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            <div id="warehouse_date_to_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            @error('date_to')
                                <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                            @enderror
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
            
            // Функция показа ошибки
            function showError(errorElement, message) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
            
            // Функция скрытия ошибки
            function hideError(errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
            
            // Функция валидации дат
            function validateDates(dateFromInput, dateToInput, dateFromError, dateToError) {
                const dateFrom = dateFromInput.value;
                const dateTo = dateToInput.value;
                let hasErrors = false;
                
                // Проверка на пустое значение
                if (!dateFrom) {
                    showError(dateFromError, 'Поле "Дата начала" обязательно для заполнения');
                    dateFromInput.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    hideError(dateFromError);
                    dateFromInput.classList.remove('border-red-500');
                }
                
                if (!dateTo) {
                    showError(dateToError, 'Поле "Дата окончания" обязательно для заполнения');
                    dateToInput.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    hideError(dateToError);
                    dateToInput.classList.remove('border-red-500');
                }
                
                // Проверка, что дата окончания не меньше даты начала
                if (dateFrom && dateTo) {
                    const fromDate = new Date(dateFrom);
                    const toDate = new Date(dateTo);
                    
                    if (toDate < fromDate) {
                        showError(dateToError, 'Дата окончания не может быть меньше даты начала');
                        dateToInput.classList.add('border-red-500');
                        hasErrors = true;
                    } else {
                        hideError(dateToError);
                        dateToInput.classList.remove('border-red-500');
                    }
                }
                
                return !hasErrors;
            }
            
            // Валидация формы отчетов по заказам
            const ordersForm = document.getElementById('orders-form');
            if (ordersForm) {
                const ordersDateFrom = document.getElementById('orders_date_from');
                const ordersDateTo = document.getElementById('orders_date_to');
                const ordersDateFromError = document.getElementById('orders_date_from_error');
                const ordersDateToError = document.getElementById('orders_date_to_error');
                
                ordersForm.addEventListener('submit', function(e) {
                    if (!validateDates(ordersDateFrom, ordersDateTo, ordersDateFromError, ordersDateToError)) {
                        e.preventDefault();
                        const firstError = document.querySelector('#orders-form .border-red-500');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return false;
                    }
                });
                
                // Очистка ошибок при изменении дат
                ordersDateFrom.addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                    hideError(ordersDateFromError);
                    // Проверяем дату окончания при изменении даты начала
                    if (this.value && ordersDateTo.value) {
                        const fromDate = new Date(this.value);
                        const toDate = new Date(ordersDateTo.value);
                        if (toDate < fromDate) {
                            showError(ordersDateToError, 'Дата окончания не может быть меньше даты начала');
                            ordersDateTo.classList.add('border-red-500');
                        } else {
                            hideError(ordersDateToError);
                            ordersDateTo.classList.remove('border-red-500');
                        }
                    }
                });
                
                ordersDateTo.addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                    hideError(ordersDateToError);
                    // Проверяем дату начала при изменении даты окончания
                    if (this.value && ordersDateFrom.value) {
                        const fromDate = new Date(ordersDateFrom.value);
                        const toDate = new Date(this.value);
                        if (toDate < fromDate) {
                            showError(ordersDateToError, 'Дата окончания не может быть меньше даты начала');
                            this.classList.add('border-red-500');
                        } else {
                            hideError(ordersDateToError);
                            this.classList.remove('border-red-500');
                        }
                    }
                });
            }
            
            // Валидация формы финансового отчета
            const financeForm = document.getElementById('finance-form');
            if (financeForm) {
                const financeDateFrom = document.getElementById('finance_date_from');
                const financeDateTo = document.getElementById('finance_date_to');
                const financeDateFromError = document.getElementById('finance_date_from_error');
                const financeDateToError = document.getElementById('finance_date_to_error');
                
                financeForm.addEventListener('submit', function(e) {
                    if (!validateDates(financeDateFrom, financeDateTo, financeDateFromError, financeDateToError)) {
                        e.preventDefault();
                        const firstError = document.querySelector('#finance-form .border-red-500');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return false;
                    }
                });
                
                // Очистка ошибок при изменении дат
                financeDateFrom.addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                    hideError(financeDateFromError);
                    if (this.value && financeDateTo.value) {
                        const fromDate = new Date(this.value);
                        const toDate = new Date(financeDateTo.value);
                        if (toDate < fromDate) {
                            showError(financeDateToError, 'Дата окончания не может быть меньше даты начала');
                            financeDateTo.classList.add('border-red-500');
                        } else {
                            hideError(financeDateToError);
                            financeDateTo.classList.remove('border-red-500');
                        }
                    }
                });
                
                financeDateTo.addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                    hideError(financeDateToError);
                    if (this.value && financeDateFrom.value) {
                        const fromDate = new Date(financeDateFrom.value);
                        const toDate = new Date(this.value);
                        if (toDate < fromDate) {
                            showError(financeDateToError, 'Дата окончания не может быть меньше даты начала');
                            this.classList.add('border-red-500');
                        } else {
                            hideError(financeDateToError);
                            this.classList.remove('border-red-500');
                        }
                    }
                });
            }
            
            // Валидация формы отчета по складу
            const warehouseForm = document.getElementById('warehouse-form');
            if (warehouseForm) {
                const warehouseDateFrom = document.getElementById('warehouse_date_from');
                const warehouseDateTo = document.getElementById('warehouse_date_to');
                const warehouseDateFromError = document.getElementById('warehouse_date_from_error');
                const warehouseDateToError = document.getElementById('warehouse_date_to_error');
                const reportTypeError = document.getElementById('report_type_error');
                const reportTypeSelect = document.getElementById('report_type');
                
                warehouseForm.addEventListener('submit', function(e) {
                    let hasErrors = false;
                    
                    // Проверка типа отчета
                    if (!reportTypeSelect.value) {
                        showError(reportTypeError, 'Выберите тип отчета');
                        reportTypeSelect.classList.add('border-red-500');
                        hasErrors = true;
                    } else {
                        hideError(reportTypeError);
                        reportTypeSelect.classList.remove('border-red-500');
                    }
                    
                    // Проверка дат только если они видны и заполнены
                    const reportType = reportTypeSelect.value;
                    if (reportType !== 'stock') {
                        // Если обе даты заполнены, проверяем их
                        if (warehouseDateFrom.value && warehouseDateTo.value) {
                            const fromDate = new Date(warehouseDateFrom.value);
                            const toDate = new Date(warehouseDateTo.value);
                            
                            if (toDate < fromDate) {
                                showError(warehouseDateToError, 'Дата окончания не может быть меньше даты начала');
                                warehouseDateTo.classList.add('border-red-500');
                                hasErrors = true;
                            } else {
                                hideError(warehouseDateToError);
                                warehouseDateTo.classList.remove('border-red-500');
                            }
                        }
                    }
                    
                    if (hasErrors) {
                        e.preventDefault();
                        const firstError = document.querySelector('#warehouse-form .border-red-500');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return false;
                    }
                });
                
                // Очистка ошибок при изменении дат
                if (warehouseDateFrom) {
                    warehouseDateFrom.addEventListener('change', function() {
                        this.classList.remove('border-red-500');
                        hideError(warehouseDateFromError);
                        if (this.value && warehouseDateTo && warehouseDateTo.value) {
                            const fromDate = new Date(this.value);
                            const toDate = new Date(warehouseDateTo.value);
                            if (toDate < fromDate) {
                                showError(warehouseDateToError, 'Дата окончания не может быть меньше даты начала');
                                warehouseDateTo.classList.add('border-red-500');
                            } else {
                                hideError(warehouseDateToError);
                                warehouseDateTo.classList.remove('border-red-500');
                            }
                        }
                    });
                }
                
                if (warehouseDateTo) {
                    warehouseDateTo.addEventListener('change', function() {
                        this.classList.remove('border-red-500');
                        hideError(warehouseDateToError);
                        if (this.value && warehouseDateFrom && warehouseDateFrom.value) {
                            const fromDate = new Date(warehouseDateFrom.value);
                            const toDate = new Date(this.value);
                            if (toDate < fromDate) {
                                showError(warehouseDateToError, 'Дата окончания не может быть меньше даты начала');
                                this.classList.add('border-red-500');
                            } else {
                                hideError(warehouseDateToError);
                                this.classList.remove('border-red-500');
                            }
                        }
                    });
                }
                
                if (reportTypeSelect) {
                    reportTypeSelect.addEventListener('change', function() {
                        this.classList.remove('border-red-500');
                        hideError(reportTypeError);
                    });
                }
            }
        });
    </script>
</x-app-layout>

