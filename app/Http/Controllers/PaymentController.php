<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Penumpang;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Carts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    /**
     * Show payment summary (ringkasan pemesanan)
     */
    public function showPaymentSummary(Request $request)
    {
        // Get cart items
        $carts = Carts::where('user_id', Auth::id())->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang kosong');
        }

        // Get jadwal and calculate totals
        $items = [];
        $totalHarga = 0;
        
        foreach ($carts as $cart) {
            $jadwal = Jadwal::with(['bus', 'rute'])->find($cart->alat_id); // Temporary: using alat_id for jadwal_id
            if ($jadwal) {
                $jumlahKursi = $cart->durasi; // Temporary: durasi stores jumlah kursi
                $subtotal = $jadwal->harga_tiket * $jumlahKursi;
                $totalHarga += $subtotal;
                
                $items[] = [
                    'cart' => $cart,
                    'jadwal' => $jadwal,
                    'jumlah_kursi' => $jumlahKursi,
                    'subtotal' => $subtotal
                ];
            }
        }

        // Biaya admin 5%
        $biayaAdmin = $totalHarga * 0.05;
        $totalPembayaran = $totalHarga + $biayaAdmin;

        return view('payment-summary', [
            'items' => $items,
            'totalHarga' => $totalHarga,
            'biayaAdmin' => $biayaAdmin,
            'totalPembayaran' => $totalPembayaran
        ]);
    }

    /**
     * Process payment and create pemesanan
     * Q-006, Q-007, Q-008 dari dokumentasi
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'penumpang' => 'required|array',
            'penumpang.*.nama_penumpang' => 'required|string',
            'penumpang.*.tipe_identitas' => 'required|string',
            'penumpang.*.nomor_identitas' => 'required|string',
            'penumpang.*.nomor_hp' => 'nullable|string',
            'metode_pembayaran' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $carts = Carts::where('user_id', Auth::id())->get();
            
            foreach ($carts as $cart) {
                $jadwal = Jadwal::with('bus')->find($cart->alat_id);
                if (!$jadwal) continue;

                $selectedSeats = session('selected_seats_' . $cart->alat_id, []);
                $jumlahKursi = count($selectedSeats);
                $totalHarga = $jadwal->harga_tiket * $jumlahKursi;

                // Q-007: INSERT INTO pemesanan
                $pemesanan = Pemesanan::create([
                    'id_user' => Auth::id(),
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_bus' => $jadwal->id_bus,
                    'nomor_kursi' => implode(',', $selectedSeats),
                    'jumlah_kursi' => $jumlahKursi,
                    'status_pemesanan' => 'pending'
                ]);

                // Save penumpang data
                foreach ($request->input('penumpang') as $index => $penumpangData) {
                    if ($index < $jumlahKursi) {
                        Penumpang::create([
                            'id_pemesanan' => $pemesanan->id_pemesanan,
                            'nama_penumpang' => $penumpangData['nama_penumpang'],
                            'tipe_identitas' => $penumpangData['tipe_identitas'],
                            'nomor_identitas' => $penumpangData['nomor_identitas'],
                            'nomor_hp' => $penumpangData['nomor_hp'] ?? null,
                        ]);
                    }
                }

                // Q-006: INSERT INTO pembayaran
                $kodeTransaksi = 'PAY-' . Auth::id() . '-' . Carbon::now()->timestamp;
                $pembayaran = Pembayaran::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'metode_pembayaran' => $request->input('metode_pembayaran'),
                    'jumlah' => $totalHarga,
                    'status_pembayaran' => 'pending',
                    'kode_transaksi' => $kodeTransaksi
                ]);

                // Q-008: UPDATE bus SET kursi_tersedia = kursi_tersedia - ?
                $bus = $jadwal->bus;
                $bus->kursi_tersedia -= $jumlahKursi;
                $bus->save();

                // Update kursi status
                foreach ($selectedSeats as $nomorKursi) {
                    Kursi::where('id_jadwal', $jadwal->id_jadwal)
                         ->where('id_bus', $jadwal->id_bus)
                         ->where('nomor_kursi', $nomorKursi)
                         ->update(['status_kursi' => 'terisi']);
                }

                // Delete cart item
                $cart->delete();
            }

            DB::commit();

            // Redirect based on payment method
            if (in_array($request->input('metode_pembayaran'), ['transfer_bank'])) {
                return redirect()->route('payment.upload', $pembayaran->id_pembayaran)
                                 ->with('success', 'Pemesanan berhasil dibuat. Silakan upload bukti transfer.');
            } else {
                // Redirect to payment gateway
                return redirect()->route('payment.gateway', $pembayaran->id_pembayaran);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Upload bukti transfer untuk pembayaran manual
     */
    public function uploadBuktiTransfer(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('images/evidence'), $filename);
            
            $pembayaran->update([
                'bukti_transfer' => base64_encode(file_get_contents(public_path('images/evidence/' . $filename)))
            ]);
        }

        return back()->with('success', 'Bukti transfer berhasil diupload');
    }

    /**
     * Payment gateway integration with Midtrans Snap
     */
    public function paymentGateway($id)
    {
        $pembayaran = Pembayaran::with(['pemesanan.jadwal.rute', 'pemesanan.bus'])->findOrFail($id);
        
        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Transaction details
        $transactionDetails = [
            'order_id' => $pembayaran->kode_transaksi,
            'gross_amount' => $pembayaran->jumlah,
        ];

        // Item details
        $itemDetails = [
            [
                'id' => $pembayaran->pemesanan->id_pemesanan,
                'price' => $pembayaran->jumlah,
                'quantity' => 1,
                'name' => 'Tiket Bus ' . $pembayaran->pemesanan->jadwal->rute->kota_asal . ' - ' . $pembayaran->pemesanan->jadwal->rute->kota_tujuan,
            ]
        ];

        // Customer details
        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->nomor_hp ?? '08123456789',
        ];

        // Transaction data
        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);
            
            return view('payment-gateway', [
                'pembayaran' => $pembayaran,
                'snapToken' => $snapToken
            ]);
        } catch (\Exception $e) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Payment callback from gateway (webhook)
     */
    public function paymentCallback(Request $request)
    {
        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $orderID = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Find payment by transaction code
            $pembayaran = Pembayaran::where('kode_transaksi', $orderID)->first();
            
            if (!$pembayaran) {
                return response()->json(['success' => false, 'status' => 'error', 'message' => 'Payment not found']);
            }

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $pembayaran->update(['status_pembayaran' => 'Pending']);
                } else if ($fraudStatus == 'accept') {
                    $pembayaran->update(['status_pembayaran' => 'Lunas']);
                    $pembayaran->pemesanan->update(['status_pemesanan' => 'dikonfirmasi']);
                }
            } else if ($transactionStatus == 'settlement') {
                // Q-009: UPDATE pembayaran SET status='Lunas'
                $pembayaran->update(['status_pembayaran' => 'Lunas']);
                $pembayaran->pemesanan->update(['status_pemesanan' => 'dikonfirmasi']);
            } else if ($transactionStatus == 'pending') {
                $pembayaran->update(['status_pembayaran' => 'Pending']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $pembayaran->update(['status_pembayaran' => 'Dibatalkan']);
                $pembayaran->pemesanan->update(['status_pemesanan' => 'dibatalkan']);
                
                // Return seats
                $nomorKursi = explode(',', $pembayaran->pemesanan->nomor_kursi);
                Kursi::where('id_jadwal', $pembayaran->pemesanan->id_jadwal)
                    ->whereIn('nomor_kursi', $nomorKursi)
                    ->update(['status' => 'tersedia']);
            }

            return response()->json(['success' => true, 'status' => 'ok']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return response()->json([
            'success' => true,
            'status' => $pembayaran->status_pembayaran,
            'message' => 'Status retrieved successfully'
        ]);
    }
}
