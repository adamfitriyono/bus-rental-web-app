# 🚀 PANDUAN TESTING - SISTEM TIKET BIS ONLINE

**Project Tugas Kuliah - Ready to Demo!**

---

## ⚙️ STEP 1: PERSIAPAN AWAL (5 MENIT)

### 1.1 Start XAMPP

```
✅ Start Apache
✅ Start MySQL
```

### 1.2 Pastikan Database Ready

-   Database name: `rentalcam` (sudah ada di .env)
-   Tabel sudah di-migrate
-   Data sudah di-seed

### 1.3 Start Laravel Server

```bash
cd d:\rental-kamera
php artisan serve
```

**Server akan jalan di:** http://127.0.0.1:8000

---

## 👤 STEP 2: TESTING AKUN

### Admin Account:

```
Email: admin@admin.com
Password: admin123
```

### User Account (buat baru atau gunakan existing):

```
Daftar di: http://127.0.0.1:8000/daftar

Isi:
- Nama: Test User
- Email: user@test.com
- Password: password123
- Nomor HP: 081234567890
```

---

## 🎯 STEP 3: TESTING FLOW LENGKAP

### A. TESTING USER BOOKING (15 menit)

#### 1. Home Page - Search Tiket

```
URL: http://127.0.0.1:8000
```

✅ **Test:**

-   Lihat tampilan home page
-   Isi form search:
    -   Kota Asal: Jakarta
    -   Kota Tujuan: Bandung
    -   Tanggal: (pilih tanggal hari ini atau besok)
-   Klik "Cari Tiket"

#### 2. Hasil Pencarian

✅ **Test:**

-   Lihat list jadwal yang available
-   Check ada bus dengan kursi tersedia
-   Klik "Pilih" pada salah satu jadwal

#### 3. Seat Selection (Visual)

```
URL: http://127.0.0.1:8000/bus/{id}
```

✅ **Test:**

-   Lihat layout kursi (2-2 configuration)
-   Warna kursi:
    -   **Hijau** = Tersedia (bisa klik)
    -   **Merah** = Sudah terisi
    -   **Biru** = Yang kamu pilih
-   Klik beberapa kursi (misal: A1, A2)
-   Check summary sidebar update (harga otomatis hitung)
-   Klik "Lanjutkan"

#### 4. Data Penumpang

```
URL: http://127.0.0.1:8000/passenger/data
```

✅ **Test:**

-   Progress indicator (Step 2 active)
-   Ada card untuk tiap penumpang (sesuai jumlah kursi)
-   Isi data:
    -   Nama Lengkap: John Doe
    -   Tipe Identitas: KTP
    -   Nomor Identitas: 1234567890123456
    -   Nomor HP: 081234567890
-   Centang "Setuju dengan syarat dan ketentuan"
-   Klik "Lanjutkan ke Pembayaran"

#### 5. Payment Summary

```
URL: http://127.0.0.1:8000/payment-summary
```

✅ **Test:**

-   Lihat ringkasan pesanan
-   Pilih metode pembayaran (ada 10 pilihan):

    **UNTUK DEMO CEPAT - PILIH TRANSFER BANK:**

    -   Klik card "BCA" atau "Mandiri"
    -   Klik "Bayar Sekarang"

#### 6A. Upload Bukti Transfer (Jika pilih Transfer Bank)

```
URL: http://127.0.0.1:8000/payment-upload/{id}
```

✅ **Test:**

-   Countdown timer jalan (24 jam)
-   Info rekening bank ditampilkan
-   Upload gambar bukti transfer:
    -   Klik area upload
    -   Pilih file gambar (JPG/PNG, max 5MB)
    -   Gambar preview muncul
-   Klik "Kirim Bukti Transfer"
-   ✅ **Berhasil:** Redirect ke dashboard

#### 6B. Payment Gateway (Jika pilih E-Wallet/VA/Credit Card)

```
URL: http://127.0.0.1:8000/payment-gateway/{id}
```

⚠️ **CATATAN:**

-   Midtrans butuh API keys dari dashboard.sandbox.midtrans.com
-   Untuk demo tanpa setup Midtrans:
    -   Pilih **Transfer Bank** saja (6A)
    -   Atau lanjut ke admin untuk verify manual

---

### B. TESTING ADMIN PANEL (10 menit)

#### 1. Login Admin

```
URL: http://127.0.0.1:8000/login
Email: admin@admin.com
Password: admin123
```

#### 2. Dashboard Admin

```
URL: http://127.0.0.1:8000/admin/dashboard
```

✅ **Test:**

-   Lihat statistics cards
-   Check total bus, rute, jadwal, pemesanan

#### 3. Kelola Bus

```
URL: http://127.0.0.1:8000/admin/bus
```

✅ **Test:**

-   **Lihat List:** Tabel bus dengan pagination
-   **Tambah Bus:**
    -   Klik "Tambah Bus"
    -   Isi: Nama Bus, Plat Nomor, Kapasitas, Jenis Kelas, Fasilitas
    -   Klik "Simpan"
-   **Edit Bus:** Klik tombol edit (icon pensil)
-   **Hapus Bus:** Klik tombol hapus (icon trash)

#### 4. Kelola Rute

```
URL: http://127.0.0.1:8000/admin/rute
```

✅ **Test:**

-   **Tambah Rute:**
    -   Kota Asal: Surabaya
    -   Kota Tujuan: Malang
    -   Jarak: 90 km
    -   Estimasi: 2 jam
    -   Harga Base: 50000
    -   Auto-calculation bekerja (kecepatan rata-rata & harga per km)
-   Klik "Simpan"

#### 5. Kelola Jadwal

```
URL: http://127.0.0.1:8000/admin/jadwal
```

✅ **Test:**

-   **Tambah Jadwal:**
    -   Pilih Rute
    -   Pilih Bus
    -   Tanggal: (besok)
    -   Jam Berangkat: 08:00
    -   Jam Tiba: 10:00
    -   Harga tiket: 50000
    -   Klik "Simpan"
    -   ✅ **Check:** Kursi otomatis di-generate

#### 6. Kelola Pemesanan ⭐ **PENTING!**

```
URL: http://127.0.0.1:8000/admin/pemesanan
```

✅ **Test:**

-   Lihat list pemesanan
-   **Filter:** Status = Pending
-   Klik "Detail" pada pemesanan yang baru dibuat tadi
-   ✅ **Verifikasi Pembayaran:**
    -   Lihat detail booking (route, bus, penumpang, payment)
    -   Lihat bukti transfer (jika sudah upload)
    -   Klik tombol **"Verifikasi Pembayaran"**
    -   Status berubah jadi "Dikonfirmasi"
    -   Status pembayaran jadi "Lunas"

#### 7. Laporan Penjualan ⭐ **UNTUK PRESENTASI**

```
URL: http://127.0.0.1:8000/admin/laporan/penjualan
```

✅ **Test:**

-   Lihat **chart revenue trend** (Line chart)
-   Lihat **booking by status** (Doughnut chart)
-   Lihat **Top 10 Routes** dengan progress bar
-   Filter by date range
-   **Print:** Klik tombol "Cetak Laporan"

#### 8. Laporan Keuangan

```
URL: http://127.0.0.1:8000/admin/laporan/keuangan
```

✅ **Test:**

-   Lihat **daily revenue chart** (Bar chart)
-   Lihat **payment method breakdown** (Pie chart)
-   Check financial summary (revenue, pending, cancelled)

#### 9. Laporan Occupancy

```
URL: http://127.0.0.1:8000/admin/laporan/occupancy
```

✅ **Test:**

-   Lihat **circular progress** (overall occupancy rate)
-   Lihat **bus utilization chart** (Horizontal stacked bar)
-   Lihat **occupancy by route** table
-   Lihat **popular routes** dan **peak hours**

---

### C. TESTING USER DASHBOARD (5 menit)

#### 1. User Dashboard

```
URL: http://127.0.0.1:8000/user/dashboard
```

✅ **Test:**

-   Lihat statistics (total tiket, pending, dikonfirmasi, mendatang)
-   Lihat upcoming trips
-   Lihat recent activity timeline

#### 2. History Pemesanan

```
URL: http://127.0.0.1:8000/user/history
```

✅ **Test:**

-   Lihat list booking dengan status badges
-   Filter by status
-   Klik "Lihat Detail"

#### 3. E-Ticket Detail

```
URL: http://127.0.0.1:8000/user/ticket/{id}
```

✅ **Test:**

-   Lihat e-ticket dengan route visualization
-   Check passenger list
-   Check payment details
-   **Print:** Klik tombol print (floating button)
-   ⚠️ **QR Code & PDF:** Butuh package tambahan (optional)

---

## 🐛 TROUBLESHOOTING

### Error 1: "Class not found"

```bash
php artisan config:clear
composer dump-autoload
```

### Error 2: "Table doesn't exist"

```bash
php artisan migrate:fresh --seed
```

### Error 3: "Session expired"

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear
```

### Error 4: "CSRF token mismatch"

-   Clear browser cookies
-   Refresh halaman dengan Ctrl+F5

### Error 5: "Midtrans error" (payment gateway)

-   Untuk demo: **Gunakan Transfer Bank saja**
-   Atau setup Midtrans keys di .env (lihat MIDTRANS_SETUP.md)

---

## ✅ CHECKLIST TESTING MINIMAL (UNTUK DEMO)

### User Flow:

-   [ ] Home page tampil ✅
-   [ ] Search tiket bisa ✅
-   [ ] Pilih kursi visual bekerja ✅
-   [ ] Isi data penumpang bisa ✅
-   [ ] Payment summary tampil ✅
-   [ ] Upload bukti transfer bisa ✅
-   [ ] Dashboard user tampil ✅
-   [ ] History booking tampil ✅
-   [ ] Detail tiket tampil ✅

### Admin Flow:

-   [ ] Login admin bisa ✅
-   [ ] CRUD Bus bekerja ✅
-   [ ] CRUD Rute bekerja ✅
-   [ ] CRUD Jadwal bekerja ✅
-   [ ] List pemesanan tampil ✅
-   [ ] Verifikasi payment bisa ✅
-   [ ] Laporan Penjualan tampil dengan chart ✅
-   [ ] Laporan Keuangan tampil dengan chart ✅
-   [ ] Laporan Occupancy tampil dengan chart ✅

---

## 🎓 TIPS PRESENTASI TUGAS KULIAH

### 1. Siapkan Data Demo:

```bash
php artisan db:seed --class=DatabaseSeeder
```

Ini akan create:

-   3 Bus (Ekonomi, Bisnis, Eksekutif)
-   5 Rute populer
-   10 Jadwal untuk minggu ini
-   Sample bookings

### 2. Jalankan Flow Lengkap:

1. **Show User Flow** (5 menit):
    - Search → Seat Selection → Booking → Payment
2. **Show Admin Panel** (5 menit):
    - Show CRUD operations
    - **Highlight:** Verifikasi payment manual
3. **Show Reports** (3 menit): ⭐ **INI YANG BAGUS UNTUK PRESENTASI**
    - Laporan Penjualan dengan Chart.js
    - Revenue trend (line chart)
    - Payment method breakdown (pie chart)
    - Occupancy rate (visual progress)

### 3. Fitur Unggulan yang Bisa Dijelaskan:

✅ **Real-time seat selection** dengan visual grid
✅ **Session-based cart** (no database clutter)
✅ **Transaction-based payment** (ACID compliant)
✅ **Interactive charts** dengan Chart.js
✅ **Responsive design** (mobile-friendly)
✅ **Payment gateway integration** (Midtrans ready)
✅ **Multi-payment methods** (10 options)
✅ **Admin reports** (3 jenis dengan visualisasi)

### 4. Yang TIDAK Usah Dijelaskan (Karena Optional):

❌ QR Code generation (butuh package tambahan)
❌ PDF E-ticket (butuh package tambahan)
❌ Email notification (butuh SMTP setup)
❌ Real payment gateway (butuh Midtrans sandbox account)

---

## 📱 DEMO SEQUENCE (15 MENIT)

### Menit 1-2: Intro

"Sistem Penjualan Tiket Bis Online berbasis web dengan Laravel 8 dan Bootstrap 5"

### Menit 3-7: User Flow

1. Open home (search form)
2. Search tiket Jakarta-Bandung
3. Pilih jadwal → Visual seat selection
4. Isi data 2 penumpang
5. Payment summary → Pilih Transfer BCA
6. Show upload bukti page

### Menit 8-10: Admin Panel

1. Login admin
2. Show dashboard
3. Quick demo CRUD Bus atau Rute
4. **Poin Penting:** Verifikasi payment yang tadi

### Menit 11-14: Reports ⭐ **HIGHLIGHT INI!**

1. Laporan Penjualan → Show chart revenue trend
2. Laporan Keuangan → Show payment method breakdown
3. Laporan Occupancy → Show occupancy rate & bus utilization
4. Explain: "Data real-time dari database dengan Chart.js"

### Menit 15: Closing

"Fitur lengkap: booking, payment, reports. Ready untuk development lebih lanjut."

---

## 🚀 QUICK START (COPY-PASTE INI!)

```bash
# 1. Pastikan di folder project
cd d:\rental-kamera

# 2. Clear cache
php artisan config:clear
php artisan cache:clear

# 3. Start server
php artisan serve

# 4. Open browser:
# User: http://127.0.0.1:8000
# Admin: http://127.0.0.1:8000/login
```

**Login Admin:**

-   Email: `admin@admin.com`
-   Password: `admin123`

**Buat User Baru:**

-   Daftar di: http://127.0.0.1:8000/daftar

---

## ✅ SYSTEM READY!

**Total Views:** 23 files
**Total Controllers:** 8 files
**Total Models:** 11 files
**Database Tables:** 11 tables
**Payment Methods:** 10 options
**Admin Reports:** 3 with charts

**Status:** ✅ **SIAP DEMO & PRESENTASI!**

---

💡 **Pro Tip:** Kalau ada error, cek `storage/logs/laravel.log` untuk debugging!
