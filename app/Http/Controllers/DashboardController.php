<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * User dashboard with statistics
     */
    public function index()
    {
        $user = Auth::user();
        
        $totalTiket = Pemesanan::where('id_user', $user->id)->count();
        $tiketMendatang = Pemesanan::where('id_user', $user->id)
                                   ->whereHas('jadwal', function($query) {
                                       $query->whereDate('tanggal', '>=', Carbon::today());
                                   })
                                   ->where('status_pemesanan', '!=', 'dibatalkan')
                                   ->count();
        $tiketLampau = Pemesanan::where('id_user', $user->id)
                                ->whereHas('jadwal', function($query) {
                                    $query->whereDate('tanggal', '<', Carbon::today());
                                })
                                ->count();
        $pendingBookings = Pemesanan::where('id_user', $user->id)
            ->where('status_pemesanan', 'menunggu_pembayaran')
            ->count();
        $confirmedBookings = Pemesanan::where('id_user', $user->id)
            ->where('status_pemesanan', 'dikonfirmasi')
            ->count();
        $upcomingTrips = $tiketMendatang;
        $upcomingBookings = Pemesanan::with(['jadwal.rute', 'bus', 'penumpangs', 'pembayaran'])
            ->where('id_user', $user->id)
            ->whereHas('jadwal', function($query) {
                $query->whereDate('tanggal', '>=', Carbon::today());
            })
            ->where('status_pemesanan', '!=', 'dibatalkan')
            ->orderBy('tanggal_pemesanan', 'ASC')
            ->get();

        // Recent activities
        $recentActivities = Pemesanan::with(['jadwal.rute', 'bus', 'pembayaran'])
            ->where('id_user', $user->id)
            ->orderBy('tanggal_pemesanan', 'DESC')
            ->limit(5)
            ->get();

        return view('user.dashboard', [
            'user' => $user,
            'totalTiket' => $totalTiket,
            'tiketMendatang' => $tiketMendatang,
            'tiketLampau' => $tiketLampau,
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            'upcomingTrips' => $upcomingTrips,
            'upcomingBookings' => $upcomingBookings,
            'recentActivities' => $recentActivities
        ]);
    }

    /**
     * History of pemesanan
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = Pemesanan::with(['jadwal.rute', 'bus', 'penumpangs', 'pembayaran'])
                         ->where('id_user', $user->id);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pemesanan', $request->status);
        }

        // Filter by date
        if ($request->has('tanggal_dari')) {
            $query->whereHas('jadwal', function($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->tanggal_dari);
            });
        }

        if ($request->has('tanggal_sampai')) {
            $query->whereHas('jadwal', function($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->tanggal_sampai);
            });
        }

        $pemesanans = $query->orderBy('tanggal_pemesanan', 'DESC')->paginate(10);

        return view('user.history', [
            'pemesanans' => $pemesanans
        ]);
    }

    /**
     * Ticket detail
     */
    public function ticketDetail($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal.rute', 'bus', 'penumpangs', 'pembayaran', 'tiket'])
                              ->findOrFail($pemesanan_id);

        // Check ownership
        if ($pemesanan->id_user !== Auth::id()) {
            abort(403);
        }

        return view('user.ticket-detail', [
            'pemesanan' => $pemesanan
        ]);
    }

    /**
     * Cancel booking with refund logic
     */
    public function cancelBooking($pemesanan_id)
    {
        $pemesanan = Pemesanan::with(['jadwal', 'bus', 'pembayaran'])->findOrFail($pemesanan_id);

        // Check ownership
        if ($pemesanan->id_user !== Auth::id()) {
            abort(403);
        }

        // Check if can be cancelled
        if ($pemesanan->status_pemesanan === 'dibatalkan') {
            return back()->with('error', 'Pemesanan sudah dibatalkan');
        }

        if ($pemesanan->pembayaran->status_pembayaran !== 'Lunas') {
            return back()->with('error', 'Pembayaran belum lunas');
        }

        $jadwal = $pemesanan->jadwal;
        $waktuKeberangkatan = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_berangkat);
        $jamSekarang = Carbon::now();
        $selisihJam = $jamSekarang->diffInHours($waktuKeberangkatan, false);

        if ($selisihJam < 12) {
            return back()->with('error', 'Pembatalan hanya bisa dilakukan minimal 12 jam sebelum keberangkatan');
        }

        // Calculate refund
        $jumlahRefund = 0;
        if ($selisihJam > 24) {
            $jumlahRefund = $pemesanan->pembayaran->jumlah * 0.9; // 90%
        } elseif ($selisihJam >= 12) {
            $jumlahRefund = $pemesanan->pembayaran->jumlah * 0.5; // 50%
        }

        // Update pemesanan status
        $pemesanan->update(['status_pemesanan' => 'dibatalkan']);

        // Update kursi status back to tersedia
        $nomorKursi = explode(',', $pemesanan->nomor_kursi);
        foreach ($nomorKursi as $kursi) {
            Kursi::where('id_jadwal', $pemesanan->id_jadwal)
                 ->where('id_bus', $pemesanan->id_bus)
                 ->where('nomor_kursi', trim($kursi))
                 ->update(['status_kursi' => 'tersedia']);
        }

        // Update bus kursi_tersedia
        $bus = $pemesanan->bus;
        $bus->kursi_tersedia += $pemesanan->jumlah_kursi;
        $bus->save();

        // Update tiket status
        if ($pemesanan->tiket) {
            $pemesanan->tiket->update(['status_tiket' => 'dibatalkan']);
        }

        // TODO: Process refund to payment method

        return back()->with('success', 'Pemesanan berhasil dibatalkan. Refund: Rp ' . number_format($jumlahRefund, 0, ',', '.'));
    }
}
