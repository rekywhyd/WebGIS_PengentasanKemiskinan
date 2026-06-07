<nav x-data="{ open: false }" class="bg-transparent border-none">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="font-outfit font-bold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 hidden sm:block">
                            WebGIS Kesra
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex font-medium font-outfit">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin')
                    <x-nav-link :href="route('ibadah.index')" :active="request()->routeIs('ibadah.index')">
                        {{ __('Daftar Tempat Ibadah') }}
                    </x-nav-link>
                    @endif
                    <x-nav-link :href="route('penerima.index')" :active="request()->routeIs('penerima.index')">
                        {{ __('Daftar Penerima Bantuan') }}
                    </x-nav-link>

                    @if(Auth::user()->role !== 'admin')
                    <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                        {{ __('Laporan Warga') }}
                    </x-nav-link>

                    <x-nav-link :href="route('scan.index')" :active="request()->routeIs('scan.index')">
                        {{ __('Scan Kupon') }}
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="route('riwayat.index')" :active="request()->routeIs('riwayat.*')">
                        {{ __('Riwayat Penyaluran') }}
                    </x-nav-link>


                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 font-outfit">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-slate-200/50 dark:border-slate-700/50 text-sm leading-4 font-medium rounded-xl text-slate-600 dark:text-slate-300 bg-white/50 dark:bg-slate-800/50 hover:bg-slate-50 hover:text-slate-800 dark:hover:bg-slate-800 dark:hover:text-slate-200 focus:outline-none transition ease-in-out duration-150 backdrop-blur-md shadow-sm">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-2 bg-slate-200/50 dark:bg-slate-700/50 rounded-full p-1">
                                <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
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

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-400 hover:bg-slate-100/50 dark:hover:bg-slate-800/50 focus:outline-none focus:bg-slate-100/50 dark:focus:bg-slate-800/50 focus:text-slate-500 dark:focus:text-slate-400 transition duration-150 ease-in-out backdrop-blur-sm border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 absolute w-full shadow-lg">
        <div class="pt-2 pb-3 space-y-1 font-outfit">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('penerima.index')" :active="request()->routeIs('penerima.index')">
                {{ __('Daftar Penerima Bantuan') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role !== 'admin')
            <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                {{ __('Laporan Warga') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('scan.index')" :active="request()->routeIs('scan.index')">
                {{ __('Scan Kupon') }}
            </x-responsive-nav-link>
            @endif
            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('ibadah.index')" :active="request()->routeIs('ibadah.index')">
                {{ __('Daftar Tempat Ibadah') }}
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('riwayat.index')" :active="request()->routeIs('riwayat.*')">
                {{ __('Riwayat Penyaluran') }}
            </x-responsive-nav-link>


        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200/50 dark:border-slate-700/50">
            <div class="px-4 pb-2">
                <div class="font-bold font-outfit text-base text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 font-outfit">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
