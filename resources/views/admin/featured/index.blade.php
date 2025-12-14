<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            Управление новинками
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Сообщения -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Выбранные новинки -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-rose-700">
                            Выбранные новинки ({{ $featuredProducts->count() }}/6)
                        </h3>
                        @if($featuredProducts->count() > 0)
                            <form method="POST" action="{{ route('admin.featured.reset') }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-bold text-red-600 hover:text-red-800 px-4 py-2 rounded-xl hover:bg-red-50">
                                    Сбросить выбор
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($featuredProducts->count() > 0)
                        <div id="featured-products" class="space-y-4">
                            @foreach($featuredProducts as $product)
                                <div class="flex items-center gap-4 p-4 bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-xl hover:shadow-md" data-id="{{ $product->id }}">
                                    <!-- Изображение -->
                                    <div class="w-16 h-16 flex-shrink-0 bg-gradient-to-br from-peach-100 to-rose-100 rounded-xl overflow-hidden border-2 border-rose-300">
                                        @php
                                            $image = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                        @endphp
                                        @if($image)
                                            <img src="{{ asset($image->path) }}" alt="{{ $product->name_product }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Информация -->
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">{{ $product->name_product }}</h4>
                                        <p class="text-sm font-semibold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                                    </div>

                                    <!-- Кнопка удаления -->
                                    <form method="POST" action="{{ route('admin.featured.remove') }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold px-3 py-2 rounded-xl hover:bg-red-50 transition-colors"
                                                title="Удалить из новинок">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-rose-600 font-semibold text-center py-8">
                            Товары не выбраны. На главной странице отображаются последние 6 добавленных товаров.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Доступные товары -->
            @if($featuredProducts->count() < 6)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-rose-700 mb-6">
                        Добавить продукцию в новинки
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($allProducts as $product)
                            <div class="flex items-center gap-3 p-3 bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-xl hover:shadow-md">
                                <!-- Изображение -->
                                <div class="w-12 h-12 flex-shrink-0 bg-gradient-to-br from-peach-100 to-rose-100 rounded-lg overflow-hidden border-2 border-rose-300">
                                    @php
                                        $image = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                    @endphp
                                    @if($image)
                                        <img src="{{ asset($image->path) }}" alt="{{ $product->name_product }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Информация -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm text-gray-900 truncate">{{ $product->name_product }}</h4>
                                    <p class="text-xs font-semibold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                                </div>

                                <!-- Кнопка добавления -->
                                <form method="POST" action="{{ route('admin.featured.add') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-bold">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Пагинация -->
                    @if($allProducts->hasPages())
                        <div class="mt-6">
                            {{ $allProducts->links() }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

</x-app-layout>

