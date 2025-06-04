<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pengguna: ') }} {{ $user->username }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Informasi Dasar Pengguna --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informasi Akun</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pengguna</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->id }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->username }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($user->role == 'admin') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                        @elseif($user->role == 'donator') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                        @elseif($user->role == 'distributor') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Terdaftar</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d F Y H:i') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('d F Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Informasi Profil Donatur (jika ada) --}}
                    @if ($user->donor)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Profil Donatur</h3>
                        <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->donor->phone ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pekerjaan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->donor->occupation ?: '-' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->donor->address ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                    @endif

                    {{-- Informasi Distribusi yang Dilakukan (jika ada) --}}
                    @if ($user->distributions && $user->distributions->count() > 0)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Riwayat Distribusi Dicatat</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Pengguna ini telah mencatat {{ $user->distributions->count() }} distribusi.</p>
                        {{-- Anda bisa menampilkan tabel ringkas distribusi di sini jika diperlukan --}}
                        {{-- Contoh: --}}
                        {{-- <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($user->distributions->take(5) as $distribution)
                                <li>
                                    Distribusi sejumlah {{ $distribution->amount }} pada {{ $distribution->distributed_at->format('d M Y') }}
                                    (ID: {{ $distribution->id }})
                                </li>
                            @endforeach
                            @if($user->distributions->count() > 5)
                                <li>... dan lainnya.</li>
                            @endif
                        </ul> --}}
                    </div>
                    @endif

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Kembali ke Daftar
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-500 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 disabled:opacity-25 transition">
                            Edit Pengguna Ini
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>