<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Основная информация -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8">
                    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                        <div class="flex-1">
                            <h1 class="text-3xl lg:text-4xl font-bold mb-6 bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent">
                                Добро пожаловать в нашу кондитерскую
                            </h1>
                            <p class="text-base lg:text-lg text-gray-700 mb-5 leading-relaxed">
                                Мы создаем изысканные кондитерские изделия с любовью и вниманием к деталям. 
                                Наша кондитерская предлагает широкий ассортимент свежих тортов, пирожных, 
                                печенья и других сладостей, приготовленных из натуральных ингредиентов.
                            </p>
                        
                            <p class="text-base lg:text-lg text-gray-700 leading-relaxed">
                                Мы гордимся качеством нашей продукции и стремимся сделать каждый ваш 
                                день немного слаще!
                            </p>
                        </div>
                        <div class="w-full lg:w-[438px] shrink-0 rounded-xl overflow-hidden shadow-xl border-4 border-rose-300">
                            <img src="{{ asset('image/right.jpg') }}" alt="Кондитерская" class="w-full h-auto object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Новинки -->
            @if($featuredProducts->count() > 0)
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8">
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-bold mb-3 bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent">
                            Новинки
                        </h2>
                        <p class="text-gray-600 text-lg">
                            Попробуйте наши свежие кондитерские изделия
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($featuredProducts as $product)
                        <div class="group bg-gradient-to-br from-rose-50 to-pink-50 rounded-2xl shadow-lg overflow-hidden border-2 border-rose-200">
                            <!-- Изображение товара -->
                            <div class="relative w-full aspect-square bg-gradient-to-br from-peach-100 to-rose-100 overflow-hidden">
                                @php
                                    $image = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                @endphp
                                @if($image)
                                    <img src="{{ asset($image->path) }}" 
                                         alt="{{ $product->name_product }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Информация о товаре -->
                            <div class="p-5">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                    {{ $product->name_product }}
                                </h3>
                                
                                @if($product->category)
                                <p class="text-sm text-rose-600 font-medium mb-3">
                                    {{ $product->category->name_category }}
                                </p>
                                @endif

                                @if($product->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ $product->description }}
                                </p>
                                @endif

                                <div class="flex items-center justify-between pt-4 border-t-2 border-rose-200">
                                    <span class="text-2xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                                        {{ number_format($product->price, 0, '.', ' ') }} ₽
                                    </span>
                                    
                                    <a href="{{ route('catalog.show', $product->id) }}" 
                                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white text-sm font-bold rounded-xl shadow-md">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Кнопка "Смотреть весь каталог" -->
                    <div class="mt-10 text-center">
                        <a href="{{ route('catalog.index') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-lg text-lg">
                            Смотреть весь каталог
                            <svg class="ml-3 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>











