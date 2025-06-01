<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Penting untuk transaksi jika diperlukan

class DistributionController extends Controller
{
    public function __construct()
    {
        // Middleware bisa juga diterapkan di route jika lebih disukai
        $this->middleware('auth');
        // Otorisasi lebih spesifik dilakukan di dalam masing-masing method
    }

    /**
     * Tampilkan daftar semua distribusi.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $distributions = Distribution::with('donation.donor.user', 'beneficiary', 'distributor')
                                        ->latest()
                                        ->paginate(10);
        } else {
            // Hanya tampilkan distribusi yang dicatat oleh user yang login (jika dia distributor)
            $distributions = Distribution::where('distributed_by', Auth::id())
                                        ->with('donation.donor.user', 'beneficiary', 'distributor')
                                        ->latest()
                                        ->paginate(10);
        }
        return view('distributions.index', compact('distributions'));
    }

    /**
     * Tampilkan form untuk membuat distribusi baru.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'distributor') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk membuat distribusi.');
        }

        // Hanya donasi yang sudah diterima & masih memiliki sisa yang bisa didistribusikan
        $donations = Donation::where('status', 'diterima')
            ->get()
            ->filter(function ($donation) {
                // Hitung sisa untuk setiap donasi
                $totalDistributed = $donation->distributions->sum('amount');
                return $donation->amount > $totalDistributed;
            });

        if ($donations->isEmpty() && Auth::user()->role !== 'admin' && !request()->has('donation_id')) {
             return redirect()->route('dashboard')->with('info', 'Saat ini tidak ada donasi yang siap untuk didistribusikan.');
        }
         if ($donations->isEmpty() && request()->has('donation_id')) {
            // Jika datang dari link dengan donation_id tapi donasi itu tidak valid lagi (misal sudah habis)
            $specificDonation = Donation::find(request()->get('donation_id'));
            if($specificDonation && $specificDonation->status === 'diterima'){
                // Masih boleh ditampilkan di form, validasi jumlah akan menanganinya
                 $donations = collect([$specificDonation]);
            } else {
                 return redirect()->route('dashboard')->with('error', 'Donasi yang dipilih tidak valid atau tidak siap didistribusikan.');
            }
        }


        $beneficiaries = Beneficiary::orderBy('name')->get();
        $distributors = User::whereIn('role', ['admin', 'distributor'])->orderBy('username')->get();

        return view('distributions.create', compact('donations', 'beneficiaries', 'distributors'));
    }

    /**
     * Simpan distribusi baru ke database.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'distributor') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'distributed_at' => 'required|date',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            // distributed_by akan diambil dari Auth::id() untuk distributor, atau dari input untuk admin
            'distributed_by' => (Auth::user()->role === 'admin' ? 'required|exists:users,id' : 'nullable'),
        ]);

        $donation = Donation::findOrFail($validated['donation_id']);

        // Pastikan donasi berstatus 'diterima'
        if ($donation->status !== 'diterima') {
            return back()->withInput()->with('error', 'Donasi ini belum berstatus "diterima" dan tidak bisa didistribusikan.');
        }

        $totalAlreadyDistributed = $donation->distributions()->sum('amount');
        $remainingAmount = $donation->amount - $totalAlreadyDistributed;

        if ($validated['amount'] > $remainingAmount) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah distribusi (Rp ' . number_format($validated['amount'], 2, ',', '.') . ') melebihi sisa donasi yang tersedia (Rp ' . number_format($remainingAmount, 2, ',', '.') . '). Sisa tersedia: Rp ' . number_format($remainingAmount, 2, ',', '.'));
        }

        $distributedBy = (Auth::user()->role === 'admin') ? $validated['distributed_by'] : Auth::id();

        Distribution::create([
            'donation_id' => $validated['donation_id'],
            'distributed_by' => $distributedBy,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'distributed_at' => $validated['distributed_at'],
            'beneficiary_id' => $validated['beneficiary_id'],
        ]);

        return redirect()->route('distributions.index')->with('success', 'Distribusi berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail distribusi tertentu.
     */
    public function show(Distribution $distribution)
    {
        if (Auth::user()->role !== 'admin' && $distribution->distributed_by !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $distribution->load('donation.donor.user', 'beneficiary', 'distributor');
        return view('distributions.show', compact('distribution'));
    }

    /**
     * Tampilkan form untuk mengedit distribusi.
     */
    public function edit(Distribution $distribution)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Donasi yang bisa dipilih: yang diterima, ATAU donasi saat ini meskipun sudah habis (untuk diedit jumlahnya)
        $currentDonationId = $distribution->donation_id;
        $donations = Donation::where('status', 'diterima')
            ->orWhere('id', $currentDonationId) // Sertakan donasi saat ini
            ->get()
            ->filter(function ($donation) use ($currentDonationId, $distribution) {
                $totalDistributed = $donation->distributions()->where('id', '!=', $distribution->id)->sum('amount');
                // Tampilkan jika donasi adalah donasi saat ini ATAU masih ada sisa
                return $donation->id === $currentDonationId || $donation->amount > $totalDistributed;
            });


        $beneficiaries = Beneficiary::orderBy('name')->get();
        $distributors = User::whereIn('role', ['admin', 'distributor'])->orderBy('username')->get();

        return view('distributions.edit', compact('distribution', 'donations', 'beneficiaries', 'distributors'));
    }

    /**
     * Perbarui distribusi di database.
     */
    public function update(Request $request, Distribution $distribution)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'distributed_at' => 'required|date',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'distributed_by' => 'required|exists:users,id',
        ]);

        $targetDonation = Donation::findOrFail($validated['donation_id']);

        // Pastikan donasi target berstatus 'diterima'
        if ($targetDonation->status !== 'diterima' && $targetDonation->id !== $distribution->donation_id) {
             // Jika donasi target diubah ke donasi lain yang belum diterima
            return back()->withInput()->with('error', 'Donasi target belum berstatus "diterima".');
        }
         if ($targetDonation->status !== 'diterima' && $targetDonation->id === $distribution->donation_id && $validated['amount'] > $distribution->amount) {
            // Jika donasi target sama, tapi statusnya berubah (tidak mungkin terjadi jika hanya bisa pilih yg diterima)
            // dan mencoba menaikkan amount. Ini lebih ke sanity check.
            return back()->withInput()->with('error', 'Donasi ini tidak lagi berstatus "diterima" untuk penambahan jumlah distribusi.');
        }


        // Hitung total yang sudah didistribusikan dari donasi target, KECUALI distribusi yang sedang diedit ini
        $totalAlreadyDistributedForTargetDonation = Distribution::where('donation_id', $targetDonation->id)
                                                                ->where('id', '!=', $distribution->id)
                                                                ->sum('amount');

        $remainingAmountOnTargetDonation = $targetDonation->amount - $totalAlreadyDistributedForTargetDonation;

        if ($validated['amount'] > $remainingAmountOnTargetDonation) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah distribusi baru (Rp ' . number_format($validated['amount'], 2, ',', '.') . ') melebihi sisa yang tersedia pada donasi target (Rp ' . number_format($remainingAmountOnTargetDonation, 2, ',', '.') . '). Sisa tersedia: Rp '.number_format($remainingAmountOnTargetDonation, 2, ',', '.'));
        }

        $distribution->update($validated);

        return redirect()->route('distributions.index')->with('success', 'Distribusi berhasil diperbarui!');
    }

    /**
     * Hapus distribusi dari database.
     */
    public function destroy(Distribution $distribution)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        // Pertimbangkan logika bisnis lain di sini jika diperlukan,
        // misalnya, apakah menghapus distribusi akan "mengembalikan" dana ke donasi?
        // Untuk saat ini, kita hanya menghapus record distribusi.
        $distribution->delete();
        return redirect()->route('distributions.index')->with('success', 'Distribusi berhasil dihapus!');
    }
}