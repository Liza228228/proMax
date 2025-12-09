<nav x-data="{ open: false }" class="bg-gradient-to-r from-rose-100 via-peach-100 to-pink-100 border-b-2 border-rose-200 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('index') }}" class="text-2xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent hover:from-rose-700 hover:to-pink-700">
                        Fancy
                    </a>
                </div>

                <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        @auth
                            @if (Auth::user()->role === 2)
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                    {{ __('Панель администратора') }}
                                </x-nav-link>
                            @elseif (Auth::user()->role === 3)
                                <x-nav-link :href="route('manager.dashboard')" :active="request()->routeIs('manager.dashboard')">
                                    {{ __('Панель менеджера') }}
                                </x-nav-link>
                            @endif
                        @endauth
                    <!-- Каталог доступен всем -->
                    <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                        {{ __('Каталог') }}
                    </x-nav-link>
                    </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Корзина доступна только обычным пользователям -->
                @auth
                    @if(Auth::user()->role != 2 && Auth::user()->role != 3)
                        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg border-2 border-rose-300 bg-white hover:bg-rose-50 hover:border-rose-400 mr-4 shadow-sm">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            @php
                                $cartCount = \App\Models\Cart::getCartCount();
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-gradient-to-r from-rose-500 to-pink-500 rounded-full shadow-lg">{{ $cartCount }}</span>
                            @endif
                        </a>
                    @endif
                @else
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg border-2 border-rose-300 bg-white hover:bg-rose-50 hover:border-rose-400 mr-4 shadow-sm">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        @php
                            $cartCount = \App\Models\Cart::getCartCount();
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-gradient-to-r from-rose-500 to-pink-500 rounded-full shadow-lg">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endauth
                
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border-2 border-rose-300 bg-white text-sm leading-4 font-semibold rounded-lg text-rose-700 hover:bg-rose-50 hover:border-rose-400 focus:outline-none shadow-sm">
                                <div>{{ Auth::user()->first_name ?? Auth::user()->login }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 text-rose-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Профиль
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Выход
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-rose-700 hover:text-rose-900 px-4 py-2 rounded-lg hover:bg-rose-50 transition-colors">
                            {{ __('Войти') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 px-4 py-2 rounded-lg shadow-md">
                                {{ __('Регистрация') }}
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-rose-600 hover:text-rose-700 hover:bg-rose-50 focus:outline-none focus:bg-rose-50 focus:text-rose-700">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gradient-to-b from-rose-50 to-pink-50 border-t-2 border-rose-200">
            <div class="pt-2 pb-3 space-y-1">
                @auth
                    @if (Auth::user()->role === 2)
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Панель администратора') }}
                        </x-responsive-nav-link>
                    @elseif (Auth::user()->role === 3)
                        <x-responsive-nav-link :href="route('manager.dashboard')" :active="request()->routeIs('manager.dashboard')">
                            {{ __('Панель менеджера') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth
            <!-- Каталог доступен всем -->
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                {{ __('Каталог') }}
            </x-responsive-nav-link>
            <!-- Корзина доступна всем -->
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                {{ __('Корзина') }}
            </x-responsive-nav-link>
            </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t-2 border-rose-200">
            @auth
                <div class="px-4">
                    <div class="font-semibold text-base text-rose-700">{{ Auth::user()->first_name ?? Auth::user()->login }}</div>
                    <div class="font-medium text-sm text-rose-600">{{ Auth::user()->login }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Профиль
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Выход
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Войти') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Регистрация') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
