<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Ringkasan Donasi Keseluruhan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filter Tanggal --}}
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <form method="GET" action="{{ route('reports.donations.summary') }}" class="flex flex-col md:flex-row md:items-end gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                        Filter
                    </button>
                     @if($startDate || $endDate)
                        <a href="{{ route('reports.donations.summary') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition">
                            Reset Filter
                        </a>
                    @endif
                </form>
            </div>

            {{-- Hasil Laporan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Pengajuan Donasi</h4>
                    <p class="text-2xl font-bold">{{ $totalDonations ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Nominal Diajukan</h4>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalAmount ?? 0, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Nominal Diterima</h4>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalAmountAccepted ?? 0, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Donasi Berdasarkan Jenis --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-md font-semibold mb-2 text-gray-900 dark:text-gray-100">Donasi Berdasarkan Jenis</h4>
                        @if($donationsByType && $donationsByType->count() > 0)
                        <ul class="list-disc pl-5 space-y-1 text-gray-700 dark:text-gray-300">
                            @foreach($donationsByType as $type)
                                <li>
                                    @if($type->type == 'uang')
                                        Uang: {{ $type->total_count }} donasi (Rp {{ number_format($type->total_amount_in_type, 2, ',', '.') }})
                                    @elseif($type->type == 'barang')
                                        Barang: {{ $type->total_count }} donasi (Estimasi Nilai Rp {{ number_format($type->total_amount_in_type, 2, ',', '.') }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data.</p>
                        @endif
                    </div>
                </div>

                {{-- Donasi Berdasarkan Status --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-md font-semibold mb-2 text-gray-900 dark:text-gray-100">Donasi Berdasarkan Status</h4>
                         @if($donationsByStatus && $donationsByStatus->count() > 0)
                        <ul class="list-disc pl-5 space-y-1 text-gray-700 dark:text-gray-300">
                            @foreach($donationsByStatus as $status)
                                <li>{{ ucfirst($status->status) }}: {{ $status->total_count }} donasi</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data.</p>
                        @endif
                    </div>
                </div>
            </div>
             <div class="mt-6">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition">
                    Kembali ke Pilihan Laporan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>