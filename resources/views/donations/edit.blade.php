<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Donasi #') }}{{ $donation->id }}
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

                    <form action="{{ route('donations.update', $donation->id) }}" method="POST" x-data="{ type: '{{ old('type', $donation->type) }}' }">
                        @csrf
                        @method('PUT') {{-- atau PATCH --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Donor (Khusus Admin) --}}
                            <div class="md:col-span-2">
                                <label for="donor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Donatur <span class="text-red-500">*</span></label>
                                <select name="donor_id" id="donor_id" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('donor_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Donatur --</option>
                                    @foreach ($donors as $donor_option) {{-- Menggunakan variabel berbeda untuk menghindari konflik dengan $donation->donor --}}
                                        <option value="{{ $donor_option->id }}" {{ old('donor_id', $donation->donor_id) == $donor_option->id ? 'selected' : '' }}>
                                            {{ $donor_option->user->username ?? 'User tidak diketahui' }} (ID: {{ $donor_option->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('donor_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jumlah Donasi --}}
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span x-show="type === 'uang'">Jumlah (Rp) <span class="text-red-500">*</span></span>
                                    <span x-show="type === 'barang'">Estimasi Nilai Barang (Rp) <span class="text-red-500">*</span></span>
                                </label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $donation->amount) }}" step="0.01" min="0.01" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('amount') border-red-500 @enderror">
                                @error('amount')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal Donasi --}}
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Donasi <span class="text-red-500">*</span></label>
                                <input type="date" name="date" id="date" value="{{ old('date', $donation->date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('date') border-red-500 @enderror">
                                @error('date')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jenis Donasi --}}
                            <div class="md:col-span-2">
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Donasi <span class="text-red-500">*</span></label>
                                <select name="type" id="type" x-model="type" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('type') border-red-500 @enderror">
                                    <option value="uang" {{ old('type', $donation->type) == 'uang' ? 'selected' : '' }}>Uang</option>
                                    <option value="barang" {{ old('type', $donation->type) == 'barang' ? 'selected' : '' }}>Barang</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Detail Barang (jika jenis 'barang') --}}
                            <div class="md:col-span-2" x-show="type === 'barang'">
                                <label for="item_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Detail Barang <span x-show="type === 'barang'" class="text-red-500">*</span></label>
                                <input type="text" name="item_details" id="item_details" value="{{ old('item_details', $donation->item_details) }}"
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('item_details') border-red-500 @enderror"
                                       :required="type === 'barang'">
                                @error('item_details')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi/Tujuan Donasi --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi / Tujuan Donasi (Opsional)</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $donation->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status (Khusus Admin) --}}
                            <div class="md:col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Donasi <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                    <option value="pending" {{ old('status', $donation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diterima" {{ old('status', $donation->status) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ old('status', $donation->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('donations.show', $donation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
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
    {{-- Script Alpine.js jika belum ada di layout utama --}}
    {{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> --}}
</x-app-layout>