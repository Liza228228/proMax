<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                {{ $product->name_product }}
            </h2>
            <a href="{{ route('catalog.index') }}" 
               class="inline-flex items-center text-sm font-semibold text-rose-600 hover:text-rose-800 px-4 py-2 rounded-lg hover:bg-rose-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Назад к каталогу
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Изображения продукта -->
                        <div>
                            @php
                                $allImages = $product->images;
                            @endphp
                            
                            @if($allImages->count() > 0 && isset($primaryImage))
                                <!-- Главное изображение на всю ширину -->
                                <div class="mb-6">
                                    <div class="w-full bg-gradient-to-br from-peach-100 to-rose-100 rounded-2xl border-2 border-rose-200 overflow-hidden shadow-lg">
                                        <img src="{{ asset($primaryImage->path) }}" 
                                             alt="{{ $product->name_product }}" 
                                             class="w-full h-96 object-cover">
                                    </div>
                                </div>
                                
                                <!-- Миниатюры на всю ширину -->
                                @if($allImages->count() > 1)
                                    <div class="flex gap-3 overflow-x-auto pb-2">
                                        @foreach($allImages as $index => $img)
                                            <a href="{{ route('catalog.show', ['product' => $product, 'image' => $img->id]) }}" 
                                               class="flex-shrink-0 w-full max-w-[120px]">
                                                <div class="w-full h-24 bg-gradient-to-br from-peach-100 to-rose-100 rounded-xl border-2 {{ isset($primaryImage) && $img->id == $primaryImage->id ? 'border-rose-500 shadow-lg' : 'border-rose-200 hover:border-rose-400' }} overflow-hidden shadow-md">
                                                    <img src="{{ asset($img->path) }}" 
                                                         alt="{{ $product->name_product }}" 
                                                         class="w-full h-full object-cover">
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-96 bg-gradient-to-br from-peach-100 to-rose-100 rounded-2xl border-2 border-rose-200 flex items-center justify-center shadow-lg">
                                    <svg class="w-24 h-24 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Информация о продукте -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                                {{ $product->name_product }}
                            </h1>
                            
                            <div class="mb-4">
                                <span class="inline-block px-4 py-2 text-sm font-bold rounded-full bg-gradient-to-r from-rose-100 to-pink-100 text-rose-700 border-2 border-rose-300">
                                    {{ $product->category->name_category ?? 'Без категории' }}
                                </span>
                            </div>

                            <div class="mb-6">
                                <p class="text-4xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-2">
                                    {{ number_format($product->price, 2, '.', ' ') }} ₽
                                </p>
                                @if($product->weight)
                                    <p class="text-lg text-rose-700 font-semibold">
                                        Вес: {{ $product->weight }} г
                                    </p>
                                @endif
                            </div>

                            @if($product->description)
                                <div class="mb-6 p-4 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Описание</h3>
                                    <p class="text-gray-700 leading-relaxed text-left">
                                        {{ $product->description }}
                                    </p>
                                </div>
                            @endif

                            <!-- Ингредиенты -->
                            @if($product->recepts && $product->recepts->count() > 0)
                                <div class="mb-6 p-4 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3">Ингредиенты</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->recepts as $recept)
                                            <span class="px-3 py-1.5 text-sm font-medium bg-white border-2 border-rose-200 text-rose-700 rounded-lg shadow-sm">
                                                {{ $recept->ingredient->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-6 space-y-3">
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                    <span class="text-rose-700 font-semibold">В наличии:</span>
                                    <span class="font-bold text-gray-900 text-lg">{{ $product->total_quantity ?? 0 }} шт.</span>
                                </div>
                                
                                @if($product->nearest_expiration_date)
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                        <span class="text-rose-700 font-semibold">Срок годности:</span>
                                        <span class="font-bold text-gray-900">
                                            {{ $product->nearest_expiration_date->format('d.m.Y') }}
                                        </span>
                                    </div>
                                @endif
                                
                                @if ($product->isExpiringSoon())
                                    @php
                                        $daysLeft = $product->getDaysUntilExpiration();
                                    @endphp
                                    <div class="p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-300 rounded-xl">
                                        <p class="text-sm font-bold text-yellow-800 text-center">
                                            ⚠️ Внимание! Срок годности истекает через {{ $daysLeft }} {{ $daysLeft == 1 ? 'день' : ($daysLeft == 2 ? 'дня' : 'дней') }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-3 mt-6">
                                <div class="flex gap-3">
                                    @auth
                                        @if(Auth::user()->role != 2 && Auth::user()->role != 3)
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" 
                                                        class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg">
                                                    Добавить в корзину
                                                </button>
                                            </form>
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="redirect_to_checkout" value="1">
                                                <button type="submit" 
                                                        class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg">
                                                    Купить
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg">
                                                Добавить в корзину
                                            </button>
                                        </form>
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="redirect_to_checkout" value="1">
                                            <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-lg">
                                                Купить
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <a href="{{ route('catalog.index') }}" 
                                   class="w-full px-6 py-4 border-2 border-rose-300 text-rose-700 rounded-xl font-bold text-lg text-center hover:bg-rose-50 hover:border-rose-400 shadow-md">
                                    Вернуться к каталогу
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>