<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ isset($category) ? $category->name_category : __('Каталог продукции') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Форма поиска и фильтров -->
            <div class="mb-6 bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200 p-6">
                <!-- Блок ошибок валидации -->
                <div id="validation-errors" class="hidden mb-4 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <ul id="error-list" class="list-disc list-inside space-y-1 font-semibold"></ul>
                </div>

                <form method="GET" action="{{ isset($category) ? route('catalog.category', $category) : route('catalog.index') }}" class="space-y-4" id="catalog-filter-form" novalidate>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Поиск -->
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-sm font-semibold text-rose-700 mb-2">Поиск</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Название товара или описание..."
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        </div>

                        <!-- Фильтр по категории (только если не в режиме категории) -->
                        @if(!isset($category))
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-rose-700 mb-2">Категория</label>
                                <select id="category_id" 
                                        name="category_id" 
                                        class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                    <option value="">Все категории</option>
                                    @php
                                        $allCategories = $categories ?? \App\Models\Category::where('available', true)->orderBy('name_category')->get();
                                    @endphp
                                    @foreach($allCategories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name_category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Минимальная цена -->
                        <div>
                            <label for="price_min" class="block text-sm font-semibold text-rose-700 mb-2">Цена от (₽)</label>
                            <input type="text" 
                                   id="price_min" 
                                   name="price_min" 
                                   value="{{ request('price_min') }}" 
                                   placeholder="0"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        </div>

                        <!-- Максимальная цена -->
                        <div>
                            <label for="price_max" class="block text-sm font-semibold text-rose-700 mb-2">Цена до (₽)</label>
                            <input type="text" 
                                   id="price_max" 
                                   name="price_max" 
                                   value="{{ request('price_max') }}" 
                                   placeholder="Без ограничений"
                                   class="w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                Применить
                            </button>
                            <a href="{{ isset($category) ? route('catalog.category', $category) : route('catalog.index') }}" 
                               class="px-6 py-2 border-2 border-rose-300 text-rose-700 font-bold rounded-xl hover:bg-rose-50 shadow-sm">
                                Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if(isset($category))
                <!-- Режим отображения конкретной категории -->
                <div class="mb-6">
                    <a href="{{ route('catalog.index') }}" 
                       class="inline-flex items-center text-sm font-semibold text-rose-600 hover:text-rose-800 mb-2 px-4 py-2 rounded-lg hover:bg-rose-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Назад к категориям
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                    <div class="p-6">
                        @if($products->count() > 0)
                            <div class="mb-4 text-sm text-gray-600">
                                Показано {{ $products->firstItem() }}-{{ $products->lastItem() }} из {{ $products->total() }} товаров
                            </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse ($products as $product)
                                <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-2xl overflow-hidden shadow-lg flex flex-col">
                                    <div class="relative w-full aspect-square bg-gradient-to-br from-peach-100 to-rose-100 overflow-hidden border-b-2 border-rose-200">
                                        @php
                                            $allImages = $product->images;
                                            $primaryImage = $allImages->where('is_primary', 1)->first() ?? $allImages->first();
                                        @endphp
                                        @if($primaryImage)
                                            <a href="{{ route('catalog.show', $product) }}" class="block w-full h-full">
                                                <img src="{{ $primaryImage->valid_path ? asset($primaryImage->valid_path) : '#' }}" 
                                                     alt="{{ $product->name_product }}" 
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><svg class=\'w-12 h-12 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg></div>'">
                                            </a>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-5 flex flex-col flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                            {{ $product->name_product }}
                                        </h3>
                                        
                                        <p class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-3">
                                            {{ number_format($product->price, 2, '.', ' ') }} ₽
                                        </p>
                                        
                                        @if($product->description)
                                            <p class="text-sm text-gray-700 mb-3 line-clamp-3 break-words whitespace-normal">
                                                {{ $product->description }}
                                            </p>
                                        @endif
                                        
                                        @if ($product->isExpiringSoon())
                                            @php
                                                $daysLeft = $product->getDaysUntilExpiration();
                                            @endphp
                                            <div class="mb-3 p-3 bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-300 rounded-xl">
                                                <p class="text-xs font-bold text-yellow-800 text-center">
                                                     Срок годности истекает через {{ $daysLeft }} {{ $daysLeft == 1 ? 'день' : ($daysLeft == 2 ? 'дня' : 'дней') }}
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <div class="flex gap-2 mt-auto pt-4 border-t-2 border-rose-200">
                                            <a href="{{ route('catalog.show', $product) }}" 
                                               class="flex-1 bg-gradient-to-r from-rose-100 to-pink-100 border-2 border-rose-300 text-rose-700 px-4 py-2.5 rounded-xl text-sm font-bold text-center hover:from-rose-200 hover:to-pink-200 hover:border-rose-400 shadow-sm">
                                                Подробнее
                                            </a>
                                            @auth
                                                @if(Auth::user()->role != 2 && Auth::user()->role != 3)
                                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" 
                                                                class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md">
                                                            Добавить в корзину
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" 
                                                            class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md">
                                                        Добавить в корзину
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <div class="mb-4">
                                        <svg class="mx-auto w-16 h-16 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-rose-700 text-xl font-semibold">Продукты не найдены</p>
                                </div>
                            @endforelse
                        </div>

                        @if(isset($products) && $products->hasPages())
                            <div class="mt-6 flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Показано {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} из {{ $products->total() }} товаров
                                </div>
                                <div class="flex items-center gap-2">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif(isset($products))
                <!-- Режим отображения результатов поиска/фильтрации -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-900">
                                Найдено товаров: {{ $products->total() }}
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse ($products as $product)
                                <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-2xl overflow-hidden shadow-lg flex flex-col">
                                    <div class="relative w-full aspect-square bg-gradient-to-br from-peach-100 to-rose-100 overflow-hidden border-b-2 border-rose-200">
                                        @php
                                            $allImages = $product->images;
                                            $primaryImage = $allImages->where('is_primary', 1)->first() ?? $allImages->first();
                                        @endphp
                                        @if($primaryImage)
                                            <a href="{{ route('catalog.show', $product) }}" class="block w-full h-full">
                                                <img src="{{ $primaryImage->valid_path ? asset($primaryImage->valid_path) : '#' }}" 
                                                     alt="{{ $product->name_product }}" 
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><svg class=\'w-12 h-12 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg></div>'">
                                            </a>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-5 flex flex-col flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                            {{ $product->name_product }}
                                        </h3>
                                        
                                        <p class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-3">
                                            {{ number_format($product->price, 2, '.', ' ') }} ₽
                                        </p>
                                        
                                        @if($product->description)
                                            <p class="text-sm text-gray-700 mb-3 line-clamp-3 break-words whitespace-normal">
                                                {{ $product->description }}
                                            </p>
                                        @endif
                                        
                                        @if ($product->isExpiringSoon())
                                            @php
                                                $daysLeft = $product->getDaysUntilExpiration();
                                            @endphp
                                            <div class="mb-3 p-3 bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-300 rounded-xl">
                                                <p class="text-xs font-bold text-yellow-800 text-center">
                                                     Срок годности истекает через {{ $daysLeft }} {{ $daysLeft == 1 ? 'день' : ($daysLeft == 2 ? 'дня' : 'дней') }}
                                                </p>
                                            </div>
                                        @endif
                                        
                                        <div class="flex gap-2 mt-auto pt-4 border-t-2 border-rose-200">
                                            <a href="{{ route('catalog.show', $product) }}" 
                                               class="flex-1 bg-gradient-to-r from-rose-100 to-pink-100 border-2 border-rose-300 text-rose-700 px-4 py-2.5 rounded-xl text-sm font-bold text-center hover:from-rose-200 hover:to-pink-200 hover:border-rose-400 shadow-sm">
                                                Подробнее
                                            </a>
                                            @auth
                                                @if(Auth::user()->role != 2 && Auth::user()->role != 3)
                                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" 
                                                                class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md">
                                                            Добавить в корзину
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" 
                                                            class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md">
                                                        Добавить в корзину
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <div class="mb-4">
                                        <svg class="mx-auto w-16 h-16 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-rose-700 text-xl font-semibold">Товары не найдены</p>
                                    <p class="text-gray-600 mt-2">Попробуйте изменить параметры поиска</p>
                                </div>
                            @endforelse
                        </div>

                        @if($products->hasPages())
                            <div class="mt-6 flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Показано {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} из {{ $products->total() }} товаров
                                </div>
                                <div class="flex items-center gap-2">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Режим отображения всех категорий -->
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

            <div class="space-y-8">
                @forelse ($categories as $category)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <!-- Заголовок категории -->
                        <div class="p-6 bg-gradient-to-r from-rose-100 to-pink-100 border-b-2 border-rose-200">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                                {{ $category->name_category }}
                            </h3>
                            <p class="text-sm text-rose-700 font-medium mt-2">
                                {{ $category->products_count }} {{ $category->products_count == 1 ? 'продукт' : ($category->products_count < 5 ? 'продукта' : 'продуктов') }}
                            </p>
                        </div>

                        <!-- Продукты категории -->
                        @if($category->products->count() > 0)
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($category->products as $product)
                                        <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-2xl overflow-hidden shadow-lg flex flex-col">
                                            <!-- Изображение продукта сверху -->
                                            <div class="relative w-full aspect-square bg-gradient-to-br from-peach-100 to-rose-100 overflow-hidden border-b-2 border-rose-200">
                                                @php
                                                    $allImages = $product->images;
                                                    $primaryImage = $allImages->where('is_primary', 1)->first() ?? $allImages->first();
                                                @endphp
                                                @if($primaryImage)
                                                    <a href="{{ route('catalog.show', $product) }}" class="block w-full h-full">
                                                        <img src="{{ asset($primaryImage->path) }}" 
                                                             alt="{{ $product->name_product }}" 
                                                             class="w-full h-full object-cover">
                                                    </a>
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Информация о продукте снизу -->
                                            <div class="p-5 flex flex-col flex-1">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                                    {{ $product->name_product }}
                                                </h3>
                                                
                                                <p class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-3">
                                                    {{ number_format($product->price, 2, '.', ' ') }} ₽
                                                </p>
                                                
                                                @if($product->description)
                                                    <p class="text-sm text-gray-700 mb-3 line-clamp-3 break-words whitespace-normal">
                                                        {{ $product->description }}
                                                    </p>
                                                @endif
                                                
                                                <!-- Кнопки действий -->
                                                <div class="flex gap-2 mt-auto pt-4 border-t-2 border-rose-200">
                                                    <a href="{{ route('catalog.show', $product) }}" 
                                                       class="flex-1 bg-white border-2 border-rose-300 text-rose-700 px-4 py-2.5 rounded-xl text-sm font-bold text-center hover:bg-rose-50 hover:border-rose-400 shadow-sm">
                                                        Подробнее
                                                    </a>
                                                    @auth
                                                        @if(Auth::user()->role != 2 && Auth::user()->role != 3)
                                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                                @csrf
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button type="submit" 
                                                                        class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md">
                                                                    Добавить в корзину
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit" 
                                                                    class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md">
                                                                Добавить в корзину
                                                            </button>
                                                        </form>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <svg class="mx-auto w-12 h-12 text-rose-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-rose-700 font-semibold">Продукты в этой категории отсутствуют</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <div class="p-12 text-center">
                            <svg class="mx-auto w-16 h-16 text-rose-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-rose-700 text-xl font-semibold">Категории не найдены</p>
                        </div>
                    </div>
                @endforelse
            </div>
            @endif
        </div>
    </div>

    <!-- Кастомная валидация формы фильтрации -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('catalog-filter-form');
            const priceMinInput = document.getElementById('price_min');
            const priceMaxInput = document.getElementById('price_max');
            const validationErrors = document.getElementById('validation-errors');
            const errorList = document.getElementById('error-list');

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

            // Функция валидации цены
            function validatePrice(value, fieldName) {
                if (value === '' || value === null) {
                    return { valid: true, value: null };
                }

                // Удаляем пробелы
                value = value.toString().trim();

                // Проверяем формат числа
                const numericValue = parseFloat(value);
                
                if (isNaN(numericValue)) {
                    return { 
                        valid: false, 
                        error: `${fieldName} должна быть числом` 
                    };
                }

                if (numericValue < 0) {
                    return { 
                        valid: false, 
                        error: `${fieldName} не может быть отрицательной` 
                    };
                }

                return { valid: true, value: numericValue };
            }

            // Обработка отправки формы
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                hideErrors();

                const errors = [];
                const priceMin = priceMinInput.value;
                const priceMax = priceMaxInput.value;

                // Валидация минимальной цены
                const minValidation = validatePrice(priceMin, 'Цена от');
                if (!minValidation.valid) {
                    errors.push(minValidation.error);
                    priceMinInput.classList.add('border-red-500');
                } else {
                    priceMinInput.classList.remove('border-red-500');
                }

                // Валидация максимальной цены
                const maxValidation = validatePrice(priceMax, 'Цена до');
                if (!maxValidation.valid) {
                    errors.push(maxValidation.error);
                    priceMaxInput.classList.add('border-red-500');
                } else {
                    priceMaxInput.classList.remove('border-red-500');
                }

                // Проверка соотношения цен
                if (minValidation.valid && maxValidation.valid && 
                    minValidation.value !== null && maxValidation.value !== null) {
                    if (minValidation.value > maxValidation.value) {
                        errors.push('Минимальная цена не может быть больше максимальной');
                        priceMinInput.classList.add('border-red-500');
                        priceMaxInput.classList.add('border-red-500');
                    }
                }

                // Если есть ошибки - показываем их
                if (errors.length > 0) {
                    showErrors(errors);
                    return false;
                }

                // Если валидация прошла - отправляем форму
                form.submit();
            });

            // Очистка ошибок при вводе
            priceMinInput.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                if (errorList.children.length > 0) {
                    hideErrors();
                }
            });

            priceMaxInput.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                if (errorList.children.length > 0) {
                    hideErrors();
                }
            });

            // Запрет ввода всего кроме цифр и точки (для цен с копейками)
            [priceMinInput, priceMaxInput].forEach(input => {
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
                    
                    // Разрешаем точку (только если её ещё нет в поле)
                    if (e.key === '.' && !this.value.includes('.')) {
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
                    
                    // Проверяем, что вставляемый текст содержит только цифры и возможно одну точку
                    if (!/^\d+\.?\d*$/.test(pastedText) || pastedText.includes('-')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</x-app-layout>