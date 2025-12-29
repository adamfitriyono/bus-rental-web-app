<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }
    
    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Rate limiting: max 5 attempts per 15 minutes
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.');
        }

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            // Check if there's an intended URL (from guest trying to book)
            if ($request->session()->has('url.intended')) {
                return redirect()->intended();
            }

            // Default redirect based on role
            if(Auth::user()->role != 0) {
                return redirect(route('admin.index'));
            } else {
                return redirect(route('dashboard.index'));
            }
        }

        RateLimiter::hit($key, 900); // 15 minutes
        return back()->with('error', 'Login gagal: email atau password salah');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Email verification
     */
    public function verifyEmail($token)
    {
        // Find user by verification token (stored in password_resets table temporarily)
        $reset = \DB::table('password_resets')->where('token', $token)->first();
        
        if (!$reset) {
            return redirect()->route('login')->with('error', 'Token verifikasi tidak valid');
        }

        $user = User::where('email', $reset->email)->first();
        
        if ($user) {
            $user->update([
                'email_verified' => true,
                'email_verified_at' => now(),
                'status' => 'aktif'
            ]);

            \DB::table('password_resets')->where('token', $token)->delete();

            return redirect()->route('login')->with('success', 'Email berhasil diverifikasi. Silakan login.');
        }

        return redirect()->route('login')->with('error', 'User tidak ditemukan');
    }
}
