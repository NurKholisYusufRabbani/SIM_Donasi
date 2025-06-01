<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Distribution;
use App\Models\Beneficiary;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk query yang lebih kompleks jika diperlukan

class ReportController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin yang bisa mengakses semua fitur laporan
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Menampilkan halaman utama untuk pilihan laporan.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Contoh Laporan: Ringkasan Donasi Keseluruhan
     */
    public function donationSummary(Request $request)
    {
        // Filter berdasarkan rentang tanggal jika ada
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $donationsQuery = Donation::query();

        if ($startDate && $endDate) {
            $donationsQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $totalDonations = (clone $donationsQuery)->count();
        $totalAmount = (clone $donationsQuery)->sum('amount');
        $totalAmountAccepted = (clone $donationsQuery)->where('status', 'diterima')->sum('amount');

        $donationsByType = (clone $donationsQuery)->select('type', DB::raw('count(*) as total_count'), DB::raw('sum(amount) as total_amount_in_type'))
            ->groupBy('type')
            ->get();

        $donationsByStatus = (clone $donationsQuery)->select('status', DB::raw('count(*) as total_count'))
            ->groupBy('status')
            ->get();

        return view('reports.donations_summary', compact(
            'totalDonations',
            'totalAmount',
            'totalAmountAccepted',
            'donationsByType',
            'donationsByStatus',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Laporan: Ringkasan Distribusi Keseluruhan
     */
    public function distributionSummary(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $distributionsQuery = Distribution::query()->with(['donation', 'beneficiary', 'distributor']);

        if ($startDate && $endDate) {
            // Filter berdasarkan tanggal distribusi ('distributed_at')
            $distributionsQuery->whereBetween('distributed_at', [$startDate, $endDate]);
        }

        $totalDistributionTransactions = (clone $distributionsQuery)->count();
        $totalDistributedAmount = (clone $distributionsQuery)->sum('amount');

        // Ambil beberapa data distribusi untuk ditampilkan (misalnya, 15 terbaru atau paginasi)
        $distributions = (clone $distributionsQuery)->latest('distributed_at')->paginate(15)->withQueryString();
        // withQueryString() digunakan agar filter tanggal tetap ada di link paginasi

        $distributionsByBeneficiary = Distribution::query() // Query baru untuk agregasi ini
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('distributed_at', [$startDate, $endDate]);
            })
            ->join('beneficiaries', 'distributions.beneficiary_id', '=', 'beneficiaries.id')
            ->select('beneficiaries.name as beneficiary_name', DB::raw('count(distributions.id) as total_transactions'), DB::raw('sum(distributions.amount) as total_amount_received'))
            ->groupBy('beneficiaries.name')
            ->orderBy('total_amount_received', 'desc')
            ->take(10) // Ambil top 10 penerima
            ->get();


        return view('reports.distributions_summary', compact(
            'totalDistributionTransactions',
            'totalDistributedAmount',
            'distributions', // Data distribusi yang dipaginasi/terbaru
            'distributionsByBeneficiary',
            'startDate',
            'endDate'
        ));
    }
}