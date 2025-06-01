<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Donasi #') }}{{ $donation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Informasi Utama Donasi --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Donasi</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Donasi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donation->id }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Donasi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($donation->date)->isoFormat('D MMMM YYYY') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($donation->type) }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    @if($donation->type == 'uang')
                                        Jumlah
                                    @else
                                        Estimasi Nilai
                                    @endif
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($donation->type == 'uang')
                                        Rp {{ number_format($donation->amount, 2, ',', '.') }}
                                    @elseif($donation->type == 'barang')
                                        Est. Rp {{ number_format($donation->amount, 2, ',', '.') }}
                                        @if($donation->item_details)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ Str::limit($donation->item_details, 40) }})</span>
                                        @endif
                                    @endif
                                </dd>
                            </div>
                            @if($donation->type === 'barang' && $donation->item_details)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Detail Barang</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donation->item_details }}</dd>
                            </div>
                            @endif
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi / Tujuan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donation->description ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($donation->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                        @elseif($donation->status == 'diterima') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                        @elseif($donation->status == 'ditolak') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @endif">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Informasi Donatur --}}
                    @if($donation->donor && $donation->donor->user)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Donatur</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Donatur</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donation->donor->user->username }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Donatur</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donation->donor->user->email }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon Donatur</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donation->donor->phone ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif

                    {{-- Riwayat Distribusi --}}
                    <div class="pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">Riwayat Distribusi</h3>
                        @if($donation->distributions && $donation->distributions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tgl Distribusi</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Penerima</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Dicatat Oleh</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($donation->distributions as $distribution)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($distribution->distributed_at)->isoFormat('D MMM YY') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">Rp {{ number_format($distribution->amount, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $distribution->beneficiary->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $distribution->distributor->username ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $distribution->description ?: '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat distribusi untuk donasi ini.</p>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('donations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Kembali ke Daftar
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('donations.edit', $donation->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-500 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 disabled:opacity-25 transition">
                                Edit Donasi
                            </a>
                        @endif
                        @if(($donation->status === 'diterima') && (Auth::user()->role === 'admin' || Auth::user()->role === 'distributor'))
                            <a href="{{ route('distributions.create', ['donation_id' => $donation->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 dark:hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                                Catat Distribusi Baru
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>