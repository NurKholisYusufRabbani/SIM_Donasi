<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor; // Diperlukan untuk relasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login
use Illuminate\Validation\Rule;

class DonationController extends Controller
{
    /**
     * Tampilkan daftar semua donasi.
     */
    public function index()
    {
        // Untuk admin: tampilkan semua donasi
        // Untuk donatur: tampilkan donasi yang dibuatnya
        if (Auth::user()->role === 'admin') {
            $donations = Donation::with('donor.user')->latest()->paginate(10);
        } else {
            $donorId = Auth::user()->donor->id ?? null;
            if (!$donorId) {
                // Handle jika user bukan donatur atau belum memiliki profil donor
                return redirect()->route('dashboard')->with('error', 'Anda belum memiliki profil donatur.');
            }
            $donations = Donation::where('donor_id', $donorId)
                                 ->with('donor.user')
                                 ->latest()
                                 ->paginate(10);
        }

        return view('donations.index', compact('donations'));
    }

    /**
     * Tampilkan form untuk membuat donasi baru.
     * Hanya admin atau user dengan role 'donator' yang bisa mengakses ini.
     */
    public function create()
    {
        // Anda bisa menambahkan middleware di route untuk ini
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'donator') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk membuat donasi.');
        }

        $donors = Donor::with('user')->get(); // Diperlukan jika admin yang mencatat donasi
        return view('donations.create', compact('donors'));
    }

    /**
     * Simpan donasi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'type' => ['required', Rule::in(['uang', 'barang'])],
            'item_details' => 'nullable|string|max:255', // opsional, tergantung 'type'
            'description' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(['pending', 'diterima', 'ditolak'])],
            'donor_id' => 'required_if:user_role,admin|exists:donors,id', // Hanya wajib jika admin yang membuat
        ]);

        // Tentukan donor_id berdasarkan user yang login atau input admin
        if (Auth::user()->role === 'admin') {
            $donorId = $validated['donor_id'];
        } else {
            $donor = Auth::user()->donor;
            if (!$donor) {
                return back()->withInput()->with('error', 'Silakan lengkapi profil donatur Anda terlebih dahulu.');
            }
            $donorId = $donor->id;
        }

        // Handle item_details: hanya wajib jika type 'barang'
        if ($validated['type'] === 'barang' && empty($validated['item_details'])) {
            return back()->withInput()->withErrors(['item_details' => 'Detail barang wajib diisi jika jenis donasi adalah barang.']);
        } elseif ($validated['type'] === 'uang') {
            // Jika 'uang', pastikan item_details kosong
            $validated['item_details'] = null;
        }

        Donation::create([
            'donor_id' => $donorId,
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'type' => $validated['type'],
            'item_details' => $validated['item_details'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('donations.index')->with('success', 'Donasi berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail donasi tertentu.
     */
    public function show(Donation $donation)
    {
        // Pastikan donatur hanya bisa melihat donasinya sendiri
        if (Auth::user()->role !== 'admin' && $donation->donor->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat donasi ini.');
        }

        $donation->load('donor.user', 'distributions.beneficiary', 'distributions.distributor'); // Load relasi

        return view('donations.show', compact('donation'));
    }

    /**
     * Tampilkan form untuk mengedit donasi.
     */
    public function edit(Donation $donation)
    {
        // Hanya admin yang bisa mengedit donasi
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit donasi ini.');
        }

        $donors = Donor::with('user')->get();
        return view('donations.edit', compact('donation', 'donors'));
    }

    /**
     * Perbarui donasi di database.
     */
    public function update(Request $request, Donation $donation)
    {
        // Hanya admin yang bisa mengedit donasi
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit donasi ini.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'type' => ['required', Rule::in(['uang', 'barang'])],
            'item_details' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(['pending', 'diterima', 'ditolak'])],
            'donor_id' => 'required|exists:donors,id', // Wajib untuk admin
        ]);

        // Handle item_details
        if ($validated['type'] === 'barang' && empty($validated['item_details'])) {
            return back()->withInput()->withErrors(['item_details' => 'Detail barang wajib diisi jika jenis donasi adalah barang.']);
        } elseif ($validated['type'] === 'uang') {
            $validated['item_details'] = null;
        }

        $donation->update($validated);

        return redirect()->route('donations.index')->with('success', 'Donasi berhasil diperbarui!');
    }

    /**
     * Hapus donasi dari database.
     */
    public function destroy(Donation $donation)
    {
        // Hanya admin yang bisa menghapus donasi
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus donasi ini.');
        }

        $donation->delete();

        return redirect()->route('donations.index')->with('success', 'Donasi berhasil dihapus!');
    }
}