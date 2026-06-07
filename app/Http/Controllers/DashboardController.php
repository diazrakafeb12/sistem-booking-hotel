<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function index()
{
    $user  = auth()->user();
    $today = today();

    if ($user->role === 'customer') {
        $tamu = \App\Models\Tamu::where('email', $user->email)->first();
        $data = [
            'booking_saya'    => $tamu ? \App\Models\Booking::with(['kamar.tipeKamar', 'pembayaran'])
                                    ->where('id_tamu', $tamu->id_tamu)->latest()->get()
                                : collect(),
            'total_booking'   => $tamu ? \App\Models\Booking::where('id_tamu', $tamu->id_tamu)->count() : 0,
            'booking_aktif'   => $tamu ? \App\Models\Booking::where('id_tamu', $tamu->id_tamu)
                                    ->whereIn('status', ['confirmed', 'checkin'])->count() : 0,
            'total_bayar'     => $tamu ? \App\Models\Pembayaran::whereHas('booking', fn($q) =>
                                    $q->where('id_tamu', $tamu->id_tamu))->where('status','lunas')
                                    ->sum('jumlah_bayar') : 0,
        ];
        return view('dashboard-customer', compact('data'));
    }

    // CEO & Admin
    $data = [
        'total_kamar'        => \App\Models\Kamar::count(),
        'kamar_tersedia'     => \App\Models\Kamar::where('status', 'tersedia')->count(),
        'kamar_terisi'       => \App\Models\Kamar::where('status', 'terisi')->count(),
        'kamar_maintenance'  => \App\Models\Kamar::where('status', 'maintenance')->count(),
        'booking_pending'    => \App\Models\Booking::where('status', 'confirmed')->count(),
        'pendapatan_bulan'   => \App\Models\Pembayaran::whereMonth('tgl_bayar', now()->month)
                                    ->whereYear('tgl_bayar', now()->year)
                                    ->where('status', 'lunas')->sum('jumlah_bayar'),
        'total_pendapatan'   => \App\Models\Pembayaran::where('status', 'lunas')->sum('jumlah_bayar'),
        'booking_terbaru'    => \App\Models\Booking::with(['tamu', 'kamar.tipeKamar'])->latest()->take(5)->get(),
        // Data grafik pendapatan 6 bulan terakhir
        'grafik_pendapatan'  => collect(range(5, 0))->map(function($i) {
            $bulan = now()->subMonths($i);
            return [
                'bulan'  => $bulan->translatedFormat('M Y'),
                'total'  => \App\Models\Pembayaran::whereMonth('tgl_bayar', $bulan->month)
                                ->whereYear('tgl_bayar', $bulan->year)
                                ->where('status', 'lunas')->sum('jumlah_bayar'),
            ];
        }),
        // Data grafik booking per status
        'grafik_status'      => [
            'confirmed' => \App\Models\Booking::where('status', 'confirmed')->count(),
            'checkin'   => \App\Models\Booking::where('status', 'checkin')->count(),
            'checkout'  => \App\Models\Booking::where('status', 'checkout')->count(),
            'cancelled' => \App\Models\Booking::where('status', 'cancelled')->count(),
        ],
    ];

    return view('dashboard', compact('data'));
}
}