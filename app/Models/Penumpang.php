<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penumpang';
    protected $table = 'penumpangs';

    protected $fillable = [
        'id_pemesanan',
        'nama_penumpang',
        'tipe_identitas',
        'nomor_identitas',
        'nomor_hp'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
