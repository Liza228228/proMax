<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Управление пользователями') }}
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
                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-xl border-2 border-rose-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Поиск -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-semibold text-rose-700 mb-2">Поиск</label>
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Фамилия, имя, логин, телефон..."
                                       class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                            </div>

                            <!-- Фильтр по роли -->
                            <div>
                                <label for="role" class="block text-sm font-semibold text-rose-700 mb-2">Роль</label>
                                <select id="role" 
                                        name="role" 
                                        class="block w-full px-4 py-2 border-2 border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm">
                                    <option value="">Все роли</option>
                                    <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Пользователь</option>
                                    <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Администратор</option>
                                    <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>Менеджер</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2 rounded-xl shadow-md">
                                Применить фильтры
                            </button>
                            <a href="{{ route('admin.users.index') }}" 
                               class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm">
                                Сбросить
                            </a>
                        </div>
                    </form>

                    <div class="mb-6 flex justify-end">
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Добавить пользователя
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rose-200">
                            <thead class="bg-gradient-to-r from-rose-100 to-pink-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Фамилия
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Имя
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Телефон
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Логин
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Роль
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-rose-700 uppercase tracking-wider">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-rose-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-rose-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $user->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $user->first_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            @php
                                                $phone = $user->phone ?? '';
                                                // Убираем все нецифровые символы
                                                $phoneDigits = preg_replace('/\D/', '', $phone);
                                                
                                                // Если номер начинается с 8, заменяем на 7
                                                if (strlen($phoneDigits) == 11 && $phoneDigits[0] == '8') {
                                                    $phoneDigits = '7' . substr($phoneDigits, 1);
                                                }
                                                
                                                // Если номер начинается с 7 и имеет 11 цифр, форматируем
                                                if (strlen($phoneDigits) == 11 && $phoneDigits[0] == '7') {
                                                    $formatted = '+7 (' . substr($phoneDigits, 1, 3) . ') ' . substr($phoneDigits, 4, 3) . '-' . substr($phoneDigits, 7, 2) . '-' . substr($phoneDigits, 9, 2);
                                                } elseif (strlen($phoneDigits) == 10) {
                                                    // Если 10 цифр, добавляем 7
                                                    $formatted = '+7 (' . substr($phoneDigits, 0, 3) . ') ' . substr($phoneDigits, 3, 3) . '-' . substr($phoneDigits, 6, 2) . '-' . substr($phoneDigits, 8, 2);
                                                } else {
                                                    // Если формат не распознан, показываем как есть
                                                    $formatted = $phone;
                                                }
                                            @endphp
                                            {{ $formatted }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $user->login }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($user->role === 2)
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border-2 border-red-300">
                                                    Администратор
                                                </span>
                                            @elseif ($user->role === 3)
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-gradient-to-r from-yellow-50 to-amber-50 text-yellow-800 border-2 border-yellow-300">
                                                    Менеджер
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border-2 border-blue-300">
                                                    Пользователь
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($user->role != 1)
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-rose-600 hover:text-rose-800 font-semibold mr-4">
                                                    Редактировать
                                                </a>
                                            @endif
                                            @php
                                                $currentUser = Auth::user();
                                                $isCurrentUser = $currentUser && (int)$currentUser->id === (int)$user->id;
                                                $isRegularUser = $user->role == 1;
                                                $canDelete = !$isCurrentUser && !$isRegularUser;
                                            @endphp
                                            @if($canDelete)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
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
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-rose-600 font-semibold">
                                            Пользователи не найдены
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    @if($users->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Показано {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} из {{ $users->total() }} пользователей
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $users->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

