<nav x-data="{ open: false }" class="bg-dark-theme color-light-theme border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Лого -->
                <div class="shrink-0 flex items-center me-4">
                    <img class='me-4' src="/images/favicon-32.png" alt="Лого"> <span class='font-bold'>Helpdesk</span>
                </div>
                <!-- Главная -->
                @if(route('index') == url()->current())
                    <div class="h-full main-link shrink-0 flex items-center px-2 w-32 flex justify-center">Главная</div>
                @else
                    <a href="{{ route('index') }}">
                        <div class="h-full main-link shrink-0 flex items-center px-2 w-32 flex justify-center">Главная</div>
                    </a>
                @endif
                
                @if(Auth::user()->role->name !== 'author')
                    <!-- Статистика -->
                    @if(route('statistic') == url()->current())
                        <div class="h-full main-link shrink-0 flex items-center px-2 w-32 flex justify-center" disabled>Статистика</div>
                    @else
                        <a href="{{route('statistic')}}">
                            <div class="h-full main-link shrink-0 flex items-center px-2 w-32 flex justify-center">Статистика</div>
                        </a>
                    @endif
                    <!-- Настройки -->
                    @if(route('settings') == url()->current())
                        <div class="h-full main-link shrink-0 flex items-center px-2 w-32 flex justify-center">Настройки</div>
                    @else
                        <a href="{{route('settings')}}">
                            <div class="h-full main-link shrink-0 flex items-center px-2 w-32 flex justify-center">Настройки</div>
                        </a>
                    @endif
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium focus:outline-none transition duration-150 ease-in-out color-theme ">
                            <div class='color-light-theme'>{{ Auth::user()->short_full_name }}</div>

                            <div class="ml-1 color-light-theme">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                            <x-dropdown-link :href="route('profile')">Профиль</x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Выйти
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')">
            Профиль
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Выйти
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
