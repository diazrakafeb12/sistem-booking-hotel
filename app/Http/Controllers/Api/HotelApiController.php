<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Booking;
use App\Models\TipeKamar;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class HotelApiController extends Controller
{
    // ===== KAMAR =====
    public function kamarList()
    {
        $kamar = Kamar::with('tipeKamar')->get()->map(function($k) {
            return [
                'id'          => $k->id_kamar,
                'nomor'       => $k->nomor_kamar,
                'tipe'        => $k->tipeKamar->nama_tipe ?? '-',
                'harga'       => $k->tipeKamar->harga_per_malam ?? 0,
                'lantai'      => $k->lantai,
                'kapasitas'   => $k->kapasitas,
                'status'      => $k->status,
                'foto'        => $k->foto ? asset('storage/' . $k->foto) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $kamar,
            'total'   => $kamar->count(),
        ]);
    }

    public function kamarTersedia(Request $request)
    {
        $checkin  = $request->tgl_checkin  ?? now()->format('Y-m-d');
        $checkout = $request->tgl_checkout ?? now()->addDay()->format('Y-m-d');

        $kamarTerpesan = Booking::whereNotIn('status', ['cancelled', 'checkout'])
            ->where(function($q) use ($checkin, $checkout) {
                $q->whereBetween('tgl_checkin',  [$checkin, $checkout])
                  ->orWhereBetween('tgl_checkout', [$checkin, $checkout])
                  ->orWhere(function($q2) use ($checkin, $checkout) {
                      $q2->where('tgl_checkin', '<=', $checkin)
                         ->where('tgl_checkout', '>=', $checkout);
                  });
            })->pluck('id_kamar')->toArray();

        $kamar = Kamar::with('tipeKamar')
            ->whereNotIn('id_kamar', $kamarTerpesan)
            ->where('status', '!=', 'maintenance')
            ->get()->map(function($k) {
                return [
                    'id'        => $k->id_kamar,
                    'nomor'     => $k->nomor_kamar,
                    'tipe'      => $k->tipeKamar->nama_tipe ?? '-',
                    'harga'     => $k->tipeKamar->harga_per_malam ?? 0,
                    'lantai'    => $k->lantai,
                    'kapasitas' => $k->kapasitas,
                    'foto'      => $k->foto ? asset('storage/' . $k->foto) : null,
                ];
            });

        return response()->json([
            'success'   => true,
            'checkin'   => $checkin,
            'checkout'  => $checkout,
            'data'      => $kamar,
            'total'     => $kamar->count(),
        ]);
    }

    // ===== BOOKING =====
    public function bookingList()
    {
        $bookings = Booking::with(['tamu', 'kamar.tipeKamar', 'pembayaran'])
            ->latest()->get()->map(function($b) {
                return [
                    'id'           => $b->id_booking,
                    'kode'         => $b->kode_booking,
                    'tamu'         => $b->tamu->nama_lengkap ?? '-',
                    'no_hp'        => $b->tamu->no_hp ?? '-',
                    'kamar'        => $b->kamar->nomor_kamar ?? '-',
                    'tipe'         => $b->kamar->tipeKamar->nama_tipe ?? '-',
                    'tgl_checkin'  => $b->tgl_checkin,
                    'tgl_checkout' => $b->tgl_checkout,
                    'jumlah_malam' => $b->jumlah_malam,
                    'total_biaya'  => $b->total_biaya,
                    'status'       => $b->status,
                    'pembayaran'   => $b->pembayaran ? $b->pembayaran->status : 'belum',
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $bookings,
            'total'   => $bookings->count(),
        ]);
    }

    // ===== STATISTIK =====
    public function statistik()
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'kamar_tersedia'   => Kamar::where('status', 'tersedia')->count(),
                'kamar_terisi'     => Kamar::where('status', 'terisi')->count(),
                'kamar_maintenance'=> Kamar::where('status', 'maintenance')->count(),
                'total_kamar'      => Kamar::count(),
                'total_booking'    => Booking::count(),
                'booking_confirmed'=> Booking::where('status', 'confirmed')->count(),
                'booking_checkin'  => Booking::where('status', 'checkin')->count(),
                'pendapatan_bulan' => Pembayaran::whereMonth('tgl_bayar', now()->month)
                                        ->where('status', 'lunas')->sum('jumlah_bayar'),
                'total_pendapatan' => Pembayaran::where('status', 'lunas')->sum('jumlah_bayar'),
            ]
        ]);
    }

    // ===== TIPE KAMAR =====
    public function tipeKamar()
    {
        $tipe = TipeKamar::withCount('kamar')->get()->map(function($t) {
            return [
                'id'          => $t->id_tipe,
                'nama'        => $t->nama_tipe,
                'harga'       => $t->harga_per_malam,
                'deskripsi'   => $t->deskripsi,
                'jumlah_kamar'=> $t->kamar_count,
            ];
        });

        return response()->json(['success' => true, 'data' => $tipe]);
    }
}
