<?php
// Model Booking: relasi ke Tamu, Kamar, Pembayaran
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id_booking';
    protected $fillable = [
        'kode_booking', 'id_tamu', 'id_kamar',
        'tgl_checkin', 'tgl_checkout', 'jumlah_malam',
        'total_biaya', 'status', 'catatan'
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_booking', 'id_booking');
    }
}