@if (Auth::user()->role === 'admin')
    <!-- Container utama supaya posisi sidebar dan konten bisa responsif -->
    <div class="flex">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="h-full flex flex-col px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800 mt-5 ml-3">
                <!-- Header sidebar dengan tombol hamburger dan judul sejajar -->
                <div class="flex items-center justify-between mb-5">
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold dark:text-white whitespace-nowrap">
                        Sistem Informasi Manajemen Donasi
                    </a>
                    <!-- Tombol hamburger untuk tutup sidebar di mobile -->
                    <button id="sidebarCloseBtn" class="md:hidden focus:outline-none">
                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 ml-1" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <ul class="space-y-2 font-medium">
                    <!-- ...menu items... -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-4 0h4" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8zm0 0a4 4 0 01-4-4" />
                            </svg>
                            <span class="ml-3">Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('donors.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2C7 7 4 10 4 13a8 8 0 0016 0c0-3-3-6-8-11z" />
                            </svg>
                            <span class="ml-3">Donatur</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('donations.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2h2a4 4 0 11-8 0h2c0 1.1.9 2 2 2zM12 8v14" />
                            </svg>
                            <span class="ml-3">Donasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('distributions.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4h16M4 10h16M4 16h16" />
                            </svg>
                            <span class="ml-3">Distribusi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('beneficiaries.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3">Penerima</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    d="M9 17v-6h13v6M9 5v6h13V5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                            <span class="ml-3">Laporan</span>
                        </a>
                    </li>
                    <!-- ...menu items lainnya... -->
                </ul>
            </div>
        </aside>

        <!-- Konten utama -->
        <div class="flex-1 min-h-screen md:ml-64">
            <!-- Header atas untuk tombol hamburger mobile -->
            <header
                class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 flex items-center md:hidden">
                <button id="sidebarOpenBtn" class="focus:outline-none">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="ml-4 font-semibold text-gray-900 dark:text-white">Sistem Informasi Manajemen Donasi</span>
            </header>

            <!-- Isi konten di sini -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('sidebarOpenBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');

        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    </script>
@endif
