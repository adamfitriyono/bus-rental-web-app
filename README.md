# 🚌 SISTEM PENJUALAN TIKET BIS ONLINE

**Project Tugas Kuliah - Laravel 8**

---

## 📋 Deskripsi Project

Sistem penjualan tiket bus online berbasis web yang memungkinkan user untuk:

-   🔍 Mencari jadwal bus berdasarkan rute dan tanggal
-   💺 Memilih kursi secara visual (interactive seat selection)
-   💳 Pembayaran dengan 10 metode (Transfer Bank, E-Wallet, Virtual Account, Credit Card)
-   📧 Upload bukti transfer untuk pembayaran manual
-   📱 Dashboard user untuk tracking pemesanan
-   📊 Admin panel dengan laporan lengkap (Chart.js)

---

## 🛠️ Technology Stack

| Technology   | Version |
| ------------ | ------- |
| PHP          | 8.0+    |
| Laravel      | 8.x     |
| MySQL        | 8.0+    |
| Bootstrap    | 5.2.3   |
| Chart.js     | 4.4.0   |
| Font Awesome | 6.4.0   |
| Midtrans SDK | 2.6.2   |

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/rental-kamera.git
cd rental-kamera
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
# Copy .env.example to .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentalcam
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Database Migration & Seeding

```bash
# Run migrations
php artisan migrate

# Run seeders (data dummy untuk testing)
php artisan db:seed
```

### 6. Storage Link (untuk upload gambar)

```bash
php artisan storage:link
```

### 7. Start Development Server

```bash
php artisan serve
```

**Server akan berjalan di:** http://127.0.0.1:8000

---

## 👤 Default Accounts

### Admin:

```
Email: admin@admin.com
Password: admin123
URL: http://127.0.0.1:8000/login
```

### User:

```
Daftar baru di: http://127.0.0.1:8000/daftar
Atau gunakan seeder data
```

---

## 📁 Project Structure

```
rental-kamera/
├── app/
│   ├── Http/Controllers/
│   │   ├── BusController.php
│   │   ├── RuteController.php
│   │   ├── JadwalController.php
│   │   ├── BookingController.php
│   │   ├── PaymentController.php (Midtrans Integration)
│   │   ├── PemesananController.php
│   │   ├── ReportController.php
│   │   └── ...
│   └── Models/
│       ├── Bus.php
│       ├── Rute.php
│       ├── Jadwal.php
│       ├── Kursi.php
│       ├── Pemesanan.php
│       ├── Pembayaran.php
│       └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── bus/
│       │   ├── rute/
│       │   ├── jadwal/
│       │   ├── pemesanan/
│       │   └── reports/ (⭐ Laporan dengan Chart.js)
│       ├── user/
│       │   ├── dashboard.blade.php
│       │   ├── history.blade.php
│       │   └── ticket-detail.blade.php
│       ├── bus-detail.blade.php (Seat Selection)
│       ├── passenger-form.blade.php
│       ├── payment-summary.blade.php
│       ├── payment-upload.blade.php
│       └── payment-gateway.blade.php (Midtrans Snap)
├── routes/
│   └── web.php
├── TESTING_GUIDE.md (⭐ Panduan Testing Lengkap)
├── MIDTRANS_SETUP.md (Setup Payment Gateway)
└── dokumentasi.md
```

---

## ✨ Fitur Utama

### 🎫 User Features:

-   [x] Search jadwal bus (kota asal, tujuan, tanggal)
-   [x] Visual seat selection (2-2 configuration dengan warna status)
-   [x] Multi-passenger data form
-   [x] 10 metode pembayaran:
    -   Transfer Bank (BCA, Mandiri, BRI, BNI)
    -   E-Wallet (GoPay, OVO, DANA)
    -   Virtual Account (BCA VA, Mandiri VA)
    -   Credit Card
-   [x] Upload bukti transfer (untuk pembayaran manual)
-   [x] Dashboard user (statistics & upcoming trips)
-   [x] History pemesanan dengan filter
-   [x] E-ticket detail (printable)

### 🔧 Admin Features:

-   [x] CRUD Bus (nama, plat, kapasitas, kelas, fasilitas)
-   [x] CRUD Rute (kota asal/tujuan, jarak, estimasi, harga)
-   [x] CRUD Jadwal (rute, bus, tanggal, jam, harga)
-   [x] Manajemen Pemesanan:
    -   List dengan multi-filter
    -   Detail pemesanan lengkap
    -   Verifikasi pembayaran manual
    -   Cancel booking (return seats)
-   [x] **Laporan dengan Chart.js:** ⭐
    -   **Laporan Penjualan:** Revenue trend, booking count, top routes
    -   **Laporan Keuangan:** Payment method breakdown, daily revenue
    -   **Laporan Occupancy:** Seat utilization, bus usage, peak hours

---

## 📊 Database Schema (Simplified)

```
users (id, name, email, password, role, nomor_hp)
buses (id_bus, nama_bus, plat_nomor, kapasitas_kursi, jenis_kelas)
rutes (id_rute, kota_asal, kota_tujuan, jarak_km, harga_base)
jadwals (id_jadwal, id_rute, id_bus, tanggal, jam_berangkat, harga_tiket)
kursis (id_kursi, id_jadwal, nomor_kursi, status)
pemesanans (id_pemesanan, id_user, id_jadwal, jumlah_kursi, status_pemesanan)
penumpangs (id_penumpang, id_pemesanan, nama_lengkap, nomor_identitas)
pembayarans (id_pembayaran, id_pemesanan, metode_pembayaran, jumlah, status_pembayaran)
tikets (id_tiket, id_pemesanan, kode_tiket, qr_code, file_pdf)
```

---

## 🎯 API Routes (Main)

### Public:

-   `GET /` - Home page (search form)
-   `POST /search` - Search jadwal
-   `GET /bus/{id}` - Bus detail (seat selection)

### Auth Required:

-   `GET /user/dashboard` - User dashboard
-   `GET /user/history` - Booking history
-   `GET /user/ticket/{id}` - E-ticket detail
-   `POST /booking/addToCart` - Add seats to cart
-   `GET /passenger/data` - Passenger form
-   `POST /passenger/store` - Save passenger data
-   `GET /payment-summary` - Payment summary
-   `POST /process-payment` - Process payment
-   `GET /payment-upload/{id}` - Upload bukti transfer
-   `GET /payment-gateway/{id}` - Midtrans Snap popup

### Admin Only:

-   `GET /admin/dashboard` - Admin dashboard
-   Resource routes untuk: `bus`, `rute`, `jadwal`
-   `GET /admin/pemesanan` - List bookings
-   `POST /admin/pemesanan/verify-payment/{id}` - Verify payment
-   `GET /admin/laporan/penjualan` - Sales report
-   `GET /admin/laporan/keuangan` - Financial report
-   `GET /admin/laporan/occupancy` - Occupancy report

---

## 🧪 Testing

**Panduan lengkap:** Lihat file [TESTING_GUIDE.md](TESTING_GUIDE.md)

### Quick Test:

```bash
# 1. Clear cache
php artisan config:clear
php artisan cache:clear

# 2. Start server
php artisan serve

# 3. Open browser
http://127.0.0.1:8000

# 4. Login admin
http://127.0.0.1:8000/login
Email: admin@admin.com
Password: admin123
```

---

## 💳 Payment Gateway (Midtrans)

### Setup:

1. Daftar di [Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/register)
2. Get API keys dari Settings → Access Keys
3. Update `.env`:
    ```env
    MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_KEY
    MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_KEY
    ```
4. Clear config: `php artisan config:clear`

**Panduan lengkap:** Lihat file [MIDTRANS_SETUP.md](MIDTRANS_SETUP.md)

---

## 📸 Screenshots

### User Flow:

-   Home Page (Search)
-   Visual Seat Selection (Interactive Grid)
-   Passenger Form (Multi-passenger)
-   Payment Summary (10 methods)
-   E-Ticket (Printable)

### Admin Panel:

-   Dashboard Statistics
-   CRUD Bus/Rute/Jadwal
-   Pemesanan Management
-   **Reports dengan Chart.js** ⭐

---

## 🐛 Troubleshooting

### Error: "Class not found"

```bash
composer dump-autoload
php artisan config:clear
```

### Error: "Table doesn't exist"

```bash
php artisan migrate:fresh --seed
```

### Error: "CSRF token mismatch"

-   Clear browser cookies
-   Refresh dengan Ctrl+F5

### Error: Payment gateway

-   Untuk demo: Gunakan **Transfer Bank** (tidak perlu Midtrans)
-   Atau setup Midtrans keys (lihat MIDTRANS_SETUP.md)

---

## 📝 Credits

**Developed for:** Tugas Kuliah - Pemrograman Web / Sistem Informasi

**Original Base Project:** [rental-kamera](https://github.com/yogaiw/rental-kamera)

**Transformed to:** Sistem Penjualan Tiket Bis Online

**Tech Stack:**

-   Laravel 8
-   Bootstrap 5
-   Chart.js
-   Midtrans Payment Gateway

---

## 📞 Support

Jika ada pertanyaan atau error:

1. Check `storage/logs/laravel.log` untuk error details
2. Lihat [TESTING_GUIDE.md](TESTING_GUIDE.md) untuk troubleshooting
3. Lihat [MIDTRANS_SETUP.md](MIDTRANS_SETUP.md) untuk payment gateway

---

## ⚡ Quick Commands

```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Reset database
php artisan migrate:fresh --seed

# Start server
php artisan serve

# Check routes
php artisan route:list
```

---

**Status:** ✅ **READY FOR DEMO & PRESENTATION**

**Last Updated:** December 25, 2025
"harga12": 80000,
"harga6": 50000,
"nama_kategori": "Kamera"
},
{
// ...
}
]
}

````
### **GET** `/alat/{id}`
```json
{
    "message": "success",
    "data": {
        "id": 1,
        "kategori_id": 1,
        "nama_alat": "Sony a7ii Body Only",
        "harga24": 200000,
        "harga12": 175000,
        "harga6": 125000
    },
    "booked": [
        {
            "start": "2022-07-09 21:00:00",
            "end": "2022-07-10 21:00:00"
        },
        {
            "start": "2022-07-08 13:00:00",
            "end": "2022-07-09 01:00:00"
        },
        {
            "start": "2022-09-20 14:01:00",
            "end": "2022-09-21 14:01:00"
        }
    ]
}
````

### **GET** `/category`

```json
{
    "message": "success",
    "data": [
        {
            "id": 1,
            "nama_kategori": "Kamera"
        },
        {
            "id": 2,
            "nama_kategori": "Lensa"
        },
        {
            // ...
        }
    ]
}
```
