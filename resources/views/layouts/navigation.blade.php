<nav x-data="{ open: false }" class="bg-black border-b border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left -->
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto fill-current text-yellow-400" />
                    <span class="text-white font-semibold tracking-wide hidden sm:block">
                        Imperial Tuitions
                    </span>
                </a>

                <!-- Desktop Links -->
                <div class="hidden sm:flex gap-6">
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium px-2 py-1 border-b-2
                       {{ request()->routeIs('dashboard')
                            ? 'border-yellow-400 text-yellow-400'
                            : 'border-transparent text-gray-300 hover:text-yellow-400 hover:border-yellow-400' }}">
                        
                    </a>
                </div>
            </div>

            <!-- Right -->
            <div class="hidden sm:flex sm:items-center">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-md
                            text-gray-300 hover:text-yellow-400
                            focus:outline-none transition">
                            <span class="text-sm font-medium">
                                {{ Auth::user()->name }}
                            </span>

                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293
                                      a1 1 0 111.414 1.414l-4 4a1 1 0
                                      01-1.414 0l-4-4a1 1 0
                                      010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-gray-300 hover:text-yellow-400 px-3 py-2">
                        Log in
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-md text-gray-300 hover:text-yellow-400
                               hover:bg-gray-900 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="sm:hidden bg-black border-t border-gray-800">
        <div class="px-4 pt-4 pb-2 space-y-2">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-900 hover:text-yellow-400">
                Dashboard
            </a>
        </div>

        <div class="border-t border-gray-800 px-4 py-4">
            @auth
            <div class="text-sm text-white font-medium">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-2">
                <a href="{{ route('profile.edit') }}"
                   class="block text-gray-300 hover:text-yellow-400">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-left w-full text-gray-300 hover:text-red-400">
                        Log Out
                    </button>
                </form>
            </div>
            @else
            <a href="{{ route('login') }}"
               class="block text-gray-300 hover:text-yellow-400">
                Log in
            </a>
            @endauth
        </div>
    </div>
</nav>
