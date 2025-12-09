<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            Панель управления
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8">
                    <div class="text-center">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">
                            Добро пожаловать!
                        </h3>
                        <p class="text-xl text-rose-700 font-semibold mb-6">
                            Вы успешно вошли в систему!
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                            <a href="{{ route('catalog.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-lg">
                                Перейти в каталог
                            </a>
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center px-6 py-3 border-2 border-rose-300 text-rose-700 font-bold rounded-xl hover:bg-rose-50 shadow-md">
                                Мой профиль
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
