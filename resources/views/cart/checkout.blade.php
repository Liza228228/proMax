<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Оформление заказа') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('payment.create') }}" method="POST" id="checkout-form">
                @csrf
                
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-6">
                            Выберите товары для заказа
                        </h3>

                        <div class="space-y-4 mb-8">
                            @foreach($items as $item)
                                @php
                                    $product = $item->product;
                                    $primaryImage = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                    $itemTotal = $item->quantity * $item->price;
                                @endphp
                                
                                <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-xl overflow-hidden shadow-md p-5">
                                    <div class="flex items-start gap-4">
                                        <!-- Чекбокс выбора -->
                                        <div class="flex items-center pt-2">
                                            <input type="checkbox" 
                                                   name="selected_items[]" 
                                                   value="{{ $item->id }}" 
                                                   id="item_{{ $item->id }}"
                                                   class="w-5 h-5 text-rose-600 border-2 border-rose-300 rounded focus:ring-rose-500 focus:ring-2"
                                                   checked
                                                   onchange="updateTotal()">
                                        </div>

                                        <!-- Изображение товара -->
                                        <div class="relative w-24 h-24 flex-shrink-0 bg-gradient-to-br from-peach-100 to-rose-100 flex items-center justify-center rounded-xl border-2 border-rose-200 shadow-sm">
                                            @if($primaryImage)
                                                <img src="{{ asset($primaryImage->path) }}" 
                                                     alt="{{ $product->name_product }}" 
                                                     class="max-w-full max-h-full w-auto h-auto object-contain">
                                            @else
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Информация о товаре -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2">
                                                {{ $product->name_product }}
                                            </h4>
                                            
                                            <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                                <div class="p-2 bg-white rounded-lg">
                                                    <span class="text-rose-700 font-medium">Количество:</span>
                                                    <span class="ml-2 font-bold text-gray-900">{{ $item->quantity }} шт.</span>
                                                </div>
                                                <div class="p-2 bg-white rounded-lg">
                                                    <span class="text-rose-700 font-medium">Цена за шт.:</span>
                                                    <span class="ml-2 font-bold text-gray-900">{{ number_format($item->price, 0, '.', ' ') }} ₽</span>
                                                </div>
                                            </div>

                                            <div class="mt-3 pt-3 border-t-2 border-rose-200">
                                                <span class="text-rose-700 font-semibold">Итого за товар:</span>
                                                <span class="ml-2 text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent item-total" data-item-id="{{ $item->id }}" data-total="{{ $itemTotal }}">
                                                    {{ number_format($itemTotal, 0, '.', ' ') }} ₽
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Информация о самовывозе -->
                        <div class="mt-8 mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-300">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-blue-800 mb-2">Информация о доставке</h4>
                                    <p class="text-blue-700 font-medium">
                                        <strong>Доступен только самовывоз.</strong> После оплаты заказа вы сможете забрать его в нашем магазине по адресу: г. Иркутск, ул. Ленина, д. 5а
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Итоговая сумма -->
                        <div class="mt-8 pt-6 border-t-4 border-rose-300">
                            <div class="bg-gradient-to-r from-rose-100 to-pink-100 rounded-xl p-6 mb-6 border-2 border-rose-300">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-gray-900">
                                        Итого к оплате:
                                    </span>
                                    <span class="text-3xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent" id="total-amount">
                                        {{ number_format($total, 0, '.', ' ') }} ₽
                                    </span>
                                </div>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="flex gap-4">
                                <a href="{{ route('cart.index') }}" 
                                   class="flex-1 px-6 py-3 border-2 border-rose-300 text-rose-700 rounded-xl font-bold hover:bg-rose-50 hover:border-rose-400 text-center shadow-md">
                                    Назад в корзину
                                </a>
                                <button type="submit" 
                                        id="payment-button"
                                        class="flex-1 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                    Оплатить заказ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateTotal() {
            let total = 0;
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
            const paymentButton = document.getElementById('payment-button');
            
            checkboxes.forEach(function(checkbox) {
                const itemId = checkbox.value;
                const itemTotalElement = document.querySelector(`.item-total[data-item-id="${itemId}"]`);
                if (itemTotalElement) {
                    total += parseFloat(itemTotalElement.getAttribute('data-total'));
                }
            });

            const formattedTotal = new Intl.NumberFormat('ru-RU').format(Math.round(total));
            document.getElementById('total-amount').textContent = formattedTotal + ' ₽';
            
            // Блокируем кнопку оплаты, если ничего не выбрано
            if (checkboxes.length === 0) {
                paymentButton.disabled = true;
                paymentButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                paymentButton.disabled = false;
                paymentButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            updateTotal();
            
            // Добавляем обработчик для всех чекбоксов
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateTotal);
            });
        });
    </script>
</x-app-layout>

