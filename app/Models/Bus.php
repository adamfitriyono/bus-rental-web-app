<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_bus';
    protected $table = 'buses';

    protected $fillable = [
        'nama_bus',
        'jenis_kelas',
        'plat_nomor',
        'kapasitas_kursi',
        'kursi_tersedia',
        'tahun_pembuatan',
        'fasilitas',
        'status'
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_bus', 'id_bus');
    }

    public function kursis()
    {
        return $this->hasMany(Kursi::class, 'id_bus', 'id_bus');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_bus', 'id_bus');
    }
}
