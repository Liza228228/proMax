<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Панель менеджера') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8">
                    <div class="mb-6 text-center">
                        <p class="text-2xl font-bold text-gray-900">Добро пожаловать, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</p>
                        <p class="text-lg text-rose-700 font-semibold mt-2">Вы вошли как менеджер</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <a href="{{ route('manager.orders.index') }}" class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 p-6 rounded-2xl hover:shadow-xl cursor-pointer">
                            <h3 class="font-bold text-lg text-rose-700 mb-2">Управление заказами</h3>
                            <p class="text-sm text-gray-700 font-medium">Обработка и отслеживание заказов</p>
                        </a>
                        <a href="{{ route('manager.warehouses.index') }}" class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 p-6 rounded-2xl hover:shadow-xl cursor-pointer">
                            <h3 class="font-bold text-lg text-rose-700 mb-2">Управление складами</h3>
                            <p class="text-sm text-gray-700 font-medium">Управление складами и ингредиентами</p>
                        </a>
                        <a href="{{ route('manager.ingredients.index') }}" class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 p-6 rounded-2xl hover:shadow-xl cursor-pointer">
                            <h3 class="font-bold text-lg text-rose-700 mb-2">Управление ингредиентами</h3>
                            <p class="text-sm text-gray-700 font-medium">Просмотр и анализ списка ингредиентов</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

