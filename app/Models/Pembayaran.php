<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pembayaran';
    protected $table = 'pembayarans';

    protected $fillable = [
        'id_pemesanan',
        'metode_pembayaran',
        'jumlah',
        'status_pembayaran',
        'kode_transaksi',
        'referensi_eksternal',
        'bukti_transfer',
        'tanggal_pembayaran'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
