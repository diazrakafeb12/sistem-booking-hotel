<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    protected $fillable = ['nomor_kamar', 'id_tipe', 'lantai', 'kapasitas', 'foto', 'foto_2', 'foto_3', 'status'];

    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'id_tipe', 'id_tipe');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_kamar', 'id_kamar');
    }
}