<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
    // Tambahkan constructor untuk middleware jika ingin semua method diautentikasi/otorisasi
    public function __construct()
    {
        // Contoh: hanya admin yang bisa mengelola beneficiaries
        $this->middleware('auth');
        $this->middleware('admin'); // Middleware kustom untuk role admin
    }

    /**
     * Tampilkan daftar semua penerima donasi.
     */
    public function index()
    {
        $beneficiaries = Beneficiary::latest()->paginate(10);
        return view('beneficiaries.index', compact('beneficiaries'));
    }

    /**
     * Tampilkan form untuk membuat penerima baru.
     */
    public function create()
    {
        return view('beneficiaries.create');
    }

    /**
     * Simpan penerima baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:255', // Contoh: 'Panti Asuhan', 'Individu Miskin'
        ]);

        Beneficiary::create($validated);

        return redirect()->route('beneficiaries.index')->with('success', 'Penerima donasi berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail penerima donasi tertentu.
     */
    public function show(Beneficiary $beneficiary)
    {
        $beneficiary->load('distributions'); // Load relasi distribusi jika ingin melihat riwayat penerimaan
        return view('beneficiaries.show', compact('beneficiary'));
    }

    /**
     * Tampilkan form untuk mengedit penerima donasi.
     */
    public function edit(Beneficiary $beneficiary)
    {
        return view('beneficiaries.edit', compact('beneficiary'));
    }

    /**
     * Perbarui penerima donasi di database.
     */
    public function update(Request $request, Beneficiary $beneficiary)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:255',
        ]);

        $beneficiary->update($validated);

        return redirect()->route('beneficiaries.index')->with('success', 'Penerima donasi berhasil diperbarui!');
    }

    /**
     * Hapus penerima donasi dari database.
     */
    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->delete();

        return redirect()->route('beneficiaries.index')->with('success', 'Penerima donasi berhasil dihapus!');
    }
}