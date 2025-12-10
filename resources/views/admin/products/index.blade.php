<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Управление продукцией') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <!-- Форма поиска и фильтрации -->
                    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6 bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-xl border-2 border-rose-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <!-- Поиск -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-semibold text-rose-700 mb-2">Поиск</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Название, описание..."
                                       class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            </div>

                            <!-- Фильтр по категории -->
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-rose-700 mb-2">Категория</label>
                                <select id="category_id" 
                                        name="category_id" 
                                        class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                    <option value="">Все категории</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Фильтр по доступности -->
                            <div>
                                <label for="available" class="block text-sm font-semibold text-rose-700 mb-2">Доступность</label>
                                <select id="available" 
                                        name="available" 
                                        class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                    <option value="">Все</option>
                                    <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Доступен</option>
                                    <option value="0" {{ request('available') == '0' ? 'selected' : '' }}>Недоступен</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                Применить фильтры
                            </button>
                            <a href="{{ route('admin.products.index') }}" 
                               class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm">
                                Сбросить
                            </a>
                        </div>
                    </form>

                    <div class="mb-6 flex justify-end">
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Добавить продукт
                        </a>
                    </div>

                    <!-- Сетка продуктов -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($products as $product)
                            <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-2xl overflow-hidden shadow-lg">
                                <!-- Изображения в одну строчку -->
                                <div class="p-3 bg-gradient-to-br from-peach-100 to-rose-100">
                                    @php
                                        $allImages = $product->images;
                                    @endphp
                                    @if($allImages->count() > 0)
                                        <div class="product-images-container relative" data-product-id="{{ $product->id }}">
                                            <div class="flex flex-nowrap gap-2 overflow-x-auto pb-2" style="scrollbar-width: thin;">
                                                @foreach($allImages as $index => $img)
                                                    <div class="flex-shrink-0 relative">
                                                        <img src="{{ asset($img->path) }}" 
                                                             alt="{{ $product->name_product }}" 
                                                             class="w-20 h-20 object-cover rounded border-2 {{ $img->is_primary ? 'border-blue-500' : 'border-gray-300' }} cursor-pointer"
                                                             onclick="showFullImage({{ $product->id }}, {{ $index }})"
                                                             data-index="{{ $index }}">
                                                        @if($img->is_primary)
                                                            <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs px-1 rounded">
                                                                Главное
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            @if($allImages->count() > 1)
                                                <!-- Стрелки навигации -->
                                                <button class="carousel-prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75 z-10" 
                                                        onclick="scrollImages({{ $product->id }}, -1)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                    </svg>
                                                </button>
                                                <button class="carousel-next absolute right-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-1 rounded hover:bg-opacity-75 z-10" 
                                                        onclick="scrollImages({{ $product->id }}, 1)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <!-- Модальное окно для просмотра большого изображения -->
                                        <div id="imageModal-{{ $product->id }}" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center" onclick="closeImageModal({{ $product->id }})">
                                            <div class="relative max-w-4xl max-h-full p-4" onclick="event.stopPropagation()">
                                                <button onclick="closeImageModal({{ $product->id }})" class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                                <div class="relative">
                                                    @foreach($allImages as $index => $img)
                                                        <img src="{{ asset($img->path) }}" 
                                                             alt="{{ $product->name_product }}" 
                                                             class="max-w-full max-h-[90vh] object-contain {{ $index === 0 ? '' : 'hidden' }}"
                                                             id="modal-image-{{ $product->id }}-{{ $index }}">
                                                    @endforeach
                                                    @if($allImages->count() > 1)
                                                        <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 text-black p-2 rounded-full hover:bg-opacity-75" 
                                                                onclick="changeModalImage({{ $product->id }}, -1); event.stopPropagation();">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                            </svg>
                                                        </button>
                                                        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 text-black p-2 rounded-full hover:bg-opacity-75" 
                                                                onclick="changeModalImage({{ $product->id }}, 1); event.stopPropagation();">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-20">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Информация -->
                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                                        {{ $product->name_product }}
                                    </h3>
                                    <p class="text-sm text-rose-700 font-semibold mb-2">
                                        Категория: {{ $product->category->name_category ?? 'Не указана' }}
                                    </p>
                                    <p class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-2">
                                        {{ number_format($product->price, 2, '.', ' ') }} ₽
                                    </p>
                                    <p class="text-sm text-gray-700 font-medium mb-3">
                                        Количество: {{ $product->total_quantity ?? 0 }}
                                    </p>
                                    
                                    <!-- Статус -->
                                    <div class="mb-3 flex flex-wrap gap-2">
                                        @if ($product->available)
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-2 border-green-300">
                                                Доступен
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 border-2 border-gray-300">
                                                Недоступен
                                            </span>
                                        @endif
                                        
                                        @if ($product->isExpired())
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border-2 border-red-300">
                                                Срок годности истек
                                            </span>
                                        @elseif ($product->isExpiringSoon())
                                            @php
                                                $daysLeft = $product->getDaysUntilExpiration();
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300">
                                                 Истекает через {{ $daysLeft }} {{ $daysLeft == 1 ? 'день' : ($daysLeft == 2 ? 'дня' : 'дней') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Действия -->
                                    <div class="flex flex-col gap-2 mt-3">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="text-center bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-bold px-4 py-2 rounded-xl shadow-md">
                                            Просмотр
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-center bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-4 py-2 rounded-xl shadow-md">
                                            Редактировать
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" 
                                              method="POST" 
                                              class="flex-1"
                                              onsubmit="return confirm('Вы уверены, что хотите удалить этот продукт?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-xl shadow-md">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-rose-700 text-xl font-semibold">Продукты не найдены</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Пагинация -->
                    @if($products->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Показано {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} из {{ $products->total() }} продуктов
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $products->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const modalImageData = {};
        
        @foreach($products as $product)
            @if($product->images->count() > 0)
                modalImageData[{{ $product->id }}] = {
                    current: 0,
                    total: {{ $product->images->count() }}
                };
            @endif
        @endforeach

        function scrollImages(productId, direction) {
            const container = document.querySelector(`.product-images-container[data-product-id="${productId}"] .flex`);
            if (!container) return;
            
            const scrollAmount = 100; // ширина фото + gap
            container.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        function showFullImage(productId, index) {
            if (!modalImageData[productId]) return;
            
            modalImageData[productId].current = index;
            const modal = document.getElementById(`imageModal-${productId}`);
            if (modal) {
                modal.classList.remove('hidden');
                updateModalImage(productId);
            }
        }

        function closeImageModal(productId) {
            const modal = document.getElementById(`imageModal-${productId}`);
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function changeModalImage(productId, direction) {
            if (!modalImageData[productId]) return;
            
            const data = modalImageData[productId];
            data.current += direction;
            
            if (data.current < 0) data.current = data.total - 1;
            if (data.current >= data.total) data.current = 0;
            
            updateModalImage(productId);
        }

        function updateModalImage(productId) {
            if (!modalImageData[productId]) return;
            
            const data = modalImageData[productId];
            const total = data.total;
            const current = data.current;
            
            // Скрываем все изображения
            for (let i = 0; i < total; i++) {
                const img = document.getElementById(`modal-image-${productId}-${i}`);
                if (img) {
                    img.classList.toggle('hidden', i !== current);
                }
            }
        }

        // Закрытие модального окна по Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="imageModal-"]').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        const productId = modal.id.replace('imageModal-', '');
                        closeImageModal(productId);
                    }
                });
            }
        });
    </script>
</x-app-layout>

