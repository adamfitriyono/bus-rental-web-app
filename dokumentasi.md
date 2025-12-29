# 📱 PROMPT CURSOR AI
## Sistem Penjualan Tiket Bis Online Melalui Website

**Dokumentasi Lengkap untuk Pengembangan Aplikasi**  
*Versi 1.0 | Desember 2025*

---

## 📋 Daftar Isi

1. [Konteks Proyek](#1-konteks-proyek)
2. [Fitur-Fitur Utama (7 Modul)](#2-fitur-fitur-utama-7-modul)
3. [Database Schema Lengkap](#3-database-schema-lengkap)
4. [Struktur Folder Project](#4-struktur-folder-project)
5. [Fitur Tambahan yang Disarankan](#5-fitur-tambahan-yang-disarankan)
6. [Timeline Implementasi (8 Minggu)](#6-timeline-implementasi-8-minggu)
7. [Parameter Kualitas Kode](#7-parameter-kualitas-kode-yang-diharapkan)
8. [Dependency & Library](#8-dependency--library-yang-direkomendasikan)
9. [Cara Menggunakan Prompt di Cursor AI](#9-cara-menggunakan-prompt-di-cursor-ai)
10. [Catatan Penting](#10-catatan-penting)

---

## 1️⃣ Konteks Proyek

Anda sedang mengembangkan sebuah **Sistem Penjualan Tiket Bis Online** untuk PO. Kalingga Jaya (Jepara, Jawa Tengah) berdasarkan dokumentasi DPPL (Deskripsi Perancangan Perangkat Lunak).

### Spesifikasi Teknis:

- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (vanilla atau jQuery)
- **Backend:** PHP 8.0+
- **Database:** MySQL 8.0+
- **Server:** Apache/Nginx
- **Arsitektur:** Three-Layer Architecture (Presentation, Application/Logic, Data)
- **Development Tools:** VS Code, XAMPP/Laragon, Git

---

## 2️⃣ Fitur-Fitur Utama (7 Modul)

### MODUL 1: Registrasi & Autentikasi

#### 1.1 Fitur Registrasi

Form pendaftaran dengan validasi:
- Nama lengkap (hanya huruf dan spasi, minimal 3 karakter)
- Email (format email valid, check duplikasi di database)
- Nomor HP (format +62 atau 08, minimal 10 digit)
- Password (minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol)
- Konfirmasi Password (harus sama dengan password)

**Implementasi:**
- Validasi real-time di frontend menggunakan JavaScript
- Hash password menggunakan bcrypt atau `password_hash()` PHP
- Kirim email konfirmasi setelah registrasi berhasil
- User status default: "pending" hingga email dikonfirmasi
- Error handling untuk duplikasi email/username

#### 1.2 Fitur Login

- Form login dengan email dan password
- Validasi input di frontend dan backend
- Implementasi session management menggunakan `$_SESSION` PHP
- Proteksi CSRF menggunakan token
- Rate limiting untuk mencegah brute force (max 5 attempt/15 menit)
- Remember me functionality (optional, dengan secure cookie)
- Redirect ke dashboard jika login berhasil
- Error message yang informatif jika login gagal

#### 1.3 Fitur Logout

- Destroy session dengan aman
- Clear cookies jika ada
- Redirect ke halaman login

> **💡 Tips:** Implementasikan password reset functionality untuk user yang lupa password.

---

### MODUL 2: Pencarian Tiket

#### 2.1 Interface Pencarian

- **Dropdown pilih kota asal** (fetch dari database)
- **Dropdown pilih kota tujuan** (fetch dari database, tidak boleh sama dengan asal)
- **Date picker** untuk tanggal keberangkatan (min: hari ini, max: 30 hari ke depan)
- **Checkbox "Pulang Pergi"** (optional, untuk feature future)
- **Button "Cari Tiket"**

#### 2.2 Backend Processing

- Validasi input di sisi server
- Query database untuk mencari rute yang sesuai
- Query jadwal berdasarkan rute dan tanggal
- Filter bus yang tersedia (kursi > 0)
- Return data dengan format JSON untuk AJAX

**Query Database:**
```sql
Q-003: SELECT * FROM rute WHERE kota_asal=? AND kota_tujuan=?

Q-004: SELECT jadwal.*, bus.* FROM jadwal 
       JOIN bus ON jadwal.id_bus = bus.id_bus 
       WHERE jadwal.id_rute=? AND DATE(jadwal.tanggal)=?
```

#### 2.3 Frontend Display

- Loading indicator saat pencarian
- Menampilkan hasil sebagai card/list item
- Setiap item menampilkan:
  - Nama PO Bus
  - Jam berangkat - Jam tiba
  - Durasi perjalanan
  - Jenis bus (Eksekutif/VIP/Super Executive)
  - Harga tiket
  - Jumlah kursi tersedia
  - Button "Pilih Bus"

---

### MODUL 3: Pemilihan Bus & Kursi

#### 3.1 Fitur Pemilihan Bus

Display detail bus terpilih:
- Nama PO dan nomor bus
- Jam keberangkatan dan tiba
- Jenis kelas (Eksekutif/VIP/Super Executive)
- Fasilitas yang disediakan (AC, WiFi, dll)
- Harga per kursi
- Rating atau review (optional)

#### 3.2 Fitur Pemilihan Kursi

**Layout Kursi Bus:**
- Tampilkan visual layout kursi bus (grid 2x6, 3x6, dll sesuai jenis)
- Kursi kosong: warna abu-abu, bisa diklik
- Kursi terisi: warna merah, tidak bisa diklik
- Kursi terpilih: warna hijau, highlighted
- Driver seat & emergency exit ditandai khusus

**Interaksi:**
- Click pada kursi kosong untuk memilih
- Click lagi untuk deselect
- Tampilkan nomor kursi yang dipilih
- Max kursi yang bisa dipilih: sesuai ketersediaan
- Update harga total otomatis (jumlah kursi × harga per kursi)
- Button "Lanjut ke Pembayaran"

**Backend:**
```sql
Q-005: SELECT kursi_tersedia FROM bus WHERE id_bus=?
       SELECT nomor_kursi, status_kursi FROM kursi 
       WHERE id_bus=? AND id_jadwal=?
```

---

### MODUL 4: Data Penumpang & Pembayaran

#### 4.1 Fitur Data Penumpang

Form untuk setiap penumpang:
- Nama penumpang (wajib)
- Tipe identitas (KTP/SIM/Paspor)
- Nomor identitas (wajib)
- Nomor HP (wajib, untuk notifikasi)

Jika multiple kursi, repeat form untuk setiap penumpang. Pre-fill dengan data user jika diperlukan (untuk penumpang utama). Validasi semua field.

#### 4.2 Fitur Ringkasan Pemesanan

Display:
- Rute perjalanan (asal → tujuan)
- Tanggal dan jam keberangkatan
- Nama bus dan jenis kelas
- Nomor kursi yang dipesan
- Harga per kursi
- Subtotal (jumlah kursi × harga)
- Biaya admin (tetap, misal 5%)
- **Total pembayaran (dengan kalkulator real-time)**
- Data penumpang (nama, identitas)

#### 4.3 Fitur Metode Pembayaran

**Dropdown pilih metode:**
- Transfer Bank Manual (dengan nomor rekening)
- E-Wallet (GCash, Dana, OVO, LinkAja)
- Virtual Account (BCA, BNI, Mandiri, BRI)
- Kartu Kredit (Visa, Mastercard)

**Detail metode pembayaran:**
- Untuk transfer bank: tampilkan nomor rekening, nama penerima, nominal
- Untuk e-wallet: generate qr code atau link pembayaran
- Untuk VA: generate nomor VA unik
- Untuk kartu kredit: redirect ke payment gateway

#### 4.4 Fitur Proses Pembayaran

**Frontend:**
- Button "Lanjut ke Pembayaran"
- Loading state selama processing
- Timeout handling (30 detik)
- Error handling dengan pesan yang jelas

**Backend:**
- Validasi data pemesanan
- Generate invoice unik
- Simpan pemesanan ke database dengan status "pending"
- Integrate dengan payment gateway (Midtrans, iPaymu, atau Xendit)
- Generate transaction ID
- Return payment link/form ke frontend

**Query Database:**
```sql
Q-006: INSERT INTO pembayaran (id_pemesanan, metode, total, status) 
       VALUES (?, ?, ?, ?)

Q-007: INSERT INTO pemesanan (id, id_user, id_jadwal, kursi, status) 
       VALUES (?, ?, ?, ?, ?)

Q-008: UPDATE bus SET kursi_tersedia = kursi_tersedia - ? 
       WHERE id_bus=?

Q-009: UPDATE pembayaran SET status='Lunas' WHERE id_pemesanan=?
```

---

### MODUL 5: Penerimaan E-Ticket

#### 5.1 Fitur Generate E-Ticket

Setelah pembayaran berhasil:
- Generate kode tiket unik (format: KJ-YYYYMMDD-XXXXX)
- Generate QR code berisi informasi tiket
- Buat file PDF dengan template profesional

**Isi E-Ticket:**
- Kode booking/pemesanan
- Kode tiket
- QR code
- Nama penumpang
- Rute perjalanan (asal → tujuan)
- Tanggal keberangkatan
- Jam keberangkatan & estimasi tiba
- Nomor kursi
- Nama driver (optional)
- Instruksi boarding
- Nomor telepon customer service

#### 5.2 Fitur Pengiriman Email

Kirim email otomatis ke user dengan:
- E-ticket sebagai attachment (PDF)
- Link download tiket (valid 7 hari)
- Instruksi perjalanan
- Nomor telepon customer service
- Syarat & ketentuan

#### 5.3 Fitur Download & Display E-Ticket

**Di halaman aplikasi:**
- Display tiket dengan preview PDF
- Button "Download PDF"
- Button "Kirim ke Email Lagi"
- Button "Bagikan" (social media share)

**Fitur Riwayat Tiket:**
- User bisa melihat semua tiket mereka
- Filter berdasarkan status (akan berangkat, sudah berangkat, dibatalkan)
- Search berdasarkan nomor booking

**Query Database:**
```sql
Q-010: INSERT INTO tiket (kode_tiket, id_pemesanan, qr_code) 
       VALUES (?, ?, ?)

Q-011: SELECT * FROM pemesanan WHERE id=?
```

---

### MODUL 6: Dashboard User

#### 6.1 Fitur Dashboard User

- Welcome message dengan nama user
- Quick stats:
  - Total tiket terbeli
  - Tiket mendatang (upcoming)
  - Tiket lampau (history)
- Menu navigasi:
  - Cari tiket baru
  - Riwayat pemesanan
  - Profile settings
  - Logout

#### 6.2 Fitur Riwayat Pemesanan

Tabel/list dengan kolom:
- Nomor booking
- Rute perjalanan
- Tanggal keberangkatan
- Status pemesanan
- Status pembayaran
- Total pembayaran
- Aksi (lihat detail, download tiket, batalkan)

#### 6.3 Fitur Pembatalan Pemesanan

Izinkan pembatalan jika:
- Status pembayaran = "Lunas"
- Tanggal keberangkatan > 24 jam dari sekarang

Terapkan kebijakan refund:
- Pembatalan > 24 jam: refund 90%
- Pembatalan 12-24 jam: refund 50%
- Pembatalan < 12 jam: refund 0% (forfeit)

Update status tiket menjadi "dibatalkan", kembalikan kursi ke "tersedia", dan proses refund otomatis ke metode pembayaran asli.

---

### MODUL 7: Admin Panel

#### 7.1 Dashboard Admin

- Summary statistics:
  - Total penjualan (hari, bulan, tahun)
  - Total tiket terjual
  - Total pendapatan
  - Metode pembayaran paling populer
  - Rute paling sering dipesan
- Chart/grafik (menggunakan Chart.js atau Recharts)
- Real-time monitoring transaksi terbaru

#### 7.2 Manajemen Data Bus

**CRUD Bus:**
- Create: form tambah bus dengan field nama, jenis kelas, kapasitas, biaya operasional
- Read: tabel daftar semua bus
- Update: edit informasi bus
- Delete: hapus bus (soft delete)
- Tampilkan status ketersediaan (kursi kosong/terisi)

#### 7.3 Manajemen Rute & Jadwal

**CRUD Rute:**
- Buat rute baru (kota asal, kota tujuan, jarak, estimasi waktu)
- Edit rute
- Hapus rute (jika tidak ada jadwal aktif)

**CRUD Jadwal:**
- Create: form jadwal dengan field bus, rute, tanggal, jam berangkat, harga
- Read: tabel daftar jadwal dengan filter (tanggal, rute, status)
- Update: edit jadwal (harga, jam, ketersediaan)
- Delete: hapus jadwal
- Bulk create: tambah jadwal untuk beberapa hari dengan template
- Status jadwal: aktif, dibatalkan, selesai

#### 7.4 Manajemen Pemesanan & Pembayaran

**View semua pemesanan:**
- Tabel dengan filter (status, tanggal, rute, metode pembayaran)
- Kolom: ID pemesanan, user, rute, tanggal, jumlah tiket, status pembayaran, total, aksi
- Aksi: lihat detail, konfirmasi pembayaran manual (untuk transfer bank), batalkan

**Verifikasi Pembayaran:**
- Untuk transfer bank: admin bisa upload bukti transfer
- Sistem auto-verify untuk e-wallet/VA (dari webhook payment gateway)
- Manual verify dengan checkbox
- Kirim email notifikasi ke user setelah verified

#### 7.5 Manajemen User

**CRUD User:**
- Lihat daftar semua user terdaftar
- Edit data user
- Ban/suspend user jika ada pelanggaran
- Lihat histori pemesanan user

#### 7.6 Reporting & Export

Generate laporan:
- Laporan penjualan (periode, format PDF/Excel)
- Laporan keuangan (pendapatan, biaya, profit)
- Laporan occupancy (tingkat penempatan kursi per jadwal)
- Laporan user (jumlah user baru, aktif, dll)
- Export data ke Excel/CSV

**Query Database Admin:**
```sql
Q-012: SELECT * FROM pemesanan ORDER BY tanggal_pemesanan DESC

Q-013: UPDATE pemesanan SET status_pemesanan=? WHERE id_pemesanan=?

Q-014: SELECT COUNT(*) as total_penjualan, 
              SUM(total) as pendapatan 
       FROM pembayaran 
       WHERE status_pembayaran='Lunas' AND DATE(tanggal) BETWEEN ? AND ?

Q-015: INSERT INTO jadwal (id_rute, id_bus, jam_berangkat, tanggal, harga) 
       VALUES (?, ?, ?, ?, ?)
```

---

## 3️⃣ Database Schema Lengkap

### Tabel Users

```sql
CREATE TABLE users (
  id_user INT PRIMARY KEY AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  nomor_hp VARCHAR(15) NOT NULL,
  status VARCHAR(20) DEFAULT 'pending',
  email_verified BOOLEAN DEFAULT FALSE,
  email_verified_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
);
```

### Tabel Rute

```sql
CREATE TABLE rute (
  id_rute INT PRIMARY KEY AUTO_INCREMENT,
  kota_asal VARCHAR(50) NOT NULL,
  kota_tujuan VARCHAR(50) NOT NULL,
  jarak_km INT,
  estimasi_jam INT,
  harga_base INT,
  status VARCHAR(20) DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_asal_tujuan (kota_asal, kota_tujuan)
);
```

### Tabel Bus

```sql
CREATE TABLE bus (
  id_bus INT PRIMARY KEY AUTO_INCREMENT,
  nama_bus VARCHAR(100) NOT NULL,
  jenis_kelas VARCHAR(50) NOT NULL,
  plat_nomor VARCHAR(20) UNIQUE NOT NULL,
  kapasitas_kursi INT NOT NULL,
  kursi_tersedia INT NOT NULL,
  tahun_pembuatan INT,
  fasilitas TEXT,
  status VARCHAR(20) DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status)
);
```

### Tabel Jadwal

```sql
CREATE TABLE jadwal (
  id_jadwal INT PRIMARY KEY AUTO_INCREMENT,
  id_rute INT NOT NULL,
  id_bus INT NOT NULL,
  jam_berangkat TIME NOT NULL,
  jam_tiba TIME NOT NULL,
  tanggal DATE NOT NULL,
  harga_tiket INT NOT NULL,
  status VARCHAR(20) DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_rute) REFERENCES rute(id_rute),
  FOREIGN KEY (id_bus) REFERENCES bus(id_bus),
  INDEX idx_tanggal (tanggal),
  INDEX idx_rute_tanggal (id_rute, tanggal)
);
```

### Tabel Kursi

```sql
CREATE TABLE kursi (
  id_kursi INT PRIMARY KEY AUTO_INCREMENT,
  id_jadwal INT NOT NULL,
  id_bus INT NOT NULL,
  nomor_kursi VARCHAR(10) NOT NULL,
  status_kursi VARCHAR(20) DEFAULT 'tersedia',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal),
  FOREIGN KEY (id_bus) REFERENCES bus(id_bus),
  INDEX idx_jadwal_status (id_jadwal, status_kursi),
  UNIQUE KEY unique_kursi (id_jadwal, nomor_kursi)
);
```

### Tabel Pemesanan

```sql
CREATE TABLE pemesanan (
  id_pemesanan INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT NOT NULL,
  id_jadwal INT NOT NULL,
  id_bus INT NOT NULL,
  nomor_kursi VARCHAR(100) NOT NULL,
  jumlah_kursi INT NOT NULL,
  status_pemesanan VARCHAR(20) DEFAULT 'pending',
  tanggal_pemesanan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user),
  FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal),
  FOREIGN KEY (id_bus) REFERENCES bus(id_bus),
  INDEX idx_user (id_user),
  INDEX idx_status (status_pemesanan),
  INDEX idx_tanggal (tanggal_pemesanan)
);
```

### Tabel Data Penumpang

```sql
CREATE TABLE penumpang (
  id_penumpang INT PRIMARY KEY AUTO_INCREMENT,
  id_pemesanan INT NOT NULL,
  nama_penumpang VARCHAR(100) NOT NULL,
  tipe_identitas VARCHAR(20) NOT NULL,
  nomor_identitas VARCHAR(50) NOT NULL,
  nomor_hp VARCHAR(15),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan),
  INDEX idx_pemesanan (id_pemesanan)
);
```

### Tabel Pembayaran

```sql
CREATE TABLE pembayaran (
  id_pembayaran INT PRIMARY KEY AUTO_INCREMENT,
  id_pemesanan INT NOT NULL,
  metode_pembayaran VARCHAR(50) NOT NULL,
  jumlah INT NOT NULL,
  status_pembayaran VARCHAR(20) DEFAULT 'pending',
  kode_transaksi VARCHAR(50),
  referensi_eksternal VARCHAR(100),
  bukti_transfer LONGBLOB,
  tanggal_pembayaran TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan),
  INDEX idx_status (status_pembayaran),
  INDEX idx_kode_transaksi (kode_transaksi),
  UNIQUE KEY unique_transaksi (kode_transaksi)
);
```

### Tabel Tiket

```sql
CREATE TABLE tiket (
  id_tiket INT PRIMARY KEY AUTO_INCREMENT,
  kode_tiket VARCHAR(50) UNIQUE NOT NULL,
  id_pemesanan INT NOT NULL,
  qr_code LONGTEXT,
  file_pdf LONGBLOB,
  status_tiket VARCHAR(20) DEFAULT 'aktif',
  tanggal_terbit TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan),
  INDEX idx_kode_tiket (kode_tiket),
  INDEX idx_status (status_tiket)
);
```

### Tabel Notifikasi/Email Log

```sql
CREATE TABLE notifikasi (
  id_notifikasi INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT NOT NULL,
  tipe VARCHAR(50) NOT NULL,
  email_penerima VARCHAR(100) NOT NULL,
  judul_email VARCHAR(255),
  isi_email LONGTEXT,
  status_pengiriman VARCHAR(20) DEFAULT 'pending',
  tanggal_pengiriman TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user),
  INDEX idx_status (status_pengiriman),
  INDEX idx_user (id_user)
);
```

---

## 4️⃣ Struktur Folder Project

```
bus-ticket-system/
├── index.php                          # Entry point
├── config/
│   ├── database.php                   # Database connection
│   ├── constants.php                  # Global constants
│   └── email.php                      # Email configuration
├── public/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── style.css
│   │   └── responsive.css
│   ├── js/
│   │   ├── bootstrap.min.js
│   │   ├── jquery.min.js
│   │   ├── main.js
│   │   ├── validate.js
│   │   └── search.js
│   └── images/
│       └── logo.png
├── app/
│   ├── controllers/
│   │   ├── AuthController.php         # Register, Login, Logout
│   │   ├── SearchController.php       # Pencarian tiket
│   │   ├── BookingController.php      # Pemesanan tiket
│   │   ├── PaymentController.php      # Pembayaran
│   │   ├── TicketController.php       # E-ticket
│   │   ├── UserController.php         # User profile
│   │   ├── AdminController.php        # Admin dashboard
│   │   ├── BusController.php          # Manajemen bus
│   │   └── ReportController.php       # Laporan
│   ├── models/
│   │   ├── User.php
│   │   ├── Rute.php
│   │   ├── Bus.php
│   │   ├── Jadwal.php
│   │   ├── Pemesanan.php
│   │   ├── Pembayaran.php
│   │   ├── Tiket.php
│   │   ├── Penumpang.php
│   │   └── Notifikasi.php
│   └── views/
│       ├── auth/
│       │   ├── register.php
│       │   ├── login.php
│       │   └── forgot-password.php
│       ├── user/
│       │   ├── dashboard.php
│       │   ├── search.php
│       │   ├── booking.php
│       │   ├── payment.php
│       │   ├── ticket.php
│       │   ├── history.php
│       │   └── profile.php
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── bus/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   ├── jadwal/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   ├── pemesanan/
│       │   │   ├── index.php
│       │   │   └── detail.php
│       │   ├── pembayaran/
│       │   │   └── index.php
│       │   ├── laporan/
│       │   │   ├── penjualan.php
│       │   │   ├── keuangan.php
│       │   │   └── occupancy.php
│       │   └── user/
│       │       └── index.php
│       └── layouts/
│           ├── header.php
│           ├── footer.php
│           ├── sidebar-admin.php
│           └── navbar-user.php
├── lib/
│   ├── Database.php                   # Database abstraction class
│   ├── Router.php                     # Simple routing
│   ├── Auth.php                       # Authentication helper
│   ├── Validation.php                 # Form validation
│   ├── Email.php                      # Email sending (PHPMailer)
│   ├── PaymentGateway.php             # Payment gateway integration
│   ├── QRCode.php                     # QR code generation
│   └── PDF.php                        # PDF generation (TCPDF)
├── helpers/
│   ├── functions.php                  # Helper functions
│   ├── date.php                       # Date helpers
│   ├── currency.php                   # Currency formatting
│   └── validation.php                 # Validation helpers
├── sql/
│   ├── schema.sql                     # Database schema
│   └── seed.sql                       # Demo data
├── tests/
│   ├── AuthTest.php
│   ├── BookingTest.php
│   └── PaymentTest.php
├── .env.example                       # Environment variables template
├── .gitignore
├── composer.json                      # PHP dependencies
├── README.md
└── LICENSE
```

---

## 5️⃣ Fitur Tambahan yang Disarankan

### 1. Keamanan
- Implement HTTPS/SSL
- Input sanitization dan prepared statements
- XSS protection (htmlspecialchars, CSP headers)
- CSRF tokens di semua form
- Rate limiting
- Password reset functionality
- Two-factor authentication (optional)

### 2. Performance
- Database indexing
- Caching (Redis untuk session/cache)
- Image optimization
- Lazy loading
- Minify CSS/JS
- CDN untuk static assets

### 3. Notifikasi
- Email notification untuk setiap tahap (registrasi, pembayaran, tiket)
- SMS notification (optional, menggunakan Twilio/Nexmo)
- In-app notifications
- Push notifications (optional)

### 4. Integrasi Payment Gateway
- Integrasikan dengan Midtrans/iPaymu/Xendit
- Implement webhook untuk automatic payment confirmation
- Error handling untuk failed transactions
- Refund mechanism

### 5. Analytics
- Google Analytics integration
- Track user behavior
- Conversion funnel tracking
- A/B testing (optional)

### 6. Mobile Responsiveness
- Design mobile-first
- Test pada berbagai device sizes
- Touch-friendly UI elements
- Progressive Web App (PWA) optional

### 7. Testing & Documentation
- Unit tests (PHPUnit)
- Integration tests
- Load testing
- API documentation (untuk future mobile app)
- User documentation

---

## 6️⃣ Timeline Implementasi (8 Minggu)

| MINGGU | FASE | DELIVERABLE |
|--------|------|-------------|
| **1** | Setup & Database | Project structure, database schema, seeding data |
| **1-2** | Authentication | Registration, login, logout, email verification |
| **2-4** | Core User Features | Search, booking, kursi selection, passenger form |
| **4-5** | Payment & E-Ticket | Payment gateway, e-ticket generation, email delivery |
| **5** | User Dashboard | Dashboard, riwayat, pembatalan, refund |
| **6-7** | Admin Panel | Dashboard, CRUD bus/jadwal, management, reports |
| **8** | Testing & Deploy | Bug fixing, security audit, deployment |

---

## 7️⃣ Parameter Kualitas Kode yang Diharapkan

### Code Style
- Follow PSR-12 PHP coding standard
- Use meaningful variable & function names (English)
- Add comments untuk logika kompleks
- Consistent indentation (2 spaces atau 1 tab)

### Architecture
- Use MVC pattern strictly
- Separation of concerns
- DRY principle (Don't Repeat Yourself)
- SOLID principles

### Database
- Use prepared statements untuk prevent SQL injection
- Proper indexing untuk performance
- Foreign key relationships
- Transaction handling untuk critical operations

### Frontend
- Responsive design (mobile-first)
- Accessibility (WCAG 2.1)
- Progressive enhancement
- Clean & semantic HTML

### Error Handling
- Try-catch blocks untuk exception handling
- User-friendly error messages
- Logging untuk debugging
- Graceful degradation

---

## 8️⃣ Dependency & Library yang Direkomendasikan

### Backend
- **PHPMailer** - untuk email sending
- **TCPDF** - untuk PDF generation
- **phpqrcode** - untuk QR code generation
- **JWT** - untuk API authentication (jika ada)

### Frontend
- **jQuery** - untuk AJAX dan DOM manipulation
- **Bootstrap 5** - untuk responsive design
- **Moment.js** - untuk date handling
- **Chart.js** - untuk statistics visualization
- **Toastr** - untuk notifications

### Development
- **Composer** - PHP package manager
- **Git** - version control
- **PHPUnit** - testing
- **Postman** - API testing

---

## 9️⃣ Cara Menggunakan Prompt di Cursor AI

### Langkah-Langkah:

1. **Copy seluruh isi prompt ini** dari file markdown
2. **Buka Cursor AI** (aplikasi atau website)
3. **Paste prompt lengkap** ke dalam chat
4. **Tambahkan instruksi spesifik**, contoh:

```
Buatkan file AuthController.php dengan:
- Fungsi register dengan validasi lengkap
- Validasi email format
- Check duplikasi email di database
- Hash password dengan password_hash()
- Send email konfirmasi
- Implementasi error handling

Gunakan prepared statement untuk semua query database.
Ikuti PSR-12 coding standard.
```

5. **Cursor AI akan generate kode** sesuai konteks & kebutuhan

### Contoh Instruksi yang Efektif:

- "Buatkan controller untuk search tiket dengan AJAX response JSON"
- "Generate database schema SQL dengan proper indexing dan foreign keys"
- "Buat form pemilihan kursi dengan visual layout bus dan JavaScript interaksi"
- "Buatkan admin dashboard dengan Chart.js untuk statistik penjualan"
- "Implementasikan payment gateway integration dengan Midtrans"
- "Generate E-ticket PDF dengan TCPDF dan QR code"

### Tips Penting:

✅ **Memberikan instruksi yang spesifik dan detail akan menghasilkan kode yang lebih berkualitas dari Cursor AI.** Jelaskan fitur apa, library apa, coding standard apa yang ingin Anda gunakan.

---

## 🔟 Catatan Penting

### ✅ DO (Lakukan Ini)
- Ikuti struktur folder yang sudah ditentukan
- Gunakan environment variables untuk konfigurasi sensitif
- Implement proper error handling & logging
- Test setiap fitur sebelum production
- Document code dengan comments yang jelas
- Use consistent naming convention
- Implement input validation & sanitization
- Backup database regularly

### ❌ DON'T (Jangan Lakukan Ini)
- Jangan hardcode credentials (password, API key)
- Jangan gunakan md5 untuk password hashing
- Jangan trust user input tanpa validasi
- Jangan expose sensitive information di error messages
- Jangan overload controller dengan terlalu banyak logic
- Jangan lupa backup database regularly
- Jangan expose database error messages ke user
- Jangan bypass CSRF protection

### 💡 Saran Tambahan

Dokumentasi ini adalah panduan lengkap untuk pengembangan sistem. Namun, selalu ada ruang untuk improvement dan inovasi. Jika ada fitur tambahan atau perubahan kebutuhan, jangan ragu untuk mengkomunikasikan dengan stakeholder dan update dokumentasi.

---

## 📞 Dukungan

**Sistem Penjualan Tiket Bis Online Melalui Website**  
PO. Kalingga Jaya | Jepara, Jawa Tengah

Dokumentasi Prompt Cursor AI v1.0 | Desember 2025  
© 2025 Semua Hak Dilindungi