<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelApiController;

Route::prefix('hotel')->group(function () {
    Route::get('/kamar',          [HotelApiController::class, 'kamarList']);
    Route::get('/kamar/tersedia', [HotelApiController::class, 'kamarTersedia']);
    Route::get('/booking',        [HotelApiController::class, 'bookingList']);
    Route::get('/statistik',      [HotelApiController::class, 'statistik']);
    Route::get('/tipe-kamar',     [HotelApiController::class, 'tipeKamar']);
});