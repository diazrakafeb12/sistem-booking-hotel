<?php
// Routes: admin, ceo, customer role-based access

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TipeKamarController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Dashboard — semua role
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // Profile — semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Semua role — hanya lihat kamar & tipe kamar
Route::get('tipe-kamar', [TipeKamarController::class, 'index'])->name('tipe-kamar.index');
Route::get('kamar', [KamarController::class, 'index'])->name('kamar.index');

// Semua role — booking
Route::post('booking/cek-ketersediaan', [BookingController::class, 'cekKetersediaan'])->name('booking.cek');
Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
Route::get('booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');

// CEO only
Route::middleware('role:ceo')->group(function () {
    Route::get('laporan/pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
});

// CEO & Admin
Route::middleware('role:ceo,admin')->group(function () {
    Route::resource('tipe-kamar', TipeKamarController::class)->except(['index']);
    Route::resource('kamar', KamarController::class)->except(['index']);
    Route::resource('tamu', TamuController::class);

    Route::resource('booking', BookingController::class)->except(['edit', 'update', 'index', 'show', 'create', 'store']);
    Route::patch('booking/{booking}/checkin', [BookingController::class, 'checkIn'])->name('booking.checkin');
    Route::patch('booking/{booking}/checkout', [BookingController::class, 'checkOut'])->name('booking.checkout');

    Route::resource('pembayaran', PembayaranController::class)->except(['edit', 'update']);

    Route::get('laporan/booking', [LaporanController::class, 'booking'])->name('laporan.booking');
    Route::get('laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
});

});

require __DIR__.'/auth.php';