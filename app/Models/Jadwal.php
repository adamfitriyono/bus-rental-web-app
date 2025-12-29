<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal';
    protected $table = 'jadwals';

    protected $fillable = [
        'id_rute',
        'id_bus',
        'jam_berangkat',
        'jam_tiba',
        'tanggal',
        'harga_tiket',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'id_rute', 'id_rute');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus', 'id_bus');
    }

    public function kursis()
    {
        return $this->hasMany(Kursi::class, 'id_jadwal', 'id_jadwal');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_jadwal', 'id_jadwal');
    }
}
