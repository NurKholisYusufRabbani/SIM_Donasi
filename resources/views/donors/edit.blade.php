<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if(Auth::user()->role === 'admin' && Auth::id() !== $donor->user_id)
                {{ __('Edit Profil Donatur: ') }} {{ $donor->user->username ?? 'Tidak Diketahui' }}
            @else
                {{ __('Edit Profil Donatur Saya') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

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

                    <form action="{{ route('donors.update', $donor->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- atau PATCH --}}

                        <div class="space-y-6">
                            {{-- Nomor Telepon --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $donor->phone) }}" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="4" required
                                          class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('address') border-red-500 @enderror">{{ old('address', $donor->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Pekerjaan --}}
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pekerjaan (Opsional)</label>
                                <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $donor->occupation) }}"
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('occupation') border-red-500 @enderror">
                                @error('occupation')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Informasi Akun Terkait (Read-only) --}}
                            @if($donor->user)
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">Informasi Akun Terkait (Tidak dapat diubah di sini)</h4>
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Username:</span>
                                        <span class="ml-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->user->username }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                        <span class="ml-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->user->email }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('donors.show', $donor->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>