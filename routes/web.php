<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DonorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

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
    // Jika pengguna sudah login, arahkan ke dashboard, jika belum, ke login.
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk Donasi [cite: 23, 27, 31, 34, 38, 40, 43]
    // Otorisasi lebih detail (siapa bisa apa) ditangani di dalam DonationController
    Route::resource('donations', DonationController::class);

    // Routes untuk Distribusi [cite: 71, 74, 77, 80, 82, 84, 86]
    // Otorisasi lebih detail ditangani di dalam DistributionController
    Route::resource('distributions', DistributionController::class);

    // Routes untuk Donor [cite: 46, 49, 53, 57, 60, 63, 67]
    // Logika 'show' bisa di-custom dalam controller jika diperlukan.
    // Jika DonorController@index sudah menangani kasus "profil sendiri" untuk non-admin,
    // maka definisi 'show' terpisah mungkin untuk admin melihat detail.
    Route::resource('donors', DonorController::class);
    // Jika Anda memiliki logika khusus untuk show yang berbeda dari resource standar,
    // atau jika index tidak meng-cover show case untuk non-admin, maka definisi eksplisit dipertahankan.
    // Jika tidak, Route::resource('donors', DonorController::class); sudah cukup.
    // Contoh dari Anda:
    // Route::resource('donors', DonorController::class)->except(['show']);
    // Route::get('donors/{donor}', [DonorController::class, 'show'])->name('donors.show');

});


// Rute KHUSUS ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Rute untuk manajemen Beneficiary (hanya admin) [cite: 88, 93, 96, 99, 102, 104, 106, 108]
    Route::resource('beneficiaries', BeneficiaryController::class);

    // Rute untuk manajemen User (hanya admin) [cite: 110, 114, 118, 121, 125, 127]
    Route::resource('users', UserController::class)->except(['create', 'store']); // Admin tidak mendaftar user baru lewat sini
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // Rute untuk Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/donations-summary', [ReportController::class, 'donationSummary'])->name('reports.donations.summary');
    Route::get('/reports/distributions-summary', [ReportController::class, 'distributionSummary'])->name('reports.distributions.summary');

});

require __DIR__.'/auth.php';