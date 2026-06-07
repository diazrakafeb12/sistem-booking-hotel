<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $table = 'tamu';
    protected $primaryKey = 'id_tamu';
    protected $fillable = ['nama_lengkap', 'nik', 'no_hp', 'email', 'alamat'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_tamu', 'id_tamu');
    }
}