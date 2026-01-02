<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Penumpang;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Cart;
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
        $user = Auth::user();
        $pemesananId = session('pemesanan_id');
        
        if (!$pemesananId) {
            return redirect()->route('home')->with('error', 'Pemesanan tidak ditemukan. Silakan pilih kursi terlebih dahulu.');
        }

        // Ambil pemesanan dari database
        $pemesanan = Pemesanan::with(['jadwal.rute', 'jadwal.bus', 'penumpangs'])
            ->where('id_pemesanan', $pemesananId)
            ->where('id_user', $user->id)
            ->firstOrFail();

        // Hitung biaya
        $totalHarga = $pemesanan->total_harga;
        $biayaAdmin = $totalHarga * 0.05;
        $totalPembayaran = $totalHarga + $biayaAdmin;

        return view('payment-summary', [
            'pemesanan' => $pemesanan,
            'totalHarga' => $totalHarga,
            'biayaAdmin' => $biayaAdmin,
            'totalPembayaran' => $totalPembayaran
        ]);
    }

    /**
     * Process payment and create pembayaran record (DUMMY PAYMENT SYSTEM)
     */
    public function processPayment(Request $request)
    {
        // Accept all payment methods from form
        $request->validate([
            'metode_pembayaran' => 'required|string|in:transfer_bank_bca,transfer_bank_mandiri,transfer_bank_bri,transfer_bank_bni,e_wallet_gopay,e_wallet_ovo,e_wallet_dana,virtual_account_bca,virtual_account_mandiri,credit_card',
        ]);

        $user = Auth::user();
        $pemesananId = session('pemesanan_id');
        
        if (!$pemesananId) {
            return redirect()->route('home')->with('error', 'Pemesanan tidak ditemukan');
        }

        // Ambil pemesanan
        $pemesanan = Pemesanan::where('id_pemesanan', $pemesananId)
            ->where('id_user', $user->id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Calculate total dengan admin fee
            $totalHarga = $pemesanan->total_harga;
            $biayaAdmin = $totalHarga * 0.05;
            $totalPembayaran = $totalHarga + $biayaAdmin;

            // Create pembayaran record
            $kodeTransaksi = 'TRX' . strtoupper(substr(uniqid(), -8));
            $metodePembayaran = $request->input('metode_pembayaran');
            
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'metode_pembayaran' => $metodePembayaran,
                'jumlah' => $totalPembayaran,
                'status_pembayaran' => 'Lunas',
                'kode_transaksi' => $kodeTransaksi,
                'tanggal_pembayaran' => now()
            ]);

            // Update pemesanan status to 'dikonfirmasi' (DUMMY: langsung confirmed)
            $pemesanan->update(['status_pemesanan' => 'dikonfirmasi']);

            // Update kursi status to 'terjual' (final status after payment)
            $nomorKursi = explode(',', $pemesanan->nomor_kursi);
            foreach ($nomorKursi as $kursi) {
                Kursi::where('id_jadwal', $pemesanan->id_jadwal)
                     ->where('id_bus', $pemesanan->id_bus)
                     ->where('nomor_kursi', trim($kursi))
                     ->update(['status_kursi' => 'terjual']);
            }

            DB::commit();

            // Clear session
            session()->forget('pemesanan_id');

            // DUMMY: Langsung ke halaman selesai dengan data pembayaran
            return redirect()->route('payment.success', $pembayaran->id_pembayaran)
                             ->with('success', 'Pembayaran berhasil diproses!');

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
     * Payment Success Page (Halaman Selesai - Step 4)
     */
    public function paymentSuccess($id)
    {
        $pembayaran = Pembayaran::with(['pemesanan.jadwal.rute', 'pemesanan.jadwal.bus', 'pemesanan.penumpangs', 'pemesanan.user'])
            ->findOrFail($id);
        
        // Verify pembayaran belongs to current user
        if ($pembayaran->pemesanan->id_user !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Akses ditolak');
        }

        // Calculate fees
        $totalHarga = $pembayaran->pemesanan->total_harga;
        $biayaAdmin = $pembayaran->jumlah - $totalHarga;

        return view('payment-success', [
            'pembayaran' => $pembayaran,
            'pemesanan' => $pembayaran->pemesanan,
            'totalHarga' => $totalHarga,
            'biayaAdmin' => $biayaAdmin,
            'totalPembayaran' => $pembayaran->jumlah
        ]);
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
