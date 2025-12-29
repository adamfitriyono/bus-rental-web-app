<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index() {
        return view('registration');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'same:password'],
            'nomor_hp' => ['required', 'string', 'regex:/^(\+62|08)[0-9]{9,13}$/', 'max:15']
        ], [
            'name.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'password.min' => 'Password minimal 6 karakter',
            'password_confirmation.same' => 'Konfirmasi password tidak sama',
            'nomor_hp.regex' => 'Nomor HP harus dimulai dengan +62 atau 08 dan minimal 10 digit'
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'aktif'; // Langsung aktif
        $validated['email_verified'] = true; // Langsung verified
        $validated['email_verified_at'] = now();
        $validated['role'] = 0; // Default member
        
        // Remove password_confirmation
        unset($validated['password_confirmation']);

        $user = User::create($validated);

        return redirect()->route('login')
                         ->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
    }
}
