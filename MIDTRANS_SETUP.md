# Setup Midtrans Payment Gateway

## 1. Install Package

```bash
composer require midtrans/midtrans-php
```

✅ **SELESAI** - Package sudah terinstall (versi 2.6.2)

---

## 2. Dapatkan API Keys dari Midtrans

### Sandbox (Testing):

1. Daftar di [https://dashboard.sandbox.midtrans.com/register](https://dashboard.sandbox.midtrans.com/register)
2. Setelah login, buka **Settings → Access Keys**
3. Copy **Server Key** dan **Client Key**

### Production:

1. Daftar di [https://dashboard.midtrans.com/register](https://dashboard.midtrans.com/register)
2. Lengkapi verifikasi dokumen perusahaan
3. Setelah disetujui, ambil API keys dari **Settings → Access Keys**

---

## 3. Konfigurasi .env

Ganti nilai berikut di file `.env`:

```env
# Midtrans Configuration
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY_HERE
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY_HERE
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Contoh Keys (Sandbox):**

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-AbCdEf1234567890aBcDeF
MIDTRANS_CLIENT_KEY=SB-Mid-client-XyZaBc1234567890XyZaB
```

⚠️ **PENTING:**

-   Untuk **Testing**: Gunakan keys yang diawali `SB-` (Sandbox)
-   Untuk **Production**: Ganti dengan keys production (tanpa prefix `SB-`) dan set `MIDTRANS_IS_PRODUCTION=true`

---

## 4. Setup Webhook URL

### Apa itu Webhook?

Webhook adalah notifikasi otomatis dari Midtrans ke aplikasi Anda saat status pembayaran berubah (sukses, pending, gagal, dll).

### Cara Setting:

#### A. Setup di Midtrans Dashboard:

1. Login ke [Sandbox Dashboard](https://dashboard.sandbox.midtrans.com/) atau [Production Dashboard](https://dashboard.midtrans.com/)
2. Buka **Settings → Configuration**
3. Isi **Payment Notification URL** dengan:

    ```
    https://yourdomain.com/payment-callback
    ```

    **Development (Local):**

    - Gunakan ngrok untuk expose localhost:
        ```bash
        ngrok http 8000
        ```
    - Copy URL dari ngrok (contoh: `https://abc123.ngrok.io`)
    - Set webhook: `https://abc123.ngrok.io/payment-callback`

#### B. Enable HTTP(S) POST Notification

4. Centang opsi **HTTP(S) POST Notification**
5. Centang **Finish Redirect URL is Enabled**
6. Isi **Finish Redirect URL**: `https://yourdomain.com/user/dashboard`
7. Klik **Save**

---

## 5. Test Payment

### Sandbox Credit Cards (untuk testing):

| Card Type     | Card Number         | CVV | Exp Date | Result                |
| ------------- | ------------------- | --- | -------- | --------------------- |
| **Success**   | 4811 1111 1111 1114 | 123 | 01/25    | Payment berhasil      |
| **Challenge** | 4911 1111 1111 1113 | 123 | 01/25    | Butuh challenge (OTP) |
| **Deny**      | 4411 1111 1111 1118 | 123 | 01/25    | Payment ditolak       |

### Sandbox E-Wallet:

-   **GoPay**: Gunakan nomor `08123456789` → OTP: `123456`
-   **QRIS**: Scan QR akan auto-approve setelah beberapa detik

### Testing Flow:

1. Login sebagai user
2. Pilih jadwal bis dan kursi
3. Isi data penumpang
4. Di halaman payment, pilih metode **E-Wallet**, **Virtual Account**, atau **Credit Card**
5. Klik "Bayar Sekarang"
6. Popup Midtrans Snap akan muncul
7. Pilih metode dan masukkan test credentials
8. Setelah berhasil, cek status di **Dashboard** atau **Admin → Pemesanan**

---

## 6. Payment Flow Diagram

```
User memilih metode pembayaran (selain transfer bank manual)
    ↓
PaymentController::processPayment()
    ↓ (Redirect ke)
PaymentController::paymentGateway($id)
    ↓ (Generate Snap Token via Midtrans API)
payment-gateway.blade.php (tampilkan Snap popup)
    ↓ (User bayar melalui Snap)
Midtrans memproses pembayaran
    ↓ (Kirim notifikasi via webhook)
PaymentController::paymentCallback()
    ↓ (Update status pembayaran di database)
    - settlement → status = 'Lunas'
    - pending → status = 'Pending'
    - deny/expire/cancel → status = 'Dibatalkan'
    ↓
User redirect ke dashboard dengan notifikasi sukses
```

---

## 7. Troubleshooting

### Error: "Invalid Server Key"

✅ **Solusi:**

-   Pastikan `MIDTRANS_SERVER_KEY` di `.env` benar
-   Cek apakah ada spasi atau karakter tersembunyi
-   Jalankan `php artisan config:clear`

### Error: "Snap Token is empty"

✅ **Solusi:**

-   Cek koneksi internet
-   Pastikan data transaksi valid (order_id, gross_amount)
-   Cek log error di `storage/logs/laravel.log`

### Webhook tidak terkirim

✅ **Solusi:**

-   Pastikan URL webhook sudah di-set di Midtrans Dashboard
-   Untuk development, gunakan ngrok
-   Test webhook dengan tool: [Webhook.site](https://webhook.site/)

### Pembayaran berhasil tapi status tidak update

✅ **Solusi:**

-   Cek route `payment.callback` di `web.php`
-   Tambahkan log di method `paymentCallback()`:
    ```php
    \Log::info('Midtrans Webhook', $request->all());
    ```
-   Cek `storage/logs/laravel.log`

---

## 8. Fitur Tambahan (Optional)

### A. Email Notification setelah Payment Success

Tambahkan di `PaymentController::paymentCallback()`:

```php
if ($transactionStatus == 'settlement') {
    $pembayaran->update(['status_pembayaran' => 'Lunas']);
    $pembayaran->pemesanan->update(['status_pemesanan' => 'dikonfirmasi']);

    // Send email
    Mail::to($pembayaran->pemesanan->user->email)
        ->send(new PaymentSuccessMail($pembayaran));
}
```

### B. Generate QR Code & PDF E-Ticket

Install packages:

```bash
composer require simplesoftwareio/simple-qrcode
composer require barryvdh/laravel-dompdf
```

### C. Refund API

Untuk handle pembatalan dengan refund:

```php
use Midtrans\Transaction;

$refund = Transaction::refund($order_id, [
    'amount' => 50000,
    'reason' => 'Customer request'
]);
```

---

## 9. Go Live Checklist

-   [ ] Ganti Sandbox keys dengan Production keys
-   [ ] Set `MIDTRANS_IS_PRODUCTION=true` di `.env`
-   [ ] Update webhook URL ke domain production
-   [ ] Test semua metode pembayaran
-   [ ] Setup email notification
-   [ ] Backup database sebelum go-live
-   [ ] Monitor payment logs selama 24 jam pertama

---

## 10. Support & Dokumentasi

-   **Midtrans Docs:** [https://docs.midtrans.com/](https://docs.midtrans.com/)
-   **PHP SDK Docs:** [https://github.com/Midtrans/midtrans-php](https://github.com/Midtrans/midtrans-php)
-   **Snap API:** [https://snap-docs.midtrans.com/](https://snap-docs.midtrans.com/)
-   **Support:** [support@midtrans.com](mailto:support@midtrans.com)

---

**Status Integrasi:** ✅ **SELESAI**

-   Package installed
-   Configuration added (.env, services.php)
-   PaymentController updated with Snap integration
-   payment-gateway.blade.php updated with Snap popup
-   Webhook handler ready
-   Auto-refresh payment status implemented
