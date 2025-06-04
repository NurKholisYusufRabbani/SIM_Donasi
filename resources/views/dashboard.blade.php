<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium">
                        Selamat datang kembali,
                        <span
                            class="{{ Auth::user()->role === 'admin' ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                            {{ Auth::user()->username }}
                        </span>
                    </h3>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Anda login sebagai:
                        @if (Auth::user()->role === 'admin')
                            <span class="text-red-600">{{ ucfirst(Auth::user()->role) }}</span>
                        @else
                            <span class="text-white">{{ ucfirst(Auth::user()->role) }}</span>
                        @endif
                    </p>


                    @if (Auth::user()->role === 'admin')
                        {{-- Statistik Cards yang sudah ada sebelumnya --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Donasi Diterima</h4>
                                <p class="text-2xl font-bold">Rp
                                    {{ number_format($totalDonationAmountAccepted ?? 0, 2, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $acceptedDonations ?? 0 }} donasi
                                    diterima dari {{ $totalDonations ?? 0 }} total pengajuan.</p>
                            </div>
                            {{-- ... (kartu statistik lainnya) ... --}}
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Dana Didistribusikan
                                </h4>
                                <p class="text-2xl font-bold">Rp
                                    {{ number_format($totalDistributionAmount ?? 0, 2, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Donasi Pending</h4>
                                <p class="text-2xl font-bold">{{ $pendingDonations ?? 0 }}</p>
                                @if (($pendingDonations ?? 0) > 0)
                                    <a href="{{ route('donations.index', ['status' => 'pending']) }}"
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Lihat &
                                        Verifikasi</a>
                                @endif
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Pengguna</h4>
                                <p class="text-2xl font-bold">{{ $totalUsers ?? 0 }}</p>
                                <a href="{{ route('users.index') }}"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Kelola
                                    Pengguna</a>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Profil Donatur</h4>
                                <p class="text-2xl font-bold">{{ $totalDonors ?? 0 }}</p>
                                <a href="{{ route('donors.index') }}"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Kelola
                                    Donatur</a>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-200">Total Penerima Manfaat</h4>
                                <p class="text-2xl font-bold">{{ $totalBeneficiaries ?? 0 }}</p>
                                <a href="{{ route('beneficiaries.index') }}"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Kelola
                                    Penerima</a>
                            </div>
                        </div>

                        {{-- GRAFIK DONASI DITERIMA PER BULAN --}}
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow mb-6">
                            <h4 class="text-md font-semibold mb-3 text-gray-700 dark:text-gray-200">Tren Nominal Donasi
                                Diterima (6 Bulan Terakhir)</h4>
                            <canvas id="monthlyAcceptedDonationsChart"></canvas>
                        </div>

                        {{-- Daftar Donasi Pending yang sudah ada sebelumnya --}}
                        <h4 class="text-md font-semibold mb-2">5 Donasi Pending Terbaru:</h4>
                        @if ($recentPendingDonations && $recentPendingDonations->count() > 0)
                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                    {{-- ... tabel donasi pending ... --}}
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left">ID</th>
                                            <th class="px-4 py-2 text-left">Donatur</th>
                                            <th class="px-4 py-2 text-left">Jumlah</th>
                                            <th class="px-4 py-2 text-left">Tanggal</th>
                                            <th class="px-4 py-2 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($recentPendingDonations as $donation)
                                            <tr>
                                                <td class="px-4 py-2">#{{ $donation->id }}</td>
                                                <td class="px-4 py-2">{{ $donation->donor->user->username ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-2">Rp
                                                    {{ number_format($donation->amount, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2">
                                                    {{ \Carbon\Carbon::parse($donation->date)->isoFormat('D MMM YY') }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('donations.show', $donation->id) }}"
                                                        class="text-indigo-600 dark:text-indigo-400 hover:underline">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada donasi yang menunggu
                                verifikasi saat ini.</p>
                        @endif

                        {{-- Akses Cepat Manajemen yang sudah ada sebelumnya --}}
                        <h4 class="text-md font-semibold mb-2 mt-2">Akses Cepat Manajemen:</h4>
                        <div class="flex flex-wrap gap-4">
                            {{-- ... link akses cepat ... --}}
                            <a href="{{ route('donations.index') }}"
                                class="bg-gray-100 dark:bg-gray-700 text-white font-bold py-2 px-4 rounded">Manajemen
                                Donasi</a>
                            <a href="{{ route('distributions.index') }}"
                                class="bg-gray-100 dark:bg-gray-700 text-white font-bold py-2 px-4 rounded">Manajemen
                                Distribusi</a>
                            <a href="{{ route('users.index') }}"
                                class="bg-gray-100 dark:bg-gray-700 text-white font-bold py-2 px-4 rounded">Manajemen
                                Pengguna</a>
                            <a href="{{ route('donors.index') }}"
                                class="bg-gray-100 dark:bg-gray-700 text-white font-bold py-2 px-4 rounded">Manajemen
                                Donatur</a>
                            <a href="{{ route('beneficiaries.index') }}"
                                class="bg-gray-100 dark:bg-gray-700 text-white font-bold py-2 px-4 rounded">Manajemen
                                Penerima</a>
                        </div>
                    @elseif(Auth::user()->role === 'donator')
                        {{-- ... (Dashboard Donatur) ... --}}
                    @elseif(Auth::user()->role === 'distributor')
                        {{-- ... (Dashboard Distributor) ... --}}
                    @elseif(Auth::user()->role === 'user')
                        {{-- ... (Dashboard User Biasa) ... --}}
                    @else
                        <p>{{ __("You're logged in!") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tempatkan script Chart.js dan inisialisasi grafik di sini atau di stack 'scripts' layout utama --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @if (Auth::user()->role === 'admin' && isset($monthlyDonationLabels) && isset($monthlyDonationAmounts))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctxMonthlyDonations = document.getElementById('monthlyAcceptedDonationsChart');
                    if (ctxMonthlyDonations) {
                        new Chart(ctxMonthlyDonations, {
                            type: 'bar', // atau 'line'
                            data: {
                                labels: @json($monthlyDonationLabels),
                                datasets: [{
                                    label: 'Nominal Donasi Diterima (Rp)',
                                    data: @json($monthlyDonationAmounts),
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Biru muda
                                    borderColor: 'rgba(54, 162, 235, 1)', // Biru tua
                                    borderWidth: 1,
                                    tension: 0.1 // Untuk line chart agar sedikit melengkung
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true, // Bisa false jika ingin mengatur tinggi sendiri via CSS
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value, index, values) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                if (context.parsed.y !== null) {
                                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                                }
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>
