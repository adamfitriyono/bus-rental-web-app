<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Jadwal;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display search form (home page)
     */
    public function index()
    {
        // Get unique cities from rutes for dropdown
        $kotaAsal = Rute::distinct()->pluck('kota_asal')->sort();
        $kotaTujuan = Rute::distinct()->pluck('kota_tujuan')->sort();

        return view('home', [
            'kotaAsal' => $kotaAsal,
            'kotaTujuan' => $kotaTujuan
        ]);
    }

    /**
     * Search jadwal berdasarkan rute dan tanggal (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $request->validate([
            'kota_asal' => 'required|string',
            'kota_tujuan' => 'required|string|different:kota_asal',
            'tanggal' => 'required|date|after_or_equal:today'
        ]);

        $kotaAsal = $request->input('kota_asal');
        $kotaTujuan = $request->input('kota_tujuan');
        $tanggal = $request->input('tanggal');

        // Query berdasarkan dokumentasi Q-003 dan Q-004
        // Q-003: SELECT * FROM rute WHERE kota_asal=? AND kota_tujuan=?
        $rute = Rute::where('kota_asal', $kotaAsal)
                    ->where('kota_tujuan', $kotaTujuan)
                    ->where('status', 'aktif')
                    ->first();

        if (!$rute) {
            return response()->json([
                'success' => false,
                'message' => 'Rute tidak ditemukan',
                'jadwals' => []
            ]);
        }

        // Q-004: SELECT jadwal.*, bus.* FROM jadwal 
        //        JOIN bus ON jadwal.id_bus = bus.id_bus 
        //        WHERE jadwal.id_rute=? AND DATE(jadwal.tanggal)=?
        $jadwals = Jadwal::with(['bus', 'rute'])
                        ->where('id_rute', $rute->id_rute)
                        ->whereDate('tanggal', $tanggal)
                        ->where('status', 'aktif')
                        ->whereHas('bus', function($query) {
                            $query->where('status', 'aktif')
                                  ->where('kursi_tersedia', '>', 0);
                        })
                        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditemukan',
            'jadwals' => $jadwals,
            'rute' => $rute
        ]);
    }
}
