<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use App\Models\Beneficiary;
use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = []; // Inisialisasi $data

        if ($user->role === 'admin') {
            // Data statistik yang sudah ada sebelumnya
            $data = [
                'totalDonations' => Donation::count(),
                'pendingDonations' => Donation::where('status', 'pending')->count(),
                'acceptedDonations' => Donation::where('status', 'diterima')->count(),
                'totalDonationAmountAccepted' => Donation::where('status', 'diterima')->sum('amount'),
                'totalDistributionAmount' => Distribution::sum('amount'),
                'totalDonors' => Donor::count(),
                'totalUsers' => User::count(),
                'totalBeneficiaries' => Beneficiary::count(),
                'recentPendingDonations' => Donation::where('status', 'pending')->with('donor.user')->latest()->take(5)->get(),
            ];

            // Data untuk Grafik Tren Donasi Diterima per Bulan (6 bulan terakhir)
            $monthlyAcceptedDonationsData = Donation::select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('status', 'diterima')
            ->where('date', '>=', Carbon::now()->subMonths(5)->startOfMonth()) // 6 bulan termasuk bulan ini
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

            $donationLabels = [];
            $donationAmounts = [];
            // Inisialisasi data untuk 6 bulan terakhir dengan 0
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $donationLabels[] = $month->isoFormat('MMM YYYY'); // Format: Jan 2023
                $donationAmounts[] = 0; // Default 0
            }

            foreach ($monthlyAcceptedDonationsData as $record) {
                $recordMonthName = Carbon::createFromDate($record->year, $record->month, 1)->isoFormat('MMM YYYY');
                $index = array_search($recordMonthName, $donationLabels);
                if ($index !== false) {
                    $donationAmounts[$index] = $record->total_amount;
                }
            }

            $data['monthlyDonationLabels'] = $donationLabels;
            $data['monthlyDonationAmounts'] = $donationAmounts;

        } elseif ($user->role === 'donator') {
            // ... (logika untuk donatur)
            $donor = $user->donor;
            if ($donor) {
                $data = [
                    'myTotalDonations' => $donor->donations()->count(),
                    'myPendingDonations' => $donor->donations()->where('status', 'pending')->count(),
                    'myAcceptedDonations' => $donor->donations()->where('status', 'diterima')->count(),
                    'myRejectedDonations' => $donor->donations()->where('status', 'ditolak')->count(),
                ];
            }
        } elseif ($user->role === 'distributor') {
            // ... (logika untuk distributor)
             $data = [
                'myTotalDistributions' => Distribution::where('distributed_by', $user->id)->count(),
                'myTotalDistributedAmount' => Distribution::where('distributed_by', $user->id)->sum('amount'),
            ];
        }
        // ... (logika untuk user biasa)

        return view('dashboard', $data);
    }
}