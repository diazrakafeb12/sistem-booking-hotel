<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function booking(Request $request)
    {
        $query = Booking::with(['tamu', 'kamar.tipeKamar']);

        if ($request->tgl_dari) {
            $query->whereDate('created_at', '>=', $request->tgl_dari);
        }
        if ($request->tgl_sampai) {
            $query->whereDate('created_at', '<=', $request->tgl_sampai);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings      = $query->latest()->get();
        $totalBooking  = $bookings->count();
        $totalPendapatan = $bookings->whereIn('status', ['checkin','checkout'])->sum('total_biaya');

        return view('laporan.booking', compact('bookings', 'totalBooking', 'totalPendapatan'));
    }

    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with(['booking.tamu', 'booking.kamar']);

        if ($request->tgl_dari) {
            $query->whereDate('tgl_bayar', '>=', $request->tgl_dari);
        }
        if ($request->tgl_sampai) {
            $query->whereDate('tgl_bayar', '<=', $request->tgl_sampai);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pembayaran   = $query->latest()->get();
        $totalLunas   = $pembayaran->where('status', 'lunas')->sum('jumlah_bayar');
        $totalDP      = $pembayaran->where('status', 'dp')->sum('jumlah_bayar');
        $totalBelum   = $pembayaran->where('status', 'belum_lunas')->sum('jumlah_bayar');

        return view('laporan.pembayaran', compact('pembayaran', 'totalLunas', 'totalDP', 'totalBelum'));
    }

    public function pendapatan(Request $request)
    {
$bulan = (int) ($request->bulan ?? now()->month);
$tahun = (int) ($request->tahun ?? now()->year);

        $pembayaran = Pembayaran::with(['booking.tamu', 'booking.kamar'])
                        ->where('status', 'lunas')
                        ->whereMonth('tgl_bayar', $bulan)
                        ->whereYear('tgl_bayar', $tahun)
                        ->latest()
                        ->get();

        $totalPendapatan = $pembayaran->sum('jumlah_bayar');

        // Data per hari untuk grafik
        $perHari = $pembayaran->groupBy(function($p) {
            return Carbon::parse($p->tgl_bayar)->format('d');
        })->map->sum('jumlah_bayar');

        return view('laporan.pendapatan', compact('pembayaran', 'totalPendapatan', 'perHari', 'bulan', 'tahun'));
    }
}