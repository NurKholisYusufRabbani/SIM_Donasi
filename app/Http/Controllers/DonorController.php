<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    /**
     * Tampilkan detail profil donor user yang sedang login.
     * Atau, jika admin, tampilkan daftar semua donor.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $donors = Donor::with('user')->latest()->paginate(10);
            return view('donors.index', compact('donors'));
        } else {
            $donor = Auth::user()->donor; // Coba dapatkan profil donor dari user yang login
            if (!$donor) {
                // Jika belum ada, arahkan ke form pembuatan profil
                return redirect()->route('donors.create')->with('info', 'Silakan lengkapi profil donatur Anda.');
            }
            return view('donors.show', compact('donor'));
        }
    }

    /**
     * Tampilkan form untuk membuat/melengkapi profil donor.
     * Hanya jika user belum memiliki profil donor.
     */
    public function create()
    {
        if (Auth::user()->donor) {
            return redirect()->route('donors.index')->with('info', 'Anda sudah memiliki profil donatur.');
        }
        return view('donors.create');
    }

    /**
     * Simpan profil donor baru (atau lengkapi).
     */
    public function store(Request $request)
    {
        // Pastikan user belum punya profil donor
        if (Auth::user()->donor) {
            return redirect()->route('donors.index')->with('error', 'Anda sudah memiliki profil donatur. Gunakan fungsi edit.');
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20|unique:donors,phone',
            'address' => 'required|string|max:500',
            'occupation' => 'nullable|string|max:255',
        ]);

        Auth::user()->donor()->create($validated); // Buat profil donor dan hubungkan ke user yang login

        // Opsional: perbarui role user menjadi 'donator'
        if (Auth::user()->role === 'user') { // Hanya ubah jika role-nya masih default 'user'
            Auth::user()->update(['role' => 'donatur']);
        }

        return redirect()->route('donors.index')->with('success', 'Profil donatur berhasil dilengkapi!');
    }

    /**
     * Tampilkan detail profil donor tertentu (khusus admin).
     */
    public function show(Donor $donor)
    {
        if (Auth::user()->role !== 'admin' && $donor->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $donor->load('user', 'donations'); // Load relasi user dan donasi
        return view('donors.show', compact('donor'));
    }


    /**
     * Tampilkan form untuk mengedit profil donor.
     */
    public function edit(Donor $donor)
    {
        // Hanya user terkait atau admin yang bisa mengedit
        if (Auth::user()->role !== 'admin' && $donor->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('donors.edit', compact('donor'));
    }

    /**
     * Perbarui profil donor di database.
     */
    public function update(Request $request, Donor $donor)
    {
        // Hanya user terkait atau admin yang bisa mengedit
        if (Auth::user()->role !== 'admin' && $donor->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20', Rule::unique('donors')->ignore($donor->id)],
            'address' => 'required|string|max:500',
            'occupation' => 'nullable|string|max:255',
        ]);

        $donor->update($validated);

        return redirect()->route('donors.index')->with('success', 'Profil donatur berhasil diperbarui!');
    }

    /**
     * Hapus profil donor dari database (khusus admin).
     */
    public function destroy(Donor $donor)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        $donor->delete();
        return redirect()->route('donors.index')->with('success', 'Profil donatur berhasil dihapus!');
    }
}