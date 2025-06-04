<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if(Auth::user()->role === 'admin' && Auth::id() !== $donor->user_id)
                {{ __('Detail Profil Donatur: ') }} {{ $donor->user->username ?? 'Tidak Diketahui' }}
            @else
                {{ __('Profil Donatur Saya') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Pesan Info, Sukses atau Error dari redirect (jika ada) --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-700 text-green-700 dark:text-green-100 border border-green-400 dark:border-green-600 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-700 text-red-700 dark:text-red-100 border border-red-400 dark:border-red-600 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-700 text-blue-700 dark:text-blue-100 border border-blue-400 dark:border-blue-600 rounded">
                            {{ session('info') }}
                        </div>
                    @endif


                    {{-- Informasi Profil Donatur --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Kontak Donatur</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->phone ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pekerjaan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->occupation ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->address ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Informasi Akun Pengguna Terkait --}}
                    @if($donor->user)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Akun Terkait</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->user->username }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->user->email }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role Akun</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($donor->user->role) }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif

                    {{-- Riwayat Donasi --}}
                    <div class="pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-2">Riwayat Donasi</h3>
                        @if($donor->donations && $donor->donations->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID Donasi</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jenis</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah/Nilai</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($donor->donations->sortByDesc('date') as $donation)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">#{{ $donation->id }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($donation->date)->isoFormat('D MMM YY') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">{{ ucfirst($donation->type) }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">Rp {{ number_format($donation->amount, 2, ',', '.') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($donation->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                                    @elseif($donation->status == 'diterima') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($donation->status == 'ditolak') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @endif">
                                                    {{ ucfirst($donation->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                                <a href="{{ route('donations.show', $donation->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat donasi dari donatur ini.</p>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('donors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    Kembali ke Daftar Donatur
                                </a>
                            @else {{-- Donatur melihat profilnya sendiri --}}
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    Kembali ke Dashboard
                                </a>
                            @endif
                        </div>
                        <div class="space-x-3">
                            @if(Auth::user()->role === 'donator' && Auth::id() === $donor->user_id)
                                <a href="{{ route('donations.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 dark:hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                                    Ajukan Donasi Baru
                                </a>
                            @endif
                            @if(Auth::user()->role === 'admin' || (Auth::id() === $donor->user_id))
                                <a href="{{ route('donors.edit', $donor->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-500 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 disabled:opacity-25 transition">
                                    Edit Profil Ini
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>