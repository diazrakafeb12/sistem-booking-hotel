<?php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Booking;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['booking.tamu', 'booking.kamar'])->latest()->get();
        return view('pembayaran.index', compact('pembayaran'));
    }

   public function create(Request $request)
{
    $booking = null;
    $sisaTagihan = 0;
    $isPelunasan = false;

    if ($request->booking_id) {
        $booking = Booking::with(['tamu', 'kamar.tipeKamar', 'pembayaran'])
                          ->findOrFail($request->booking_id);

        if ($request->pelunasan && $booking->pembayaran) {
            $sisaTagihan = $booking->total_biaya - $booking->pembayaran->jumlah_bayar;
            $isPelunasan = true;
        }
    }

    // Ambil booking yang belum lunas, sertakan info pembayaran DP
    $bookings = Booking::with(['tamu', 'pembayaran'])
                ->whereDoesntHave('pembayaran', function($q) {
                    $q->where('status', 'lunas');
                })
                ->whereNotIn('status', ['cancelled'])
                ->get()
                ->map(function($b) {
                    // Jika sudah ada DP, tampilkan sisa tagihan
                    $sudahBayar = $b->pembayaran ? $b->pembayaran->jumlah_bayar : 0;
                    $sisa = $b->total_biaya - $sudahBayar;
                    $b->sisa_tagihan = $sisa;
                    $b->sudah_bayar  = $sudahBayar;
                    return $b;
                });

    return view('pembayaran.create', compact('booking', 'bookings', 'sisaTagihan', 'isPelunasan'));
}
   public function store(Request $request)
{
    $request->validate([
        'id_booking'   => 'required|exists:bookings,id_booking',
        'jumlah_bayar' => 'required|numeric|min:1',
        'metode'       => 'required|in:cash,transfer,kartu',
        'status'       => 'required|in:lunas,belum_lunas,dp',
        'keterangan'   => 'nullable|string',
    ]);

    $booking = Booking::with('pembayaran')->findOrFail($request->id_booking);

    // Jika sudah ada pembayaran DP sebelumnya → UPDATE, bukan CREATE baru
    if ($booking->pembayaran && $booking->pembayaran->status == 'dp') {
        $booking->pembayaran->update([
            'jumlah_bayar' => $booking->total_biaya, // update jadi total penuh
            'metode'       => $request->metode,
            'status'       => 'lunas',
            'tgl_bayar'    => now(),
            'keterangan'   => $request->keterangan,
        ]);
    } else {
        // Belum ada pembayaran sama sekali → CREATE baru
        Pembayaran::create([
            'id_booking'   => $request->id_booking,
            'jumlah_bayar' => $request->jumlah_bayar,
            'metode'       => $request->metode,
            'status'       => $request->status,
            'tgl_bayar'    => now(),
            'keterangan'   => $request->keterangan,
        ]);
    }

    // Redirect balik ke detail booking jika ada, atau ke index pembayaran
    return redirect()->route('booking.show', $request->id_booking)
                     ->with('success', 'Pembayaran berhasil dicatat!');
}

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['booking.tamu', 'booking.kamar.tipeKamar']);
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')
                         ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}