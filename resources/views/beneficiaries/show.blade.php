<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Penerima Manfaat: ') }} {{ $beneficiary->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Informasi Dasar Penerima Manfaat --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Penerima</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Penerima</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->id }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Penerima</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->category ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->phone ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->address ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Ditambahkan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $beneficiary->updated_at->isoFormat('D MMMM YYYY, HH:mm') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Riwayat Distribusi Diterima --}}
                    <div class="pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">Riwayat Distribusi Diterima</h3>
                        @if($beneficiary->distributions && $beneficiary->distributions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID Distribusi</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tgl Distribusi</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah Diterima</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Dari Donasi (ID)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Dicatat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($beneficiary->distributions as $distribution)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                <a href="{{ route('distributions.show', $distribution->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    #{{ $distribution->id }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($distribution->distributed_at)->isoFormat('D MMM YY') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">Rp {{ number_format($distribution->amount, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                @if($distribution->donation)
                                                <a href="{{ route('donations.show', $distribution->donation_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    #{{ $distribution->donation_id }}
                                                </a>
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ $distribution->distributor->username ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat distribusi yang diterima oleh penerima manfaat ini.</p>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('beneficiaries.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Kembali ke Daftar
                        </a>
                        <a href="{{ route('beneficiaries.edit', $beneficiary->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-500 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 disabled:opacity-25 transition">
                            Edit Penerima Ini
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>