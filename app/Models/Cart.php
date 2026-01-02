<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts'; // Pastikan nama tabel sesuai dengan database
    protected $fillable = [
        'user_id', 'alat_id', 'durasi', 'created_at', 'updated_at'
    ];
}
