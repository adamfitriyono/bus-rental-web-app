<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Public Routes - Sistem Tiket Bis
Route::get('/', [SearchController::class, 'index'])->name('home');
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::get('/bus/{id}', [BookingController::class, 'showBusDetail'])->name('bus.detail');
Route::get('/seat-layout/{jadwal_id}', [BookingController::class, 'getSeatLayout'])->name('seat.layout');

// Auth Routes
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.attempt');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/daftar', [RegisterController::class, 'index'])->name('daftar');
Route::post('/daftar', [RegisterController::class, 'store'])->name('register.store');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

Route::get('/forget-password',[ForgetPasswordController::class,'index'])->name('forgetpassword.index');
Route::post('/forget-password',[ForgetPasswordController::class,'sendResetLink'])->name('forgetpassword.sendlink');

Route::get('/reset/{token}',[ForgetPasswordController::class,'resetPasswordIndex']);
Route::post('/reset',[ForgetPasswordController::class,'resetPassword'])->name('resetpassword');

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // User Management
    Route::get('/admin/usermanagement', [AdminController::class, 'usermanagement'])->name('admin.user');
    Route::post('/admin/usermanagement/new', [AdminController::class, 'newUser'])->name('user.new');
    Route::patch('/admin/user/promote/{id}', [UserController::class, 'promote'])->name('user.promote');
    Route::patch('/admin/user/demote/{id}', [UserController::class, 'demote'])->name('user.demote');

    // Rute Management
    Route::resource('/admin/rute', RuteController::class)->names([
        'index' => 'admin.rute.index',
        'create' => 'admin.rute.create',
        'store' => 'admin.rute.store',
        'show' => 'admin.rute.show',
        'edit' => 'admin.rute.edit',
        'update' => 'admin.rute.update',
        'destroy' => 'admin.rute.destroy',
    ]);

    // Bus Management
    Route::resource('/admin/bus', BusController::class)->names([
        'index' => 'admin.bus.index',
        'create' => 'admin.bus.create',
        'store' => 'admin.bus.store',
        'show' => 'admin.bus.show',
        'edit' => 'admin.bus.edit',
        'update' => 'admin.bus.update',
        'destroy' => 'admin.bus.destroy',
    ]);

    // Jadwal Management
    Route::resource('/admin/jadwal', JadwalController::class)->names([
        'index' => 'admin.jadwal.index',
        'create' => 'admin.jadwal.create',
        'store' => 'admin.jadwal.store',
        'show' => 'admin.jadwal.show',
        'edit' => 'admin.jadwal.edit',
        'update' => 'admin.jadwal.update',
        'destroy' => 'admin.jadwal.destroy',
    ]);
    Route::post('/admin/jadwal/bulk-create', [JadwalController::class, 'bulkCreate'])->name('admin.jadwal.bulk');

    // Pemesanan Management
    Route::get('/admin/pemesanan', [PemesananController::class, 'index'])->name('admin.pemesanan.index');
    Route::get('/admin/pemesanan/{id}', [PemesananController::class, 'show'])->name('admin.pemesanan.show');
    Route::post('/admin/pemesanan/verify-payment/{id}', [PemesananController::class, 'verifyPayment'])->name('admin.pemesanan.verify');
    Route::post('/admin/pemesanan/cancel/{id}', [PemesananController::class, 'cancel'])->name('admin.pemesanan.cancel');

    // Reports
    Route::get('/admin/laporan/penjualan', [ReportController::class, 'penjualan'])->name('admin.report.penjualan');
    Route::get('/admin/laporan/keuangan', [ReportController::class, 'keuangan'])->name('admin.report.keuangan');
    Route::get('/admin/laporan/occupancy', [ReportController::class, 'occupancy'])->name('admin.report.occupancy');
});

// User Routes (Authenticated)
Route::middleware('auth')->group(function() {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/history', [DashboardController::class, 'history'])->name('dashboard.history');
    Route::get('/user/history', [DashboardController::class, 'history'])->name('user.history');
    Route::get('/ticket-detail/{id}', [DashboardController::class, 'ticketDetail'])->name('ticket.detail');
    Route::get('/user/ticket-detail/{id}', [DashboardController::class, 'ticketDetail'])->name('user.ticket.detail');
    Route::post('/cancel-booking/{id}', [DashboardController::class, 'cancelBooking'])->name('booking.cancel');

    // Booking
    Route::post('/lock-seat/{jadwal_id}', [BookingController::class, 'lockSeat'])->name('seat.lock');
    Route::post('/add-to-cart/{jadwal_id}', [BookingController::class, 'addToCart'])->name('cart.add');
    Route::get('/passenger-data', [BookingController::class, 'passengerData'])->name('passenger.data');
        Route::post('/passenger-data', [BookingController::class, 'storePassenger'])->name('passenger.store');
    Route::post('/checkout', [BookingController::class, 'checkout'])->name('checkout');

    // Payment
    Route::get('/payment-summary', [PaymentController::class, 'showPaymentSummary'])->name('payment.summary');
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment-success/{id}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment-upload/{id}', [PaymentController::class, 'uploadBuktiTransfer'])->name('payment.upload');
    Route::post('/upload-bukti/{id}', [PaymentController::class, 'uploadBuktiTransfer'])->name('payment.upload.bukti');
    Route::get('/payment-gateway/{id}', [PaymentController::class, 'paymentGateway'])->name('payment.gateway');
    Route::post('/payment-callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
    Route::get('/payment-check-status/{id}', [PaymentController::class, 'checkStatus'])->name('payment.check.status');

    // Ticket
    Route::get('/ticket/{kode}', [TicketController::class, 'viewTicket'])->name('ticket.view');
    Route::get('/download-ticket/{kode}', [TicketController::class, 'downloadTicket'])->name('ticket.download');

    // Account Settings
    Route::get('/akun/pengaturan', [UserController::class, 'edit'])->name('akun.pengaturan');
    Route::patch('/akun/pengaturan', [UserController::class, 'update'])->name('akun.update');
    Route::patch('/changepass', [UserController::class, 'changePassword'])->name('changepassword');
});

Route::get('/logout',[AuthController::class, 'logout'])->name('logout');
