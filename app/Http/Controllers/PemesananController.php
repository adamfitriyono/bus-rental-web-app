<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PemesananController extends Controller
{
    /**
     * Display a listing of all pemesanan (admin)
     */
    public function index(Request $request)
    {
        $query = Pemesanan::with(['user', 'jadwal.rute', 'bus', 'pembayaran']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pemesanan', $request->status);
        }

        // Filter by tanggal
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

        // Filter by metode pembayaran
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran != '') {
            $query->whereHas('pembayaran', function($q) use ($request) {
                $q->where('metode_pembayaran', $request->metode_pembayaran);
            });
        }

        $pemesanans = $query->orderBy('tanggal_pemesanan', 'DESC')->paginate(15);

        return view('admin.pemesanan.index', [
            'pemesanans' => $pemesanans
        ]);
    }

    /**
     * Display the specified resource (detail pemesanan)
     */
    public function show($id)
    {
        $pemesanan = Pemesanan::with(['user', 'jadwal.rute', 'bus', 'penumpangs', 'pembayaran', 'tiket'])
                              ->findOrFail($id);

        return view('admin.pemesanan.detail', [
            'pemesanan' => $pemesanan
        ]);
    }

    /**
     * Verify payment manually (for transfer bank)
     */
    public function verifyPayment($id)
    {
        $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_pembayaran' => now()
        ]);

        // Update pemesanan status
        $pembayaran->pemesanan->update([
            'status_pemesanan' => 'dikonfirmasi'
        ]);

        // Generate ticket
        // You can call TicketController here or trigger it via event

        // TODO: Send email notification
        // Mail::to($pembayaran->pemesanan->user->email)->send(new PaymentVerified($pembayaran));

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }

    /**
     * Cancel pemesanan (admin)
     */
    public function cancel($id)
    {
        $pemesanan = Pemesanan::with(['jadwal', 'bus', 'pembayaran'])->findOrFail($id);

        // Update status
        $pemesanan->update(['status_pemesanan' => 'dibatalkan']);

        // Return kursi
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

        return back()->with('success', 'Pemesanan berhasil dibatalkan');
    }
}

