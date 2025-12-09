<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Статус платежа') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
                        Статус платежа
                    </h1>
                    
                    <div class="status p-6 mb-6 rounded-lg text-center text-lg font-semibold
                        @if($statusClass === 'success') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border-2 border-green-300 dark:border-green-700
                        @elseif($statusClass === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 border-2 border-yellow-300 dark:border-yellow-700
                        @elseif($statusClass === 'error') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border-2 border-red-300 dark:border-red-700
                        @else bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 border-2 border-blue-300 dark:border-blue-700
                        @endif">
                        {{ $message }}
                    </div>
                    
                    @if(isset($details) && !empty($details))
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <strong class="text-gray-900 dark:text-gray-100">Детали:</strong><br>
                        <span class="text-gray-700 dark:text-gray-300">{{ $details }}</span>
                    </div>
                    @endif
                    
                    @if(isset($order) && $order)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <strong class="text-gray-900 dark:text-gray-100">Информация о заказе:</strong><br>
                        <span class="text-gray-700 dark:text-gray-300">
                            Номер заказа: #{{ $order->id }}<br>
                            Сумма: {{ number_format($order->total_amount, 0, '.', ' ') }} ₽<br>
                            Статус: {{ $order->status }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="text-center mt-6">
                        <a href="{{ route('cart.index') }}" 
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Вернуться в корзину
                        </a>
                    </div>
                    
                    @if(isset($paymentId))
                    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        ID платежа: {{ $paymentId }}<br>
                        Время: {{ now()->format('Y-m-d H:i:s') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



