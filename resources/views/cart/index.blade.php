<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            Корзина
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Блок ошибок валидации -->
            <div id="validation-errors" class="hidden mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                <ul id="error-list" class="list-disc list-inside space-y-1 font-semibold"></ul>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if($items->count() > 0)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                    <div class="p-8">
                        <div class="space-y-6">
                            @foreach($items as $item)
                                <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-2xl overflow-hidden shadow-md flex p-5">
                                    @php
                                        $product = $item->product;
                                        $primaryImage = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                    @endphp
                                    
                                    <!-- Квадратное изображение товара слева -->
                                    <div class="relative w-28 h-28 flex-shrink-0 bg-white flex items-center justify-center p-2 rounded-xl border-2 border-rose-200 shadow-md">
                                        @if($primaryImage)
                                            <a href="{{ route('catalog.show', $product) }}" class="w-full h-full flex items-center justify-center">
                                                <img src="{{ asset($primaryImage->path) }}" 
                                                     alt="{{ $product->name_product }}" 
                                                     class="max-w-full max-h-full w-auto h-auto object-contain">
                                            </a>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Информация о товаре справа -->
                                    <div class="p-5 flex-1 flex flex-col min-w-0">
                                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                            <a href="{{ route('catalog.show', $product) }}" class="hover:text-rose-600">
                                                {{ $product->name_product }}
                                            </a>
                                        </h3>
                                        
                                        <!-- Наличие -->
                                        <div class="mb-3 flex items-center gap-2">
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-base font-semibold text-green-700">Есть в наличии</span>
                                        </div>
                                        
                                        @if($product->description)
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 line-clamp-2">
                                                {{ $product->description }}
                                            </p>
                                        @endif

                                        <!-- Количество и цена -->
                                        <div class="flex items-center justify-between mt-auto mb-4 pt-4 border-t-2 border-rose-200">
                                            <!-- Количество -->
                                            <div class="flex items-center gap-3">
                                                <span class="text-lg font-semibold text-gray-700">Кол-во:</span>
                                                <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-item-form" data-item-id="{{ $item->id }}" novalidate>
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" 
                                                           name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           data-max="{{ $product->total_quantity }}"
                                                           data-price="{{ $item->price }}"
                                                           data-item-id="{{ $item->id }}"
                                                           data-product-name="{{ $product->name_product }}"
                                                           class="quantity-input w-20 px-3 py-2 border-2 border-rose-300 rounded-lg text-center text-lg font-bold bg-white text-gray-900 focus:border-rose-500 focus:ring-2 focus:ring-rose-300">
                                                </form>
                                            </div>

                                            <!-- Цена -->
                                            <div class="text-right">
                                                <p class="text-sm text-gray-600 mb-1">Цена за шт.</p>
                                                <p class="text-lg font-bold text-gray-900 mb-2 item-price" data-item-id="{{ $item->id }}">
                                                    {{ number_format($item->price, 0, '.', ' ') }} руб./шт
                                                </p>
                                                <p class="text-sm text-gray-600 mb-1">Итого:</p>
                                                <p class="text-2xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent item-total" data-item-id="{{ $item->id }}">
                                                    {{ number_format($item->total, 0, '.', ' ') }} ₽
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Кнопка удаления -->
                                        <div class="mt-3 flex justify-end">
                                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white rounded-lg font-bold shadow-md"
                                                        onclick="return confirm('Вы уверены, что хотите удалить этот товар из корзины?')">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Итого -->
                        <div class="mt-10 pt-8 border-t-4 border-rose-300">
                            <div class="flex justify-between items-center mb-8 bg-gradient-to-r from-rose-100 to-pink-100 p-6 rounded-2xl border-2 border-rose-300">
                                <span class="text-3xl font-bold text-gray-900">
                                    Итого:
                                </span>
                                <span class="text-4xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent cart-total">
                                    {{ number_format($total, 0, '.', ' ') }} ₽
                                </span>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="flex flex-col sm:flex-row gap-5">
                                <a href="{{ route('catalog.index') }}" 
                                   class="flex-1 px-8 py-4 border-2 border-rose-300 text-gray-700 rounded-xl font-bold hover:bg-rose-50 text-center shadow-md">
                                    Продолжить покупки
                                </a>
                                @auth
                                    <a href="{{ route('cart.checkout') }}" 
                                       class="flex-1 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-8 py-4 rounded-xl font-bold text-center shadow-lg">
                                        Оформить заказ
                                    </a>
                                @else
                                    <a href="{{ route('login', ['redirect' => 'cart']) }}" 
                                       class="flex-1 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-8 py-4 rounded-xl font-bold text-center shadow-lg">
                                        Войти для оформления заказа
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Пустая корзина -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                    <div class="p-16 text-center">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">
                            Ваша корзина пуста
                        </h3>
                        <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                            Добавьте товары из нашего каталога, чтобы они появились здесь
                        </p>
                        <a href="{{ route('catalog.index') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white text-lg rounded-xl font-bold shadow-lg">
                            Перейти в каталог
                            <svg class="ml-3 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const validationErrors = document.getElementById('validation-errors');
            const errorList = document.getElementById('error-list');
            let updateTimeout;

            // Функция для форматирования числа с пробелами
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }

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

            // Функция валидации количества
            function validateQuantity(value, maxQuantity, productName) {
                if (value === '' || value === null) {
                    return { 
                        valid: false, 
                        error: `Укажите количество для "${productName}"`,
                        correctedValue: 1
                    };
                }

                // Удаляем пробелы
                value = value.toString().trim();

                // Проверяем формат числа
                const numericValue = parseInt(value);
                
                if (isNaN(numericValue)) {
                    return { 
                        valid: false, 
                        error: `Количество для "${productName}" должно быть числом`,
                        correctedValue: 1
                    };
                }

                if (numericValue < 1) {
                    return { 
                        valid: false, 
                        error: `Количество для "${productName}" не может быть меньше 1`,
                        correctedValue: 1
                    };
                }

                if (numericValue > maxQuantity) {
                    return { 
                        valid: false, 
                        error: `Недостаточно товара на складе для "${productName}". Доступно: ${maxQuantity} шт.`,
                        correctedValue: maxQuantity
                    };
                }

                return { valid: true, value: numericValue };
            }

            // Функция для пересчета суммы товара
            function updateItemTotal(itemId, quantity, price) {
                const total = quantity * price;
                const totalElement = document.querySelector(`.item-total[data-item-id="${itemId}"]`);
                if (totalElement) {
                    totalElement.textContent = formatNumber(Math.round(total)) + ' ₽';
                }
                return total;
            }

            // Функция для пересчета общей суммы
            function updateCartTotal() {
                let grandTotal = 0;
                quantityInputs.forEach(input => {
                    const itemId = input.getAttribute('data-item-id');
                    const price = parseFloat(input.getAttribute('data-price'));
                    const quantity = parseInt(input.value) || 0;
                    grandTotal += updateItemTotal(itemId, quantity, price);
                });
                
                const cartTotalElement = document.querySelector('.cart-total');
                if (cartTotalElement) {
                    cartTotalElement.textContent = formatNumber(Math.round(grandTotal)) + ' ₽';
                }
            }

            // Обработчик изменения количества
            quantityInputs.forEach(input => {
                // Запрет ввода всего кроме цифр
                input.addEventListener('keydown', function(e) {
                    // Разрешаем: Backspace, Delete, Tab, Escape, Enter, стрелки
                    const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'];
                    
                    // Если нажата служебная клавиша - разрешаем
                    if (allowedKeys.includes(e.key)) {
                        return;
                    }
                    
                    // Если нажат Ctrl/Cmd + A, C, V, X - разрешаем (копирование/вставка/выделение)
                    if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) {
                        return;
                    }
                    
                    // Проверяем, что это цифра (0-9)
                    if (!/^[0-9]$/.test(e.key)) {
                        e.preventDefault();
                    }
                });

                input.addEventListener('paste', function(e) {
                    // Получаем вставляемый текст
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    
                    // Проверяем, что вставляемый текст содержит только цифры
                    if (!/^\d+$/.test(pastedText)) {
                        e.preventDefault();
                    }
                });

                input.addEventListener('input', function() {
                    const itemId = this.getAttribute('data-item-id');
                    const price = parseFloat(this.getAttribute('data-price'));
                    let quantity = parseInt(this.value) || 0;
                    
                    // Если введен 0, автоматически заменяем на 1
                    if (this.value !== '' && quantity === 0) {
                        this.value = 1;
                        quantity = 1;
                    }
                    
                    // Обновляем сумму товара
                    updateItemTotal(itemId, quantity, price);
                    
                    // Обновляем общую сумму
                    updateCartTotal();

                    // Очищаем ошибки при вводе
                    this.classList.remove('border-red-500');
                    hideErrors();
                });

                // Автоматическое обновление на сервере через AJAX с задержкой
                input.addEventListener('change', function() {
                    const form = this.closest('.cart-item-form');
                    const itemId = this.getAttribute('data-item-id');
                    let quantity = this.value;
                    const maxQuantity = parseInt(this.getAttribute('data-max')) || 999;
                    const productName = this.getAttribute('data-product-name');
                    
                    // Если введен 0 или пустое значение, автоматически ставим 1 без ошибки
                    if (quantity === '' || quantity === '0' || parseInt(quantity) === 0) {
                        this.value = 1;
                        quantity = '1';
                        updateCartTotal();
                        this.classList.remove('border-red-500');
                        hideErrors();
                    }
                    
                    // Валидация количества (для проверки максимума и формата)
                    const validation = validateQuantity(quantity, maxQuantity, productName);
                    
                    if (!validation.valid) {
                        this.value = validation.correctedValue;
                        updateCartTotal();
                        this.classList.add('border-red-500');
                        showErrors([validation.error]);
                        return;
                    }

                    // Очищаем предыдущий таймер
                    clearTimeout(updateTimeout);
                    
                    // Устанавливаем новый таймер для отправки на сервер
                    updateTimeout = setTimeout(function() {
                        if (form) {
                            const formData = new FormData(form);
                            
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                }
                                return response.json().then(data => {
                                    throw new Error(data.error || 'Ошибка обновления');
                                });
                            })
                            .then(data => {
                                // Обновление успешно
                                if (data.cart_total !== undefined) {
                                    const cartTotalElement = document.querySelector('.cart-total');
                                    if (cartTotalElement) {
                                        cartTotalElement.textContent = formatNumber(Math.round(data.cart_total)) + ' ₽';
                                    }
                                }
                                hideErrors();
                            })
                            .catch(error => {
                                console.error('Ошибка:', error);
                                // В случае ошибки показываем сообщение пользователю
                                if (error.message) {
                                    showErrors([error.message]);
                                }
                            });
                        }
                    }, 1000); // Задержка 1 секунда после последнего изменения
                });
            });
        });
    </script>
</x-app-layout>


