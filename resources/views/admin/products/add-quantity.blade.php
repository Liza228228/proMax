<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
                {{ __('Добавить количество: ') . $product->name_product }}
            </h2>
            <a href="{{ route('admin.products.show', $product) }}" class="text-rose-600 hover:text-rose-800 font-semibold">
                ← Назад к продукту
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.products.addQuantity.store', $product) }}" id="addQuantityForm">
                        @csrf

                        <!-- Информация о продукте -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                            <h3 class="text-lg font-bold text-rose-700 mb-3">Продукт: {{ $product->name_product }}</h3>
                            <div class="p-3 bg-white rounded-lg border-2 border-rose-200">
                                <p class="text-sm font-bold text-rose-700 mb-1">Текущее количество:</p>
                                <p class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                    {{ $product->total_quantity ?? 0 }} шт.
                                </p>
                            </div>
                        </div>

                        <!-- Количество для добавления -->
                        <div class="mb-6">
                            <x-input-label for="quantity" :value="__('Количество для добавления (шт.)')" class="text-rose-700 font-bold" />
                            <x-text-input id="quantity" 
                                         class="block mt-1 w-full rounded-xl border-2 border-rose-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500" 
                                         type="number" 
                                         name="quantity" 
                                         :value="old('quantity')" 
                                         min="1"
                                         step="1"
                                         required 
                                         autofocus
                                         oninput="updateIngredientsCalculation()" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <!-- Расчет ингредиентов -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-rose-700 mb-4">Ингредиенты, которые будут потрачены:</h3>
                            @if($recepts->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-rose-200">
                                        <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Ингредиент
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    На 1 шт.
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    Всего будет потрачено
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                                    На складе
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
                                                        {{ number_format($recept->display_quantity, 3, '.', ' ') }} {{ $recept->display_unit->code }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-rose-600" 
                                                        data-base-quantity="{{ $recept->quantity }}"
                                                        data-multiplier="{{ $recept->display_unit->multiplier_to_base }}"
                                                        data-unit-code="{{ $recept->display_unit->code }}"
                                                        id="total-{{ $recept->id }}">
                                                        <span class="calculated-value">0</span> <span class="unit-code">{{ $recept->display_unit->code }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="font-bold {{ $recept->stock_quantity_base > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ number_format($recept->stock_display_quantity, 3, '.', ' ') }} {{ $recept->display_unit->code }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-8 text-center bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border-2 border-rose-200">
                                    <p class="text-rose-600 font-semibold">Рецепт не указан</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t-2 border-rose-200">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="px-6 py-3 bg-white border-2 border-rose-300 text-rose-700 font-bold rounded-xl hover:bg-rose-50 shadow-sm">
                                Отмена
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-md">
                                Добавить количество
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateIngredientsCalculation() {
            const quantityInput = document.getElementById('quantity');
            const quantity = parseInt(quantityInput.value) || 0;
            
            // Обновляем расчет для каждого ингредиента
            document.querySelectorAll('[data-base-quantity]').forEach(function(cell) {
                const baseQuantity = parseFloat(cell.getAttribute('data-base-quantity'));
                const multiplier = parseFloat(cell.getAttribute('data-multiplier'));
                const totalBase = baseQuantity * quantity;
                const totalInDisplayUnit = multiplier > 0 ? totalBase / multiplier : totalBase;
                
                const valueSpan = cell.querySelector('.calculated-value');
                if (valueSpan) {
                    valueSpan.textContent = number_format(totalInDisplayUnit, 3, '.', ' ');
                }
            });
        }

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            updateIngredientsCalculation();
        });
    </script>
</x-app-layout>

