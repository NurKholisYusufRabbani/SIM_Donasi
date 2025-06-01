<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Catatan Distribusi #') }}{{ $distribution->id }}
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

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-700 text-red-700 dark:text-red-100 border border-red-400 dark:border-red-600 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('distributions.update', $distribution->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- atau PATCH --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Pilih Donasi --}}
                            <div class="md:col-span-2">
                                <label for="donation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Donasi yang Akan Didistribusikan <span class="text-red-500">*</span></label>
                                <select name="donation_id" id="donation_id" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('donation_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Donasi --</option>
                                    @foreach ($donations as $donation_option) {{-- Variabel $donations dari controller --}}
                                        <option value="{{ $donation_option->id }}"
                                                {{ old('donation_id', $distribution->donation_id) == $donation_option->id ? 'selected' : '' }}
                                                data-max-amount="{{ $donation_option->amount - $donation_option->distributions->where('id', '!=', $distribution->id)->sum('amount') }}" {{-- Sisa amount tidak termasuk distribusi ini --}}
                                                >
                                            ID: {{ $donation_option->id }} - {{ Str::limit($donation_option->description ?: ($donation_option->donor->user->username ?? 'Donasi tanpa deskripsi'), 50) }}
                                            (Tersedia Total: Rp {{ number_format($donation_option->amount, 2, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('donation_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Pilih Penerima Manfaat --}}
                            <div class="md:col-span-2">
                                <label for="beneficiary_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Penerima Manfaat <span class="text-red-500">*</span></label>
                                <select name="beneficiary_id" id="beneficiary_id" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('beneficiary_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Penerima --</option>
                                    @foreach ($beneficiaries as $beneficiary_option) {{-- Variabel $beneficiaries dari controller --}}
                                        <option value="{{ $beneficiary_option->id }}" {{ old('beneficiary_id', $distribution->beneficiary_id) == $beneficiary_option->id ? 'selected' : '' }}>
                                            {{ $beneficiary_option->name }} ({{ $beneficiary_option->category ?: 'Kategori Umum' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('beneficiary_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jumlah Didistribusikan --}}
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Didistribusikan (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $distribution->amount) }}" step="0.01" min="0.01" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('amount') border-red-500 @enderror">
                                @error('amount')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <p id="amount-warning" class="mt-1 text-xs text-yellow-600 dark:text-yellow-400 hidden"></p>
                            </div>

                            {{-- Tanggal Distribusi --}}
                            <div>
                                <label for="distributed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Distribusi <span class="text-red-500">*</span></label>
                                <input type="date" name="distributed_at" id="distributed_at" value="{{ old('distributed_at', \Carbon\Carbon::parse($distribution->distributed_at)->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('distributed_at') border-red-500 @enderror">
                                @error('distributed_at')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi Distribusi --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi / Catatan Distribusi (Opsional)</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $distribution->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dicatat Oleh (Khusus Admin) --}}
                            <div class="md:col-span-2">
                                <label for="distributed_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dicatat Oleh (Petugas Distribusi) <span class="text-red-500">*</span></label>
                                <select name="distributed_by" id="distributed_by" required
                                        class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('distributed_by') border-red-500 @enderror">
                                    <option value="">-- Pilih Petugas --</option>
                                    @foreach ($distributors as $distributor_option) {{-- Variabel $distributors dari controller --}}
                                        <option value="{{ $distributor_option->id }}" {{ old('distributed_by', $distribution->distributed_by) == $distributor_option->id ? 'selected' : '' }}>
                                            {{ $distributor_option->username }} (Role: {{ $distributor_option->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('distributed_by')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('distributions.show', $distribution->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
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
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const donationSelect = document.getElementById('donation_id');
            const amountInput = document.getElementById('amount');
            const amountWarning = document.getElementById('amount-warning');
            const currentDistributionAmount = {{ $distribution->amount }}; // Ambil jumlah distribusi saat ini

            function checkAmount() {
                const selectedOption = donationSelect.options[donationSelect.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    amountWarning.classList.add('hidden');
                    amountWarning.textContent = '';
                    return;
                }

                // maxAmount adalah sisa dari donasi yang dipilih, ditambah dengan jumlah distribusi yang sedang diedit ini
                // karena jumlah ini "kembali" tersedia untuk diedit.
                let sisaDonasiSebelumEdit = parseFloat(selectedOption.dataset.maxAmount); // Ini adalah sisa donasi di luar distribusi saat ini
                let maxAllowedForThisDistribution;

                // Jika donasi yang dipilih sama dengan donasi asli dari distribusi ini
                if (parseInt(selectedOption.value) === {{ $distribution->donation_id }}) {
                    maxAllowedForThisDistribution = sisaDonasiSebelumEdit + currentDistributionAmount;
                } else { // Jika admin mengganti ke donasi lain
                    maxAllowedForThisDistribution = sisaDonasiSebelumEdit;
                }
                
                const currentAmountInput = parseFloat(amountInput.value);

                if (currentAmountInput > maxAllowedForThisDistribution) {
                    amountWarning.textContent = `Jumlah distribusi (Rp ${currentAmountInput.toLocaleString('id-ID')}) melebihi sisa donasi yang tersedia (Rp ${maxAllowedForThisDistribution.toLocaleString('id-ID')}).`;
                    amountWarning.classList.remove('hidden');
                } else {
                    amountWarning.classList.add('hidden');
                    amountWarning.textContent = '';
                }
            }

            if (donationSelect) {
                donationSelect.addEventListener('change', checkAmount);
            }
            if (amountInput) {
                amountInput.addEventListener('input', checkAmount);
            }
            // Initial check
            if (donationSelect && donationSelect.value) {
                checkAmount();
            }
        });
    </script>
    @endpush
</x-app-layout>