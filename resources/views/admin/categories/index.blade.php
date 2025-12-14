<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Управление категориями') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl shadow-lg" role="alert">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <!-- Форма поиска и фильтрации -->
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-6 bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-xl border-2 border-rose-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Поиск -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-semibold text-rose-700 mb-2">Поиск</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Название категории..."
                                       class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            </div>

                            <!-- Фильтр по доступности -->
                            <div>
                                <label for="available" class="block text-sm font-semibold text-rose-700 mb-2">Доступность</label>
                                <select id="available" 
                                        name="available" 
                                        class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                    <option value="">Все</option>
                                    <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Доступна</option>
                                    <option value="0" {{ request('available') == '0' ? 'selected' : '' }}>Недоступна</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                Применить фильтры
                            </button>
                            <a href="{{ route('admin.categories.index') }}" 
                               class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm">
                                Сбросить
                            </a>
                        </div>
                    </form>

                    <div class="mb-6 flex justify-end">
                        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Добавить категорию
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rose-200">
                            <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Название категории
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Статус
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-rose-200">
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-rose-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $category->name_category }}
                                            <span class="ml-2 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-300">
                                                {{ $category->products_count }} {{ $category->products_count == 1 ? 'продукт' : ($category->products_count < 5 ? 'продукта' : 'продуктов') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($category->available)
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border-2 border-green-300">
                                                    Доступна
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 border-2 border-gray-300">
                                                    Недоступна
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-rose-600 hover:text-rose-800 font-semibold mr-4">
                                                Редактировать
                                            </a>
                                            @if($category->products_count > 0)
                                                <span class="text-gray-400  text-sm" title="В категории есть продукты">
                                                    Нельзя удалить
                                                </span>
                                            @else
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                                        Удалить
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-rose-600 font-semibold">
                                            Категории не найдены
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    @if($categories->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Показано {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} из {{ $categories->total() }} категорий
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $categories->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>










