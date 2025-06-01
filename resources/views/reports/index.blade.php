<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan & Statistik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Pilih Laporan yang Ingin Dilihat:</h3>
                    <div class="space-y-4">
                        {{-- Link ke Laporan Ringkasan Donasi --}}
                        <div>
                            <a href="{{ route('reports.donations.summary') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 font-semibold">
                                1. Laporan Ringkasan Donasi Keseluruhan
                            </a>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Menampilkan total donasi, jumlah nominal, berdasarkan jenis, dan status. Dapat difilter berdasarkan tanggal.</p>
                        </div>

                        {{-- Tambahkan link ke laporan lain di sini --}}
                        {{-- ... link laporan ringkasan donasi yang sudah ada ... --}}
                        <div class="mt-4">
                            <a href="{{ route('reports.distributions.summary') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 font-semibold">
                                2. Laporan Ringkasan Distribusi Keseluruhan
                            </a>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Menampilkan total dana didistribusikan, jumlah transaksi, dan rincian per penerima. Dapat difilter berdasarkan tanggal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>