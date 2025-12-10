<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent leading-tight">
            {{ __('Создание новой категории') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-rose-50 via-peach-50 to-pink-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-2 border-rose-200">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.categories.store') }}" id="category-create-form" novalidate>
                        @csrf

                        <!-- Name Category -->
                        <div>
                            <x-input-label for="name_category" :value="__('Название категории')" />
                            <x-text-input id="name_category" class="block mt-1 w-full" type="text" name="name_category" :value="old('name_category')" autofocus />
                            <div id="name_category_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('name_category')" class="mt-2" />
                        </div>

                        <!-- Available -->
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="available" value="1" checked id="available" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Доступна') }}</span>
                            </label>
                            <div id="available_error" class="hidden mt-2 text-sm text-red-600 font-semibold"></div>
                            <x-input-error :messages="$errors->get('available')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.categories.index') }}" class="bg-white border-2 border-rose-300 text-rose-700 font-bold px-6 py-2 rounded-xl hover:bg-rose-50 shadow-sm mr-4">
                                {{ __('Отмена') }}
                            </a>
                            <x-primary-button>
                                {{ __('Создать категорию') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('category-create-form');
                            const nameCategoryInput = document.getElementById('name_category');
                            const availableCheckbox = document.getElementById('available');
                            
                            // Элементы для отображения ошибок под каждым полем
                            const nameCategoryError = document.getElementById('name_category_error');
                            const availableError = document.getElementById('available_error');

                            // Функция показа ошибки под полем
                            function showFieldError(errorElement, message) {
                                errorElement.textContent = message;
                                errorElement.classList.remove('hidden');
                            }

                            // Функция скрытия ошибки под полем
                            function hideFieldError(errorElement) {
                                errorElement.classList.add('hidden');
                                errorElement.textContent = '';
                            }

                            // Обработка отправки формы
                            form.addEventListener('submit', function(e) {
                                let hasErrors = false;
                                
                                // Проверка названия категории
                                if (!nameCategoryInput.value.trim()) {
                                    showFieldError(nameCategoryError, 'Поле "Название категории" обязательно для заполнения');
                                    nameCategoryInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else if (nameCategoryInput.value.trim().length > 100) {
                                    showFieldError(nameCategoryError, 'Поле "Название категории" не должно превышать 100 символов');
                                    nameCategoryInput.classList.add('border-red-500');
                                    hasErrors = true;
                                } else {
                                    hideFieldError(nameCategoryError);
                                    nameCategoryInput.classList.remove('border-red-500');
                                }

                                if (hasErrors) {
                                    e.preventDefault();
                                    // Прокрутка к первому полю с ошибкой
                                    nameCategoryInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    return false;
                                }
                            });

                            // Очистка ошибок при вводе
                            nameCategoryInput.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                hideFieldError(nameCategoryError);
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>










