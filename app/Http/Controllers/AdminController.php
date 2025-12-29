<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Rute;
use App\Models\Bus;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index() {
        // Statistics for dashboard
        $totalUser = User::where('role', 0)->where('status', 'aktif')->count();
        $totalRute = Rute::where('status', 'aktif')->count();
        $totalBus = Bus::where('status', 'aktif')->count();
        $totalPemesanan = Pemesanan::where('status_pemesanan', '!=', 'dibatalkan')->count();
        
        // Pendapatan bulan ini
        $pendapatanBulanIni = Pembayaran::where('status_pembayaran', 'Lunas')
                                        ->whereMonth('tanggal_pembayaran', Carbon::now()->month)
                                        ->whereYear('tanggal_pembayaran', Carbon::now()->year)
                                        ->sum('jumlah');

        // Top rute populer (rute dengan pemesanan terbanyak)
        $topRutes = Rute::withCount(['jadwals as pemesanan_count' => function($query) {
                $query->select(\DB::raw('count(*)'))
                      ->from('pemesanans')
                      ->join('jadwals', 'jadwals.id_jadwal', '=', 'pemesanans.id_jadwal')
                      ->whereColumn('rutes.id_rute', 'jadwals.id_rute')
                      ->where('pemesanans.status_pemesanan', '!=', 'dibatalkan');
            }])
            ->orderBy('pemesanan_count', 'DESC')
            ->limit(5)
            ->get();

        // Top user (user dengan pemesanan terbanyak)
        $topUsers = User::where('role', 0)
                       ->withCount('pemesanans')
                       ->orderBy('pemesanans_count', 'DESC')
                       ->limit(5)
                       ->get();

        // Pemesanan pending (menunggu konfirmasi)
        $pemesananPending = Pemesanan::where('status_pemesanan', 'pending')
                                     ->with(['user', 'jadwal.rute'])
                                     ->orderBy('tanggal_pemesanan', 'DESC')
                                     ->limit(5)
                                     ->get();

        return view('admin.dashboard', [
            'loggedUsername' => Auth::user()->name,
            'total_user' => $totalUser,
            'total_rute' => $totalRute,
            'total_bus' => $totalBus,
            'total_pemesanan' => $totalPemesanan,
            'pendapatan_bulan_ini' => $pendapatanBulanIni,
            'top_rutes' => $topRutes,
            'top_users' => $topUsers,
            'pemesanan_pending' => $pemesananPending
        ]);
    }

    public function usermanagement() {
        $users = User::where('role', 0)->withCount('pemesanans')->get();

        return view('admin.user.user', [
            'users' => $users
        ]);
    }

    public function adminmanagement() {
        $admins = User::where('role', '!=', 0)->get();
        $users = User::where('role', 0)->get();

        return view('admin.user.admin_management', [
            'admins' => $admins,
            'users' => $users
        ]);
    }

    public function newUser(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:255',
            'nomor_hp' => 'required|max:15',
            'role' => 'required|in:0,1,2'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'aktif';
        $validated['email_verified'] = true; // Admin-created users are auto-verified
        $validated['email_verified_at'] = now();
        
        User::create($validated);

        return redirect(route('admin.user'))->with('success', 'User berhasil ditambahkan');
    }
}
