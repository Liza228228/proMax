<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                {{ __('Продукт: ') . $product->name_product }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="text-rose-600 hover:text-rose-800 font-semibold">
                ← Назад к списку
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Информация о продукте -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-xl font-bold text-rose-700 mb-6">Информация о продукте</h3>
                            
                            <!-- Изображения продукта -->
                            @if($product->images->count() > 0)
                                <div class="mb-6">
                                    @php
                                        $primaryImage = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                                        $allImages = $product->images;
                                    @endphp
                                    <div id="main-image-container" class="w-full h-96 bg-gradient-to-br from-peach-100 to-rose-100 rounded-xl border-2 border-rose-200 overflow-hidden flex items-center justify-center p-4">
                                        <img id="main-image" src="{{ asset($primaryImage->path) }}" 
                                             alt="{{ $product->name_product }}" 
                                             class="w-full h-full object-contain">
                                    </div>
                                    @if($product->images->count() > 1)
                                        <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                                            @foreach($allImages as $img)
                                                <button type="button" 
                                                        onclick="changeMainImage('{{ asset($img->path) }}', {{ $img->id }}, this)"
                                                        class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-peach-100 to-rose-100 rounded-lg border-2 border-rose-200 hover:border-rose-400 overflow-hidden transition-all image-thumbnail {{ $img->id == $primaryImage->id ? 'border-rose-500 shadow-md' : '' }} cursor-pointer"
                                                        data-image-id="{{ $img->id }}">
                                                    <img src="{{ asset($img->path) }}" alt="" class="w-full h-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                
                                <script>
                                    function changeMainImage(imagePath, imageId, clickedButton) {
                                        // Обновляем главное изображение
                                        document.getElementById('main-image').src = imagePath;
                                        
                                        // Обновляем активную миниатюру
                                        const thumbnails = document.querySelectorAll('.image-thumbnail');
                                        thumbnails.forEach(thumb => {
                                            thumb.classList.remove('border-rose-500', 'shadow-md');
                                            thumb.classList.add('border-rose-200');
                                        });
                                        
                                        // Активируем выбранную миниатюру
                                        if (clickedButton) {
                                            clickedButton.classList.remove('border-rose-200');
                                            clickedButton.classList.add('border-rose-500', 'shadow-md');
                                        }
                                    }
                                </script>
                            @endif
                            
                            <div class="p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200 space-y-4">
                                <div>
                                    <p class="text-xs font-bold text-rose-700 mb-1">Название</p>
                                    <p class="font-bold text-gray-900 break-words whitespace-normal">{{ $product->name_product }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-rose-700 mb-1">Категория</p>
                                    <p class="font-bold text-gray-900">{{ $product->category->name_category ?? 'Не указана' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-rose-700 mb-1">Цена</p>
                                    <p class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">
                                        {{ number_format($product->price, 2, '.', ' ') }} ₽
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-rose-700 mb-1">Количество</p>
                                    <p class="font-bold text-gray-900">{{ $product->total_quantity ?? 0 }} шт.</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-rose-700 mb-1">Срок годности</p>
                                    <p class="font-bold text-gray-900">{{ $product->expiration_date ?? 0 }} {{ $product->expiration_date == 1 ? 'день' : (in_array($product->expiration_date, [2, 3, 4]) ? 'дня' : 'дней') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-rose-700 mb-1">Статус</p>
                                    <p>
                                        @if ($product->available)
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-2 border-green-300">
                                                Доступен
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 border-2 border-gray-300">
                                                Недоступен
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6 space-y-3">
                                <a href="{{ route('admin.products.addQuantity', $product) }}" 
                                   class="block w-full text-center bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-4 py-3 rounded-xl shadow-md transition-colors">
                                    Добавить количество
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="block w-full text-center bg-white border-2 border-rose-300 text-rose-700 font-bold px-4 py-3 rounded-xl hover:bg-rose-50 shadow-sm transition-colors">
                                    Редактировать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Рецепт и описание -->
                <div class="lg:col-span-2 space-y-6">
                    @if($product->description)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-xl font-bold text-rose-700 mb-4">Описание</h3>
                                <div class="p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                    <p class="text-gray-700 leading-relaxed break-words whitespace-pre-wrap">{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Рецепт -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-xl font-bold text-rose-700 mb-6">Рецепт</h3>
                            @if($product->recepts->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-rose-200">
                                        <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Ингредиент
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Количество на 1 шт.
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-rose-200">
                                            @foreach($recepts as $recept)
                                                <tr class="hover:bg-rose-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $recept->ingredient->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                        {{ number_format($recept->display_quantity, 3, '.', ' ') }} {{ $recept->display_unit->name ?? 'ед.' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-8 text-center">
                                    <p class="text-rose-600 font-semibold">Рецепт не указан</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Текущие запасы -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                        <div class="p-6 text-gray-900">
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-bold text-rose-700">Текущие запасы</h3>
                                </div>
                                @php
                                    $stockProducts = $product->stockProducts()
                                        ->orderBy('expiration_date', 'asc')
                                        ->orderBy('created_at', 'asc')
                                        ->get();
                                    
                                    // Группируем по дате срока годности и суммируем количество
                                    $groupedStocks = $stockProducts->groupBy(function($stock) {
                                        return \Carbon\Carbon::parse($stock->expiration_date)->format('Y-m-d');
                                    })->map(function($group) {
                                        return [
                                            'expiration_date' => \Carbon\Carbon::parse($group->first()->expiration_date),
                                            'quantity' => $group->sum('quantity'),
                                            'stocks' => $group
                                        ];
                                    })->sortBy('expiration_date');
                                @endphp
                                @if($groupedStocks->count() > 0)
                                    <div class="flex gap-3 items-center">
                                        <select id="stock-filter" class="rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white text-gray-900 font-medium transition-all">
                                            <option value="active">Актуальные</option>
                                            <option value="all">Все</option>
                                            <option value="expired">Просроченные</option>
                                        </select>
                                        <button type="button" id="apply-filter" class="px-6 py-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-md transition-colors">
                                            Применить
                                        </button>
                                        <button type="button" id="reset-filter" class="px-6 py-2 border-2 border-rose-300 text-rose-700 font-bold rounded-xl hover:bg-rose-50 shadow-sm transition-colors">
                                            Сбросить
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @if($groupedStocks->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-rose-200">
                                        <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Количество
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Срок годности
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Статус
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="stock-tbody" class="bg-white divide-y divide-rose-200">
                                            @foreach($groupedStocks as $index => $groupedStock)
                                                @php
                                                    $expirationDate = $groupedStock['expiration_date'];
                                                    $isExpired = $expirationDate->isPast();
                                                    $daysUntilExpiration = \Carbon\Carbon::today()->diffInDays($expirationDate, false);
                                                    $isExpiringSoon = !$isExpired && $daysUntilExpiration >= 0 && $daysUntilExpiration <= 2;
                                                @endphp
                                                <tr class="stock-row hover:bg-rose-50 {{ $isExpired ? 'bg-red-50 stock-expired' : ($isExpiringSoon ? 'bg-yellow-50 stock-active' : 'stock-active') }}" 
                                                    data-status="{{ $isExpired ? 'expired' : 'active' }}"
                                                    data-index="{{ $index }}">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $groupedStock['quantity'] }} шт.
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                        {{ $expirationDate->format('d.m.Y') }}
                                                        @if($isExpiringSoon)
                                                            <span class="ml-2 inline-block px-2 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-300">
                                                                 Истекает через {{ $daysUntilExpiration }} {{ $daysUntilExpiration == 1 ? 'день' : ($daysUntilExpiration == 2 ? 'дня' : 'дней') }}
                                                            </span>
                                                        @elseif($isExpired)
                                                            <span class="ml-2 inline-block px-2 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-300">
                                                                Просрочено
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        @if($isExpired)
                                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border-2 border-red-300">
                                                                Просрочено
                                                            </span>
                                                        @elseif($isExpiringSoon)
                                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-2 border-yellow-300">
                                                                Истекает скоро
                                                            </span>
                                                        @else
                                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-2 border-green-300">
                                                                Активна
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Пагинация -->
                                <div id="pagination-container" class="mt-4 flex justify-center items-center gap-2"></div>
                            @else
                                <div class="p-8 text-center bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                    <p class="text-rose-600 font-semibold">Партии продукции отсутствуют</p>
                                    <p class="text-sm text-gray-600 mt-2">Добавьте количество продукции через кнопку "Добавить количество"</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- JavaScript для фильтрации и пагинации запасов -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const filterSelect = document.getElementById('stock-filter');
                            const applyButton = document.getElementById('apply-filter');
                            const resetButton = document.getElementById('reset-filter');
                            const paginationContainer = document.getElementById('pagination-container');
                            
                            if (!filterSelect) return;
                            
                            const stockRows = Array.from(document.querySelectorAll('.stock-row'));
                            const itemsPerPage = 5;
                            let currentFilter = 'active';
                            let currentPage = 1;
                            let filteredRows = [];
                            
                            // Функция фильтрации
                            function filterStocks(filterValue) {
                                currentFilter = filterValue;
                                currentPage = 1;
                                
                                filteredRows = stockRows.filter(row => {
                                    const status = row.getAttribute('data-status');
                                    
                                    if (filterValue === 'all') {
                                        return true;
                                    } else if (filterValue === 'expired') {
                                        return status === 'expired';
                                    } else if (filterValue === 'active') {
                                        return status === 'active';
                                    }
                                    return false;
                                });
                                
                                displayPage();
                            }
                            
                            // Функция отображения страницы
                            function displayPage() {
                                // Скрываем все строки
                                stockRows.forEach(row => {
                                    row.style.display = 'none';
                                });
                                
                                // Вычисляем диапазон для текущей страницы
                                const startIndex = (currentPage - 1) * itemsPerPage;
                                const endIndex = startIndex + itemsPerPage;
                                const rowsToShow = filteredRows.slice(startIndex, endIndex);
                                
                                // Показываем строки текущей страницы
                                rowsToShow.forEach(row => {
                                    row.style.display = '';
                                });
                                
                                // Обновляем пагинацию
                                updatePagination();
                            }
                            
                            // Функция обновления пагинации
                            function updatePagination() {
                                const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
                                
                                if (totalPages <= 1) {
                                    paginationContainer.innerHTML = '';
                                    return;
                                }
                                
                                let paginationHTML = '';
                                
                                // Кнопка "Назад"
                                if (currentPage > 1) {
                                    paginationHTML += `
                                        <button type="button" onclick="goToPage(${currentPage - 1})" 
                                                class="px-4 py-2 border-2 border-rose-300 text-rose-700 font-bold rounded-xl hover:bg-rose-50 shadow-sm transition-colors">
                                            ←
                                        </button>
                                    `;
                                }
                                
                                // Номера страниц
                                for (let i = 1; i <= totalPages; i++) {
                                    if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                                        paginationHTML += `
                                            <button type="button" onclick="goToPage(${i})" 
                                                    class="px-4 py-2 ${i === currentPage ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white' : 'border-2 border-rose-300 text-rose-700 hover:bg-rose-50'} font-bold rounded-xl shadow-sm transition-colors">
                                                ${i}
                                            </button>
                                        `;
                                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                                        paginationHTML += `<span class="px-2 text-rose-700">...</span>`;
                                    }
                                }
                                
                                // Кнопка "Вперед"
                                if (currentPage < totalPages) {
                                    paginationHTML += `
                                        <button type="button" onclick="goToPage(${currentPage + 1})" 
                                                class="px-4 py-2 border-2 border-rose-300 text-rose-700 font-bold rounded-xl hover:bg-rose-50 shadow-sm transition-colors">
                                            →
                                        </button>
                                    `;
                                }
                                
                                paginationContainer.innerHTML = paginationHTML;
                            }
                            
                            // Функция перехода на страницу
                            window.goToPage = function(page) {
                                currentPage = page;
                                displayPage();
                                // Прокрутка к началу таблицы
                                document.getElementById('stock-tbody').scrollIntoView({ behavior: 'smooth', block: 'start' });
                            };
                            
                            // Обработчик кнопки "Применить"
                            applyButton.addEventListener('click', function() {
                                filterStocks(filterSelect.value);
                            });
                            
                            // Обработчик кнопки "Сбросить"
                            resetButton.addEventListener('click', function() {
                                filterSelect.value = 'active';
                                filterStocks('active');
                            });
                            
                            // Инициализация: скрываем просроченные по умолчанию
                            filterStocks('active');
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

