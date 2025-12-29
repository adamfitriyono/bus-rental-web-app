<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_notifikasi';
    protected $table = 'notifikasis';

    protected $fillable = [
        'id_user',
        'tipe',
        'email_penerima',
        'judul_email',
        'isi_email',
        'status_pengiriman',
        'tanggal_pengiriman'
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
