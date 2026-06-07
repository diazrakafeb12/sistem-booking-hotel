<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
{
    $kamarTersedia = \App\Models\Kamar::where('status', 'tersedia')->count();
    $totalTipe     = \App\Models\TipeKamar::count();
    $totalBooking  = \App\Models\Booking::count();
    $tipeKamar     = \App\Models\TipeKamar::with('kamar')->get();
    $kamar         = \App\Models\Kamar::with('tipeKamar')
                        ->where('status', 'tersedia')
                        ->get();

    return view('booking.index', compact(
        'kamarTersedia', 'totalTipe', 'totalBooking', 'tipeKamar', 'kamar'
    ));
}

    public function create()
    {
        $tamu  = Tamu::orderBy('nama_lengkap')->get();
        $kamar = Kamar::with('tipeKamar')->get(); // ambil semua kamar dulu
        return view('booking.create', compact('tamu', 'kamar'));
    }

    // Method AJAX cek ketersediaan kamar berdasarkan tanggal
    public function cekKetersediaan(Request $request)
    {
        $request->validate([
            'tgl_checkin'  => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
        ]);

        $checkin  = $request->tgl_checkin;
        $checkout = $request->tgl_checkout;

        // Cari id kamar yang sudah dibooking pada rentang tanggal tersebut
        $kamarTerpesan = Booking::whereNotIn('status', ['cancelled', 'checkout'])
            ->where(function($q) use ($checkin, $checkout) {
                $q->whereBetween('tgl_checkin',  [$checkin, $checkout])
                  ->orWhereBetween('tgl_checkout', [$checkin, $checkout])
                  ->orWhere(function($q2) use ($checkin, $checkout) {
                      $q2->where('tgl_checkin', '<=', $checkin)
                         ->where('tgl_checkout', '>=', $checkout);
                  });
            })->pluck('id_kamar')->toArray();

        // Ambil semua kamar beserta status ketersediaan
        $kamar = Kamar::with('tipeKamar')->get()->map(function($k) use ($kamarTerpesan) {
            return [
                'id_kamar'    => $k->id_kamar,
                'nomor_kamar' => $k->nomor_kamar,
                'tipe'        => $k->tipeKamar->nama_tipe ?? '-',
                'harga'       => $k->tipeKamar->harga_per_malam ?? 0,
                'harga_format'=> 'Rp ' . number_format($k->tipeKamar->harga_per_malam ?? 0, 0, ',', '.'),
                'kapasitas'   => $k->kapasitas,
                'lantai'      => $k->lantai,
                'foto'        => $k->foto ? asset('storage/' . $k->foto) : null,
                'status_kamar'=> $k->status,
                'tersedia'    => !in_array($k->id_kamar, $kamarTerpesan) && $k->status != 'maintenance',
            ];
        });

        return response()->json($kamar);
    }

 public function store(Request $request)
{
    $user = auth()->user();

    // Validasi berbeda untuk customer vs admin/ceo
$request->validate([
    'nama_tamu'    => 'required|string|max:150',
    'no_hp'        => 'required|string|max:15',
    'email'        => 'nullable|email|max:100',
    'id_kamar'     => 'required|exists:kamar,id_kamar',
    'tgl_checkin'  => 'required|date|after_or_equal:today',
    'tgl_checkout' => 'required|date|after:tgl_checkin',
    'catatan'      => 'nullable|string',
    'jumlah_bayar' => 'required|numeric|min:1',
    'metode_bayar' => 'required|in:cash,transfer,kartu',
    'status_bayar' => 'required|in:lunas,dp',
]);

    // Cek bentrok kamar
    $bentrok = Booking::where('id_kamar', $request->id_kamar)
        ->whereNotIn('status', ['cancelled', 'checkout'])
        ->where(function($q) use ($request) {
            $q->whereBetween('tgl_checkin',  [$request->tgl_checkin, $request->tgl_checkout])
              ->orWhereBetween('tgl_checkout', [$request->tgl_checkin, $request->tgl_checkout])
              ->orWhere(function($q2) use ($request) {
                  $q2->where('tgl_checkin', '<=', $request->tgl_checkin)
                     ->where('tgl_checkout', '>=', $request->tgl_checkout);
              });
        })->exists();

    if ($bentrok) {
        return back()->withErrors(['id_kamar' => 'Maaf, kamar tersebut telah dibooking pada tanggal yang dipilih!'])->withInput();
    }

    // Tentukan data tamu berdasarkan role
  // BENAR - pakai nama dari form
if ($user->role === 'customer') {
    $tamu = \App\Models\Tamu::firstOrCreate(
        ['email' => $user->email],
        ['nama_lengkap' => $request->nama_tamu, 'no_hp' => $request->no_hp]
    );
    $tamu->update([
        'nama_lengkap' => $request->nama_tamu,
        'no_hp'        => $request->no_hp,
        'email'        => $user->email,
    ]);
    
    } else {
        $tamu = \App\Models\Tamu::firstOrCreate(
            ['nama_lengkap' => $request->nama_tamu],
            ['no_hp' => $request->no_hp, 'email' => $request->email]
        );
        $tamu->update([
            'no_hp' => $request->no_hp,
            'email' => $request->email ?? $tamu->email,
        ]);
    }

    $kamar       = Kamar::findOrFail($request->id_kamar);
    $checkin     = Carbon::parse($request->tgl_checkin);
    $checkout    = Carbon::parse($request->tgl_checkout);
    $jumlahMalam = $checkin->diffInDays($checkout);
    $totalBiaya  = $jumlahMalam * $kamar->tipeKamar->harga_per_malam;
    $kodeBooking = 'BK-' . strtoupper(substr(uniqid(), -6));

    $booking = Booking::create([
        'kode_booking' => $kodeBooking,
        'id_tamu'      => $tamu->id_tamu,
        'id_kamar'     => $request->id_kamar,
        'tgl_checkin'  => $request->tgl_checkin,
        'tgl_checkout' => $request->tgl_checkout,
        'jumlah_malam' => $jumlahMalam,
        'total_biaya'  => $totalBiaya,
        'status'       => 'confirmed',
        'catatan'      => $request->catatan,
    ]);

    \App\Models\Pembayaran::create([
        'id_booking'   => $booking->id_booking,
        'jumlah_bayar' => $request->jumlah_bayar,
        'metode'       => $request->metode_bayar,
        'status'       => $request->status_bayar,
        'tgl_bayar'    => now(),
        'keterangan'   => 'Pembayaran saat booking',
    ]);

    // Redirect berbeda untuk customer vs admin
    if ($user->role === 'customer') {
        return redirect()->route('booking.show', $booking->id_booking)
                         ->with('success', 'Booking berhasil! Kode: ' . $kodeBooking);
    }

    return redirect()->route('tamu.index')
                     ->with('success', 'Booking berhasil! Kode: ' . $kodeBooking . ' — Pembayaran tercatat.');
}
    public function show(Booking $booking)
    {
        $booking->load(['tamu', 'kamar.tipeKamar', 'pembayaran']);
        return view('booking.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function checkIn(Booking $booking)
    {
        $booking->update(['status' => 'checkin']);
        $booking->kamar->update(['status' => 'terisi']);
        return redirect()->back()->with('success', 'Check-In berhasil dikonfirmasi!');
    }

    public function checkOut(Booking $booking)
{
    $booking->load('pembayaran');

    // Cek apakah pembayaran sudah lunas
    if (!$booking->pembayaran || $booking->pembayaran->status !== 'lunas') {
        return redirect()->back()->with('error', 'Check-Out gagal! Tamu harus melunasi pembayaran terlebih dahulu.');
    }

    $booking->update(['status' => 'checkout']);
    $booking->kamar->update(['status' => 'tersedia']);

    return redirect()->back()->with('success', 'Check-Out berhasil! Kamar kembali tersedia.');
}
}