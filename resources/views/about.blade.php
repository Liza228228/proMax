<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            О нас
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-8 md:p-12">
                    <!-- Заголовок -->
                    <div class="text-center mb-8">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-4">
                            Добро пожаловать в кондитерскую 
                        </h1>
                        <p class="text-lg text-gray-700 font-medium">
                            Мы создаем сладости с любовью и вниманием к деталям
                        </p>
                    </div>

                    <!-- О нас -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-rose-700 mb-4">О нашей кондитерской</h2>
                        <div class="prose max-w-none text-gray-700">
                            <p class="mb-4 text-base leading-relaxed">
                                Мы специализируемся на создании изысканных кондитерских изделий, которые 
                                радуют не только вкусом, но и внешним видом.
                            </p>
                            <p class="mb-4 text-base leading-relaxed">
                                Наша команда опытных кондитеров использует только натуральные ингредиенты 
                                высочайшего качества. Каждое изделие создается вручную с особой заботой 
                                и вниманием к деталям.
                            </p>
                            <p class="text-base leading-relaxed">
                                Мы гордимся тем, что можем предложить широкий ассортимент тортов, пирожных, 
                                десертов и других сладостей на любой вкус. От классических рецептов до 
                                современных авторских десертов — у нас найдется что-то особенное для каждого.
                            </p>
                        </div>
                    </div>

                    <!-- Расписание работы -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-rose-700 mb-4">Режим работы</h2>
                        <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-xl p-6">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Понедельник - Пятница:</span>
                                    <span class="text-rose-700 font-bold">09:00 - 20:00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Суббота:</span>
                                    <span class="text-rose-700 font-bold">10:00 - 19:00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Воскресенье:</span>
                                    <span class="text-rose-700 font-bold">10:00 - 18:00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Контакты -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-rose-700 mb-4">Контакты</h2>
                        <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-200 rounded-xl p-6">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium">г. Иркутск, ул. Ленина, д. 5а</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium">+7 (999) 123-456</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка назад -->
                    <div class="text-center mt-8">
                        <a href="{{ route('index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-md">
                            Вернуться на главную
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

