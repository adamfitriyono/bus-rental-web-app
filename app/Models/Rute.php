<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_rute';
    protected $table = 'rutes';

    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'jarak_km',
        'estimasi_jam',
        'harga_base',
        'status'
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_rute', 'id_rute');
    }
}
