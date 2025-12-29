<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Show bus detail
     */
    public function showBusDetail($jadwal_id)
    {
        $jadwal = Jadwal::with(['bus', 'rute'])->findOrFail($jadwal_id);
        
        return view('bus-detail', [
            'jadwal' => $jadwal
        ]);
    }

    /**
     * Get seat layout for a jadwal (AJAX)
     */
    public function getSeatLayout($jadwal_id)
    {
        $jadwal = Jadwal::with('bus')->findOrFail($jadwal_id);
        
        // Q-005: SELECT nomor_kursi, status_kursi FROM kursi 
        //        WHERE id_bus=? AND id_jadwal=?
        $kursis = Kursi::where('id_jadwal', $jadwal_id)
                      ->where('id_bus', $jadwal->id_bus)
                      ->orderBy('nomor_kursi')
                      ->get();

        return response()->json([
            'success' => true,
            'message' => 'Seat layout retrieved successfully',
            'kapasitas' => $jadwal->bus->kapasitas_kursi,
            'kursi_tersedia' => $jadwal->bus->kursi_tersedia,
            'kursis' => $kursis
        ]);
    }

    /**
     * Lock seat temporarily during booking process
     */
    public function lockSeat(Request $request, $jadwal_id)
    {
        $request->validate([
            'nomor_kursi' => 'required|array',
            'nomor_kursi.*' => 'required|string'
        ]);

        $nomorKursi = $request->input('nomor_kursi');
        $jadwal = Jadwal::findOrFail($jadwal_id);

        // Check if seats are available
        foreach ($nomorKursi as $kursi) {
            $kursiData = Kursi::where('id_jadwal', $jadwal_id)
                             ->where('id_bus', $jadwal->id_bus)
                             ->where('nomor_kursi', $kursi)
                             ->first();

            if (!$kursiData || $kursiData->status_kursi !== 'tersedia') {
                return response()->json([
                    'success' => false,
                    'message' => 'Kursi ' . $kursi . ' tidak tersedia'
                ], 400);
            }
        }

        // Temporarily lock seats (update status to 'dipesan' temporarily)
        // Note: In production, you might want to use Redis or cache for this
        foreach ($nomorKursi as $kursi) {
            Kursi::where('id_jadwal', $jadwal_id)
                 ->where('id_bus', $jadwal->id_bus)
                 ->where('nomor_kursi', $kursi)
                 ->update(['status_kursi' => 'dipesan']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kursi berhasil dikunci'
        ]);
    }

    /**
     * Add to cart with selected seats (Session-based)
     */
    public function addToCart(Request $request, $jadwal_id)
    {
        $request->validate([
            'nomor_kursi' => 'required|array',
            'nomor_kursi.*' => 'required|string'
        ]);

        $jadwal = Jadwal::with(['bus', 'rute'])->findOrFail($jadwal_id);
        $nomorKursi = $request->input('nomor_kursi');
        $jumlahKursi = count($nomorKursi);

        // Calculate total price
        $totalHarga = $jadwal->harga_tiket * $jumlahKursi;

        // Save to session cart (not database)
        $cart = [
            'jadwal_id' => $jadwal_id,
            'jadwal' => $jadwal,
            'nomor_kursi' => $nomorKursi,
            'jumlah_kursi' => $jumlahKursi,
            'total_harga' => $totalHarga,
            'created_at' => now()
        ];

        session(['booking_cart' => $cart]);

        return redirect()->route('passenger.data')->with('success', 'Silakan isi data penumpang');
    }

    /**
     * Show passenger data form
     */
    public function passengerData(Request $request)
    {
        $cart = session('booking_cart');
        
        if (!$cart) {
            return redirect()->route('home')->with('error', 'Keranjang kosong');
        }

        return view('passenger-form', [
            'cart' => $cart,
            'jumlahKursi' => $cart['jumlah_kursi'],
            'selectedSeats' => $cart['nomor_kursi']
        ]);
    }

    /**
     * Store passenger data (dummy implementation)
     */
    public function storePassenger(Request $request)
    {
        // Validasi data penumpang
        $validated = $request->validate([
            'nama.*' => 'required|string|max:100',
            'nik.*' => 'required|string|max:20',
            'telepon.*' => 'required|string|max:20',
        ]);

        // Simpan data penumpang ke session (atau database jika sudah siap)
        session(['passenger_data' => $request->all()]);

        // Redirect ke pembayaran
        return redirect()->route('payment.summary')->with('success', 'Data penumpang berhasil disimpan.');
    }

    /**
     * Process checkout
     */
    public function checkout(Request $request)
    {
        $cart = session('booking_cart');
        
        if (!$cart) {
            return redirect()->route('home')->with('error', 'Keranjang kosong');
        }

        // This will be handled by PaymentController
        // Redirect to payment summary
        return redirect()->route('payment.summary');
    }
}
