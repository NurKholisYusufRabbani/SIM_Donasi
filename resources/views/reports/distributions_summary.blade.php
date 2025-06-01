<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Ringkasan Distribusi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filter Tanggal --}}
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <form method="GET" action="{{ route('reports.distributions.summary') }}" class="flex flex-col md:flex-row md:items-end gap-4">
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
                        <a href="{{ route('reports.distributions.summary') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition">
                            Reset Filter
                        </a>
                    @endif
                </form>
            </div>

            {{-- Hasil Laporan Ringkasan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Transaksi Distribusi</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalDistributionTransactions ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Dana Didistribusikan</h4>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalDistributedAmount ?? 0, 2, ',', '.') }}</p>
                </div>
            </div>

            {{-- Distribusi per Penerima (Top 10) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-md font-semibold mb-2 text-gray-900 dark:text-gray-100">Top 10 Penerima Manfaat (Berdasarkan Jumlah Diterima)</h4>
                    @if($distributionsByBeneficiary && $distributionsByBeneficiary->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Penerima</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jml Transaksi</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Diterima</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($distributionsByBeneficiary as $item)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $item->beneficiary_name }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $item->total_transactions }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">Rp {{ number_format($item->total_amount_received, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data distribusi per penerima untuk ditampilkan.</p>
                    @endif
                </div>
            </div>


            {{-- Tabel Rincian Distribusi Terbaru/Terfilter --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-md font-semibold mb-2">Rincian Transaksi Distribusi
                        @if($startDate && $endDate)
                            (Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YYYY') }})
                        @elseif($startDate)
                            (Mulai: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMM YYYY') }})
                        @elseif($endDate)
                            (Hingga: {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMM YYYY') }})
                        @else
                            (Terbaru)
                        @endif
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID Dist.</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tgl Dist.</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Donasi Asal (ID)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Penerima</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($distributions as $distribution)
                                    <tr>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $distribution->id }}</td>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($distribution->distributed_at)->isoFormat('D MMM YY') }}</td>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">
                                            <a href="{{ $distribution->donation ? route('donations.show', $distribution->donation_id) : '#' }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                #{{ $distribution->donation_id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">Rp {{ number_format($distribution->amount, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">
                                            @if($distribution->beneficiary)
                                                <a href="{{ route('beneficiaries.show', $distribution->beneficiary_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    {{ Str::limit($distribution->beneficiary->name, 25) }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm whitespace-nowrap">{{ $distribution->distributor->username ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada data distribusi untuk periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $distributions->links() }} {{-- Paginasi untuk tabel rincian --}}
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