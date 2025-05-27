<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Beneficiary;
use App\Models\User; // Untuk distributed_by
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    /**
     * Tampilkan daftar semua distribusi.
     * Hanya admin yang bisa melihat semua.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $distributions = Distribution::with('donation.donor.user', 'beneficiary', 'distributor')
                                         ->latest()
                                         ->paginate(10);
        } else {
            // Jika bukan admin, hanya tampilkan distribusi yang dia lakukan
            $distributions = Distribution::where('distributed_by', Auth::id())
                                         ->with('donation.donor.user', 'beneficiary', 'distributor')
                                         ->latest()
                                         ->paginate(10);
        }

        return view('distributions.index', compact('distributions'));
    }

    /**
     * Tampilkan form untuk membuat distribusi baru.
     * Hanya admin atau user dengan role yang diizinkan untuk distribusi.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'distributor') { // Asumsi ada role 'distributor'
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk membuat distribusi.');
        }

        $donations = Donation::where('status', 'diterima')->get(); // Hanya donasi yang sudah diterima bisa didistribusikan
        $beneficiaries = Beneficiary::all();
        $distributors = User::whereIn('role', ['admin', 'distributor'])->get(); // User yang bisa mendistribusikan

        return view('distributions.create', compact('donations', 'beneficiaries', 'distributors'));
    }

    /**
     * Simpan distribusi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'distributed_at' => 'required|date',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'distributed_by' => 'required_if:user_role,admin|exists:users,id', // Hanya wajib jika admin yang membuat
        ]);

        $donation = Donation::find($validated['donation_id']);

        // Validasi ketersediaan jumlah donasi (jika ingin melacak sisa donasi)
        // Ini adalah logika tambahan yang mungkin perlu Anda implementasikan lebih lanjut
        // Contoh sederhana: pastikan jumlah yang didistribusikan tidak melebihi sisa donasi
        // Anda perlu menambahkan kolom 'distributed_amount' di tabel 'donations' atau menghitungnya secara dinamis
        // Untuk saat ini, kita asumsikan bisa mendistribusikan berapa pun dari donasi yang ada.
        if ($validated['amount'] > $donation->amount) { // Ini hanya validasi sederhana
             // Sebaiknya, Anda perlu menghitung total amount yang sudah didistribusikan dari donasi ini
             // $totalDistributed = Distribution::where('donation_id', $donation->id)->sum('amount');
             // $remainingAmount = $donation->amount - $totalDistributed;
             // if ($validated['amount'] > $remainingAmount) { ... }
             return back()->withInput()->with('error', 'Jumlah distribusi melebihi total jumlah donasi yang tersedia.');
        }


        // Tentukan distributed_by berdasarkan user yang login atau input admin
        if (Auth::user()->role === 'admin') {
            $distributedBy = $validated['distributed_by'];
        } else {
            $distributedBy = Auth::id();
        }

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
        // Pastikan hanya admin atau distributor yang relevan yang bisa melihat
        if (Auth::user()->role !== 'admin' && $distribution->distributed_by !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat distribusi ini.');
        }

        $distribution->load('donation.donor.user', 'beneficiary', 'distributor');
        return view('distributions.show', compact('distribution'));
    }

    /**
     * Tampilkan form untuk mengedit distribusi.
     */
    public function edit(Distribution $distribution)
    {
        // Hanya admin yang bisa mengedit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit distribusi ini.');
        }

        $donations = Donation::where('status', 'diterima')->get();
        $beneficiaries = Beneficiary::all();
        $distributors = User::whereIn('role', ['admin', 'distributor'])->get();

        return view('distributions.edit', compact('distribution', 'donations', 'beneficiaries', 'distributors'));
    }

    /**
     * Perbarui distribusi di database.
     */
    public function update(Request $request, Distribution $distribution)
    {
        // Hanya admin yang bisa mengedit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit distribusi ini.');
        }

        $validated = $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'distributed_at' => 'required|date',
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'distributed_by' => 'required|exists:users,id',
        ]);

        $donation = Donation::find($validated['donation_id']);

        // Tambahkan validasi sisa donasi di sini juga jika diperlukan
        if ($validated['amount'] > $donation->amount) {
            return back()->withInput()->with('error', 'Jumlah distribusi melebihi total jumlah donasi yang tersedia.');
        }

        $distribution->update($validated);

        return redirect()->route('distributions.index')->with('success', 'Distribusi berhasil diperbarui!');
    }

    /**
     * Hapus distribusi dari database.
     */
    public function destroy(Distribution $distribution)
    {
        // Hanya admin yang bisa menghapus
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus distribusi ini.');
        }

        $distribution->delete();

        return redirect()->route('distributions.index')->with('success', 'Distribusi berhasil dihapus!');
    }
}