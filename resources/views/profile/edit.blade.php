<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            Личный кабинет
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif
            
            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Левая колонка: Персональные данные -->
                <div class="space-y-6">
                    <!-- Информация профиля -->
                    <div class="p-6 sm:p-8 bg-white shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Изменение пароля (недоступно для менеджеров) -->
                    @if(Auth::user()->role != 3)
                        <div class="p-6 sm:p-8 bg-white shadow-xl sm:rounded-2xl border-2 border-rose-200">
                            @include('profile.partials.update-password-form')
                        </div>
                    @endif
                </div>

                <!-- Правая колонка: Заказы пользователя (только для обычных пользователей) -->
                @auth
                    @if(Auth::user()->role != 2 && Auth::user()->role != 3)
                        <div class="space-y-6">
                            <div class="p-6 sm:p-8 bg-white shadow-xl sm:rounded-2xl border-2 border-rose-200">
                                <header class="mb-6">
                                    <h2 class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                                        Мои заказы
                                    </h2>
                                    <p class="mt-2 text-sm text-rose-700 font-medium">
                                        История ваших заказов
                                    </p>
                                </header>

                        @if($orders->total() > 0)
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    <div class="border-2 border-rose-200 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl p-5 hover:shadow-lg">
                                        <!-- Заголовок заказа -->
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-lg">
                                                    Заказ #{{ $order->id }}
                                                </h3>
                                                <p class="text-sm text-rose-700 font-medium mt-1">
                                                    {{ $order->order_date->format('d.m.Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold border-2
                                                    @if($order->status === 'Создан') bg-gradient-to-r from-rose-100 to-pink-100 text-rose-700 border-rose-300
                                                    @elseif($order->status === 'Принят') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border-blue-300
                                                    @elseif($order->status === 'Готов к выдаче') bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-green-300
                                                    @elseif($order->status === 'Выполнен') bg-gradient-to-r from-purple-100 to-violet-100 text-purple-700 border-purple-300
                                                    @else bg-gradient-to-r from-rose-50 to-pink-50 text-gray-700 border-rose-200
                                                    @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Товары в заказе -->
                                        <div class="space-y-2 mb-3">
                                            @if($order->items && $order->items->count() > 0)
                                                @foreach($order->items as $item)
                                                    <div class="flex justify-between items-center text-sm p-2 bg-white rounded-lg border border-rose-100">
                                                        <div class="flex-1">
                                                            <span class="text-gray-900 font-medium">
                                                                {{ $item->product->name_product ?? 'Неизвестный товар' }}
                                                            </span>
                                                            <span class="text-rose-600 font-semibold ml-2">
                                                                × {{ $item->quantity }} шт.
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-900 font-bold">
                                                            {{ number_format($item->price * $item->quantity, 2, '.', ' ') }} ₽
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-sm text-gray-500 italic p-2 bg-white rounded-lg">
                                                    Товары в заказе отсутствуют
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Итоговая сумма и действия -->
                                        <div class="border-t-2 border-rose-200 pt-3 mt-3">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="font-bold text-gray-900 text-lg">
                                                    Итого:
                                                </span>
                                                <span class="font-bold text-xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                                                    {{ number_format($order->total_amount, 2, '.', ' ') }} ₽
                                                </span>
                                            </div>
                                            
                                            <!-- Информация о самовывозе -->
                                            <div class="mt-3 mb-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border-2 border-blue-200">
                                                <div class="flex items-start gap-2">
                                                   
                                                    <div class="flex-1">
                                                        <p class="text-sm font-bold text-blue-800 mb-1">Доступен только самовывоз</p>
                                                        <p class="text-xs text-blue-700">Адрес: г. Иркутск, ул. Ленина, д. 5а</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Кнопка оплаты для неоплаченных заказов -->
                                            @if($order->status === 'Создан')
                                                <form action="{{ route('payment.pay-order', $order->id) }}" method="POST" class="mt-3" id="pay-order-form-{{ $order->id }}">
                                                    @csrf
                                                    <button type="submit" 
                                                            id="pay-button-{{ $order->id }}"
                                                            class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-md">
                                                        Оплатить заказ
                                                    </button>
                                                </form>
                                                <script>
                                                    document.getElementById('pay-order-form-{{ $order->id }}').addEventListener('submit', function(e) {
                                                        const button = document.getElementById('pay-button-{{ $order->id }}');
                                                        button.disabled = true;
                                                        button.textContent = 'Перенаправление на оплату...';
                                                        button.classList.add('opacity-50', 'cursor-not-allowed');
                                                    });
                                                </script>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
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
                        @else
                            <div class="text-center py-8">
                                <div class="mb-4">
                                    <svg class="mx-auto h-16 w-16 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-lg font-bold text-gray-900">
                                    Нет заказов
                                </h3>
                                <p class="mt-2 text-sm text-rose-700 font-medium">
                                    У вас пока нет оформленных заказов
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-lg">
                                        Перейти в каталог
                                    </a>
                                </div>
                            </div>
                        @endif
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
