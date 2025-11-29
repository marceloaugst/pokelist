<nav x-data="{ open: false }" class="border-b border-slate-600 bg-gradient-to-r from-slate-700 to-slate-800 shadow-xl">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('pokemons.index') }}" class="group flex items-center gap-3">
                        <x-application-logo
                            class="block h-10 w-10 transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" />
                        <span
                            class="hidden bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 bg-clip-text text-xl font-bold text-transparent sm:block">Pokédex</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('pokemons.index') }}"
                        class="{{ request()->routeIs('pokemons.index') ? 'border-yellow-400 text-yellow-400' : 'border-transparent text-slate-300 hover:text-yellow-400 hover:border-yellow-400/50' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        {{ __('Meus Pokémons') }}
                    </a>
                    <a href="{{ route('pokemons.team') }}"
                        class="{{ request()->routeIs('pokemons.team') ? 'border-yellow-400 text-yellow-400' : 'border-transparent text-slate-300 hover:text-yellow-400 hover:border-yellow-400/50' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        {{ __('Meu Time') }}
                    </a>
                    <a href="{{ route('pokedex.index') }}"
                        class="{{ request()->routeIs('pokedex.*') ? 'border-yellow-400 text-yellow-400' : 'border-transparent text-slate-300 hover:text-yellow-400 hover:border-yellow-400/50' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out">
                        {{ __('Pokédex') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center rounded-lg border border-slate-600 bg-slate-800/50 px-3 py-2 text-sm font-medium leading-4 text-slate-300 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-white focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-yellow-400 transition duration-150 ease-in-out hover:bg-slate-700 hover:text-yellow-300 focus:bg-slate-700 focus:text-yellow-300 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden bg-slate-800 sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <a href="{{ route('pokemons.index') }}"
                class="{{ request()->routeIs('pokemons.index') ? 'border-yellow-400 text-yellow-400 bg-slate-700' : 'border-transparent text-slate-300 hover:text-yellow-400 hover:bg-slate-700 hover:border-yellow-400' }} block w-full border-l-4 py-2 pe-4 ps-3 text-start text-base font-medium transition duration-150 ease-in-out">
                {{ __('Meus Pokémons') }}
            </a>
            <a href="{{ route('pokemons.team') }}"
                class="{{ request()->routeIs('pokemons.team') ? 'border-yellow-400 text-yellow-400 bg-slate-700' : 'border-transparent text-slate-300 hover:text-yellow-400 hover:bg-slate-700 hover:border-yellow-400' }} block w-full border-l-4 py-2 pe-4 ps-3 text-start text-base font-medium transition duration-150 ease-in-out">
                {{ __('Meu Time') }}
            </a>
            <a href="{{ route('pokedex.index') }}"
                class="{{ request()->routeIs('pokedex.*') ? 'border-yellow-400 text-yellow-400 bg-slate-700' : 'border-transparent text-slate-300 hover:text-yellow-400 hover:bg-slate-700 hover:border-yellow-400' }} block w-full border-l-4 py-2 pe-4 ps-3 text-start text-base font-medium transition duration-150 ease-in-out">
                {{ __('Pokédex') }}
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-slate-700 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-medium text-slate-200">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-slate-300 transition duration-150 ease-in-out hover:border-yellow-400 hover:bg-slate-700 hover:text-yellow-400">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-red-400 transition duration-150 ease-in-out hover:border-red-400 hover:bg-slate-700 hover:text-red-300">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>