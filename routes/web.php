<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DonorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk Donasi
    Route::resource('donations', DonationController::class);

    // Routes untuk Distribusi
    Route::resource('distributions', DistributionController::class);

    // Routes untuk Beneficiary
    Route::resource('beneficiaries', BeneficiaryController::class);

    // Routes untuk Donor
    // Gunakan only untuk membatasi aksi jika tidak semua resource actions diperlukan
    Route::resource('donors', DonorController::class)->except(['show']); // Karena show sudah ditangani di index untuk non-admin
    Route::get('donors/{donor}', [DonorController::class, 'show'])->name('donors.show'); // Definisi eksplisit untuk show
});


// Rute KHUSUS ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Rute untuk manajemen Beneficiary (hanya admin)
    Route::resource('beneficiaries', BeneficiaryController::class);

    // Rute untuk manajemen User (opsional, jika admin bisa mengelola user lain)
    // Akan kita buat controller-nya nanti
    Route::resource('users', UserController::class)->except(['create', 'store']); // Admin tidak mendaftar user baru lewat sini
    // Tambahkan rute untuk mengubah role user jika diperlukan
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

require __DIR__.'/auth.php';