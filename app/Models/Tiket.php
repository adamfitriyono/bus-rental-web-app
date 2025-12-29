<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tiket';
    protected $table = 'tikets';

    protected $fillable = [
        'kode_tiket',
        'id_pemesanan',
        'qr_code',
        'file_pdf',
        'status_tiket'
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
