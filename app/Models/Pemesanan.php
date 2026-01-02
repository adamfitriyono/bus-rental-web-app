<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pemesanan';
    protected $table = 'pemesanans';

    protected $fillable = [
        'id_user',
        'id_jadwal',
        'id_bus',
        'nomor_kursi',
        'jumlah_kursi',
        'total_harga',
        'status_pemesanan',
        'tanggal_pemesanan'
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus', 'id_bus');
    }

    public function penumpangs()
    {
        return $this->hasMany(Penumpang::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function tiket()
    {
        return $this->hasOne(Tiket::class, 'id_pemesanan', 'id_pemesanan');
    }
}
