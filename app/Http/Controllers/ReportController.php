<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan penjualan
     */
    public function penjualan(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari'
        ]);

        $laporan = DB::table('pemesanans')
                    ->join('jadwals', 'jadwals.id_jadwal', '=', 'pemesanans.id_jadwal')
                    ->join('rutes', 'rutes.id_rute', '=', 'jadwals.id_rute')
                    ->join('buses', 'buses.id_bus', '=', 'pemesanans.id_bus')
                    ->join('users', 'users.id', '=', 'pemesanans.id_user')
                    ->join('pembayarans', 'pembayarans.id_pemesanan', '=', 'pemesanans.id_pemesanan')
                    ->whereBetween('pemesanans.tanggal_pemesanan', [$request->dari, $request->sampai])
                    ->where('pemesanans.status_pemesanan', '!=', 'dibatalkan')
                    ->where('pembayarans.status_pembayaran', 'Lunas')
                    ->select(
                        'pemesanans.id_pemesanan',
                        'users.name as nama_user',
                        'rutes.kota_asal',
                        'rutes.kota_tujuan',
                        'jadwals.tanggal',
                        'buses.nama_bus',
                        'pemesanans.jumlah_kursi',
                        'pemesanans.nomor_kursi',
                        'pembayarans.jumlah as total_pembayaran',
                        'pembayarans.metode_pembayaran',
                        'pemesanans.tanggal_pemesanan'
                    )
                    ->get();

        $total = $laporan->sum('total_pembayaran');

        return view('admin.laporan.penjualan', [
            'laporan' => $laporan,
            'total' => $total,
            'dari' => $request->dari,
            'sampai' => $request->sampai
        ]);
    }

    /**
     * Laporan keuangan
     */
    public function keuangan(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari'
        ]);

        // Pendapatan
        $pendapatan = Pembayaran::where('status_pembayaran', 'Lunas')
                                ->whereBetween('tanggal_pembayaran', [$request->dari, $request->sampai])
                                ->sum('jumlah');

        // Breakdown by metode pembayaran
        $byMetode = Pembayaran::where('status_pembayaran', 'Lunas')
                              ->whereBetween('tanggal_pembayaran', [$request->dari, $request->sampai])
                              ->select('metode_pembayaran', DB::raw('SUM(jumlah) as total'))
                              ->groupBy('metode_pembayaran')
                              ->get();

        // Total tiket terjual
        $totalTiket = Pemesanan::whereHas('pembayaran', function($q) use ($request) {
                                $q->where('status_pembayaran', 'Lunas')
                                  ->whereBetween('tanggal_pembayaran', [$request->dari, $request->sampai]);
                            })
                            ->sum('jumlah_kursi');

        return view('admin.laporan.keuangan', [
            'pendapatan' => $pendapatan,
            'byMetode' => $byMetode,
            'totalTiket' => $totalTiket,
            'dari' => $request->dari,
            'sampai' => $request->sampai
        ]);
    }

    /**
     * Laporan occupancy (tingkat penempatan kursi)
     */
    public function occupancy(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari'
        ]);

        $laporan = DB::table('jadwals')
                    ->join('rutes', 'rutes.id_rute', '=', 'jadwals.id_rute')
                    ->join('buses', 'buses.id_bus', '=', 'jadwals.id_bus')
                    ->leftJoin('pemesanans', function($join) {
                        $join->on('pemesanans.id_jadwal', '=', 'jadwals.id_jadwal')
                             ->where('pemesanans.status_pemesanan', '!=', 'dibatalkan');
                    })
                    ->leftJoin('pembayarans', function($join) {
                        $join->on('pembayarans.id_pemesanan', '=', 'pemesanans.id_pemesanan')
                             ->where('pembayarans.status_pembayaran', '=', 'Lunas');
                    })
                    ->whereBetween('jadwals.tanggal', [$request->dari, $request->sampai])
                    ->select(
                        'jadwals.id_jadwal',
                        'rutes.kota_asal',
                        'rutes.kota_tujuan',
                        'jadwals.tanggal',
                        'jadwals.jam_berangkat',
                        'buses.nama_bus',
                        'buses.kapasitas_kursi',
                        DB::raw('COALESCE(SUM(pemesanans.jumlah_kursi), 0) as kursi_terisi')
                    )
                    ->groupBy('jadwals.id_jadwal', 'rutes.kota_asal', 'rutes.kota_tujuan', 
                             'jadwals.tanggal', 'jadwals.jam_berangkat', 'buses.nama_bus', 'buses.kapasitas_kursi')
                    ->get()
                    ->map(function($item) {
                        $item->occupancy_rate = $item->kapasitas_kursi > 0 
                            ? ($item->kursi_terisi / $item->kapasitas_kursi) * 100 
                            : 0;
                        return $item;
                    });

        return view('admin.laporan.occupancy', [
            'laporan' => $laporan,
            'dari' => $request->dari,
            'sampai' => $request->sampai
        ]);
    }
}
