<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->username }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 dark:bg-red-700 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! Ada yang salah:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- atau PATCH, sesuaikan dengan definisi route Anda, PUT umum untuk update keseluruhan resource --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Username --}}
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            {{-- Role --}}
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                                <select name="role" id="role" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach ($roles as $roleValue)
                                        {{-- Logika untuk mencegah admin menurunkan role admin lain atau menaikkan role user lain menjadi admin dari form ini,
                                             jika $user yang diedit adalah admin dan bukan diri sendiri.
                                             Atau, jika $user yang diedit BUKAN admin, dan role yang dipilih adalah 'admin' dan bukan diri sendiri.
                                             Logika ini lebih kompleks dan biasanya ditangani juga di backend (seperti di UserController@updateRole).
                                             Untuk form edit umum, kita tampilkan semua role yang valid.
                                             Controller Anda (UserController@update) sudah melakukan validasi role.
                                        --}}
                                        <option value="{{ $roleValue }}" {{ old('role', $user->role) == $roleValue ? 'selected' : '' }}>
                                            {{ ucfirst($roleValue) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(Auth::user()->id === $user->id && $user->role === 'admin')
                                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">Anda tidak dapat mengubah role Anda sendiri jika Anda adalah admin.</p>
                                @endif
                            </div>

                            {{-- Kosongkan satu kolom jika ingin password di baris baru --}}
                            <div class="md:col-span-2 my-2 border-t border-gray-200 dark:border-gray-700"></div>


                            {{-- Password (Opsional oleh Admin) --}}
                            <p class="md:col-span-2 text-sm text-gray-600 dark:text-gray-400 mb-1">
                                Isi field password hanya jika Anda ingin mengubah password pengguna ini.
                                Jika dibiarkan kosong, password tidak akan berubah.
                                (Catatan: logika update password di UserController Anda saat ini dikomentari).
                            </p>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru</label>
                                <input type="password" name="password" id="password"
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('users.show', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>