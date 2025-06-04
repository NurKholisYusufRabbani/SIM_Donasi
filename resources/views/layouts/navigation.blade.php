{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="self-center text-xs font-semibold whitespace-nowrap dark:text-white">Sistem Informasi Manajemen Donasi</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Link untuk ADMIN --}}
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index') || request()->routeIs('users.show') || request()->routeIs('users.edit')">
                            {{ __('Pengguna') }} {{-- [cite: 110] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('donors.index')" :active="request()->routeIs('donors.index') || request()->routeIs('donors.show') || request()->routeIs('donors.edit') ">
                            {{ __('Donatur') }} {{-- [cite: 46] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index') || request()->routeIs('donations.create') || request()->routeIs('donations.show') || request()->routeIs('donations.edit')">
                            {{ __('Donasi') }} {{-- [cite: 23] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('distributions.index')" :active="request()->routeIs('distributions.index') || request()->routeIs('distributions.create') || request()->routeIs('distributions.show') || request()->routeIs('distributions.edit')">
                            {{ __('Distribusi') }} {{-- [cite: 71] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('beneficiaries.index')" :active="request()->routeIs('beneficiaries.index') || request()->routeIs('beneficiaries.create') || request()->routeIs('beneficiaries.show') || request()->routeIs('beneficiaries.edit')">
                            {{ __('Penerima') }} {{-- [cite: 93] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index') || request()->routeIs('reports.donations.summary') || request()->routeIs('reports.distributions.summary')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endif

                    {{-- Link untuk DONATUR --}}
                    @if(Auth::user()->role === 'donator')
                        @if(Auth::user()->donor) {{-- Hanya jika sudah punya profil donor --}}
                            <x-nav-link :href="route('donors.show', Auth::user()->donor->id)" :active="request()->routeIs('donors.show', Auth::user()->donor->id) || request()->routeIs('donors.edit', Auth::user()->donor->id)">
                                {{ __('Profil Donatur Saya') }}
                            </x-nav-link>
                        @else {{-- Jika belum punya profil, link ke create --}}
                            <x-nav-link :href="route('donors.create')" :active="request()->routeIs('donors.create')">
                                {{ __('Lengkapi Profil Donatur') }} {{-- [cite: 53] --}}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('donations.create')" :active="request()->routeIs('donations.create')">
                            {{ __('Ajukan Donasi') }} {{-- [cite: 31] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index') && !request()->routeIs('donations.create')">
                            {{ __('Riwayat Donasi Saya') }} {{-- [cite: 23] --}}
                        </x-nav-link>
                    @endif

                    {{-- Link untuk PETUGAS DISTRIBUSI --}}
                    @if(Auth::user()->role === 'distributor')
                        <x-nav-link :href="route('distributions.create')" :active="request()->routeIs('distributions.create')">
                            {{ __('Catat Distribusi') }} {{-- [cite: 77] --}}
                        </x-nav-link>
                        <x-nav-link :href="route('distributions.index')" :active="request()->routeIs('distributions.index') && !request()->routeIs('distributions.create')">
                            {{ __('Riwayat Distribusi Saya') }} {{-- [cite: 71] --}}
                        </x-nav-link>
                    @endif

                     {{-- Link untuk PENGGUNA BIASA (belum jadi donatur/distributor) --}}
                    @if(Auth::user()->role === 'user')
                        @if(!Auth::user()->donor) {{-- Jika belum punya profil donor --}}
                            <x-nav-link :href="route('donors.create')" :active="request()->routeIs('donors.create')">
                                {{ __('Jadi Donatur') }} {{-- [cite: 53] --}}
                            </x-nav-link>
                        @endif
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->username }} ({{ ucfirst(Auth::user()->role) }})</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Edit Profil Akun') }}
                        </x-dropdown-link>

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

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Responsive Links untuk ADMIN --}}
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    {{ __('Pengguna') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('donors.index')" :active="request()->routeIs('donors.index')">
                    {{ __('Donatur') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index')">
                    {{ __('Donasi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('distributions.index')" :active="request()->routeIs('distributions.index')">
                    {{ __('Distribusi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('beneficiaries.index')" :active="request()->routeIs('beneficiaries.index')">
                    {{ __('Penerima') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @endif

            {{-- Responsive Links untuk DONATUR --}}
            @if(Auth::user()->role === 'donator')
                 @if(Auth::user()->donor)
                    <x-responsive-nav-link :href="route('donors.show', Auth::user()->donor->id)" :active="request()->routeIs('donors.show', Auth::user()->donor->id)">
                        {{ __('Profil Donatur Saya') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('donors.create')" :active="request()->routeIs('donors.create')">
                        {{ __('Lengkapi Profil Donatur') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('donations.create')" :active="request()->routeIs('donations.create')">
                    {{ __('Ajukan Donasi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('donations.index')" :active="request()->routeIs('donations.index') && !request()->routeIs('donations.create')">
                    {{ __('Riwayat Donasi Saya') }}
                </x-responsive-nav-link>
            @endif

            {{-- Responsive Links untuk PETUGAS DISTRIBUSI --}}
            @if(Auth::user()->role === 'distributor')
                <x-responsive-nav-link :href="route('distributions.create')" :active="request()->routeIs('distributions.create')">
                    {{ __('Catat Distribusi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('distributions.index')" :active="request()->routeIs('distributions.index') && !request()->routeIs('distributions.create')">
                    {{ __('Riwayat Distribusi Saya') }}
                </x-responsive-nav-link>
            @endif

            {{-- Responsive Links untuk PENGGUNA BIASA --}}
            @if(Auth::user()->role === 'user')
                 @if(!Auth::user()->donor)
                    <x-responsive-nav-link :href="route('donors.create')" :active="request()->routeIs('donors.create')">
                        {{ __('Jadi Donatur') }}
                    </x-responsive-nav-link>
                @endif
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Edit Profil Akun') }}
                </x-responsive-nav-link>

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