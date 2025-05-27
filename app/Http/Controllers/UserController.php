<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk update password jika diperlukan
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware admin diterapkan di sini
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Tampilkan daftar semua user.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan detail user tertentu.
     */
    public function show(User $user)
    {
        $user->load('donor', 'distributions'); // Load relasi jika perlu
        return view('users.show', compact('user'));
    }

    /**
     * Tampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        // Admin tidak boleh mengedit role user lain menjadi admin, kecuali dirinya sendiri.
        // Atau, Anda bisa membuat logika yang lebih canggih di sini.
        $roles = ['user', 'donator', 'admin', 'distributor']; // Sesuaikan dengan role yang Anda miliki
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Perbarui user di database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // 'password' => 'nullable|string|min:8|confirmed', // Jika ingin admin bisa update password
            'role' => ['required', Rule::in(['user', 'donator', 'admin', 'distributor'])], // Validasi role
        ]);

        // Jika ada password baru
        // if (!empty($validated['password'])) {
        //     $validated['password'] = Hash::make($validated['password']);
        // } else {
        //     unset($validated['password']); // Jangan update password jika kosong
        // }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Endpoint khusus untuk update role user.
     */
    public function updateRole(Request $request, User $user)
    {
        // Pastikan admin tidak menurunkan atau menaikkan role admin lain jika tidak diizinkan
        if (Auth::id() !== $user->id && $user->role === 'admin' && $request->role !== 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role admin lain.');
        }
        if (Auth::id() !== $user->id && $request->role === 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role pengguna lain menjadi admin.');
        }


        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'donator', 'admin', 'distributor'])],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('users.index')->with('success', 'Role pengguna berhasil diperbarui!');
    }


    /**
     * Hapus user dari database.
     */
    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}