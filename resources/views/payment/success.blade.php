<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Статус платежа') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                            Платеж не был совершен
                        </h1>
                    </div>
                    
                    <div class="p-6 mb-6 rounded-xl text-center text-lg font-bold bg-gradient-to-r from-rose-50 to-pink-50 text-rose-800 border-2 border-rose-300 shadow-md">
                        Платеж не был завершен. Вы можете оплатить заказ в личном кабинете.
                    </div>
                    
                    @if(isset($order) && $order)
                    <div class="bg-gradient-to-br from-rose-50 to-pink-50 p-5 rounded-xl mb-6 border-2 border-rose-200">
                        <strong class="text-gray-900 font-bold text-lg">Информация о заказе:</strong><br>
                        <span class="text-gray-700 font-medium">
                            Номер заказа: #<span class="font-bold text-rose-700">{{ $order->id }}</span><br>
                            Сумма: <span class="font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</span><br>
                            Статус: <span class="font-bold text-rose-700">{{ $order->status }}</span>
                        </span>
                    </div>
                    
                    @endif
                    
                    <!-- Информация о возможности оплаты в личном кабинете -->
                    <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl p-6 mb-6 border-2 border-rose-300">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-rose-800 mb-2">Оплата заказа</h4>
                                <p class="text-rose-700 font-medium mb-3">
                                    Вы можете оплатить заказ в разделе <strong>"Мои заказы"</strong> в личном кабинете.
                                </p>
                                <p class="text-rose-700 text-sm mb-3">
                                    После оплаты заказ будет принят в работу и станет доступен для самовывоза по адресу:<br>
                                    <strong>г. Иркутск, ул. Ленина, д. 5а</strong>
                                </p>
                                <a href="{{ route('profile.edit') }}" 
                                   class="inline-block bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md transition-all">
                                    Перейти в личный кабинет
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-6">
                        <a href="{{ route('cart.index') }}" 
                           class="inline-block bg-white border-2 border-rose-300 text-rose-700 hover:bg-rose-50 px-8 py-3 rounded-xl font-bold shadow-md transition-all">
                            Вернуться в корзину
                        </a>
                    </div>
                    
                    <div class="mt-6 text-center text-sm text-rose-600 font-medium">
                        Время: {{ now()->format('d.m.Y H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

