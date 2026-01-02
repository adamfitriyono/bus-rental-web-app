<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bus;
use App\Models\Kursi;
use App\Models\Pemesanan;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Store passenger data and create pemesanan to database
     */
    public function storePassenger(Request $request)
    {
        // Validasi data penumpang - sesuai dengan form input name
        $validated = $request->validate([
            'penumpang.*.nama_penumpang' => 'required|string|max:100',
            'penumpang.*.tipe_identitas' => 'required|in:ktp,sim,paspor,lainnya',
            'penumpang.*.nomor_identitas' => 'required|string|max:50',
            'penumpang.*.nomor_hp' => 'required|string|max:15',
        ], [
            'penumpang.*.nama_penumpang.required' => 'Nama penumpang wajib diisi',
            'penumpang.*.tipe_identitas.required' => 'Tipe identitas wajib dipilih',
            'penumpang.*.nomor_identitas.required' => 'Nomor identitas wajib diisi',
            'penumpang.*.nomor_hp.required' => 'Nomor HP wajib diisi'
        ]);

        // Ambil data dari session
        $cart = session('booking_cart');
        if (!$cart) {
            return redirect()->route('home')->with('error', 'Data booking tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            $jadwal = $cart['jadwal'];
            $nomorKursi = $cart['nomor_kursi'];
            $jumlahKursi = $cart['jumlah_kursi'];
            $totalHarga = $cart['total_harga'];
            $user = Auth::user();

            // Create pemesanan
            $pemesanan = Pemesanan::create([
                'id_user' => $user->id,
                'id_jadwal' => $jadwal->id_jadwal,
                'id_bus' => $jadwal->id_bus,
                'nomor_kursi' => implode(',', $nomorKursi),
                'jumlah_kursi' => $jumlahKursi,
                'total_harga' => $totalHarga,
                'status_pemesanan' => 'menunggu_pembayaran',
                'tanggal_pemesanan' => now()
            ]);

            // Create penumpang records - sesuai dengan fields di database
            foreach ($validated['penumpang'] as $penumpangData) {
                Penumpang::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'nama_penumpang' => $penumpangData['nama_penumpang'],
                    'tipe_identitas' => $penumpangData['tipe_identitas'],
                    'nomor_identitas' => $penumpangData['nomor_identitas'],
                    'nomor_hp' => $penumpangData['nomor_hp']
                ]);
            }

            // Update status kursi ke 'terjual' (final status after booking created)
            foreach ($nomorKursi as $kursi) {
                Kursi::where('id_jadwal', $jadwal->id_jadwal)
                    ->where('id_bus', $jadwal->id_bus)
                    ->where('nomor_kursi', $kursi)
                    ->update(['status_kursi' => 'terjual']);
            }

            DB::commit();

            // Simpan pemesanan ID ke session untuk referensi payment
            session(['pemesanan_id' => $pemesanan->id_pemesanan]);

            // Clear cart dari session
            session()->forget('booking_cart');

            return redirect()->route('payment.summary')->with('success', 'Data penumpang berhasil disimpan. Lanjut ke pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
