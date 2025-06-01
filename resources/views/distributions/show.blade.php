<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Distribusi Donasi #') }}{{ $distribution->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Informasi Utama Distribusi --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Rincian Distribusi</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Distribusi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->id }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Distribusi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($distribution->distributed_at)->isoFormat('D MMMM YYYY') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Didistribusikan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($distribution->amount, 2, ',', '.') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dicatat Oleh</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->distributor->username ?? 'N/A' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi/Catatan Distribusi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->description ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Informasi Donasi Asal --}}
                    @if($distribution->donation)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Donasi Asal</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Donasi Asal</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('donations.show', $distribution->donation->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        #{{ $distribution->donation->id }}
                                    </a>
                                </dd>
                            </div>
                            @if($distribution->donation->donor && $distribution->donation->donor->user)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Donatur Asal</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->donation->donor->user->username }}</dd>
                            </div>
                            @endif
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi Donasi Asal</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->donation->description ?: ($distribution->donation->type == 'barang' ? $distribution->donation->item_details : 'Donasi Uang') }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif

                    {{-- Informasi Penerima Manfaat --}}
                    @if($distribution->beneficiary)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Penerima Manfaat</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Penerima</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                     @if(Auth::user()->role === 'admin') {{-- Hanya admin yang bisa ke detail beneficiary --}}
                                        <a href="{{ route('beneficiaries.show', $distribution->beneficiary->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $distribution->beneficiary->name }}
                                        </a>
                                    @else
                                        {{ $distribution->beneficiary->name }}
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->beneficiary->category ?: '-' }}</dd>
                            </div>
                             <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Penerima</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->beneficiary->address ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon Penerima</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $distribution->beneficiary->phone ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('distributions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Kembali ke Daftar
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('distributions.edit', $distribution->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-500 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 disabled:opacity-25 transition">
                                Edit Distribusi
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>