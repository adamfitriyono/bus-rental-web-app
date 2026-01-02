<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function promote($id) {
        $user = User::find($id);
        $user->update([
            'role' => 1,
        ]);

        return back();
    }

    public function demote($id) {
        $user = User::find($id);
        $user->update([
            'role' => 0,
        ]);

        return back();
    }

    public function edit() {
        $user = User::find(Auth::id());
        if (!$user) {
            return redirect()->route('dashboard.index')->with('error', 'User tidak ditemukan');
        }
        return view('account', ['user' => $user]);
    }

    public function update(Request $request) {
        $user = User::find(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nomor_hp' => 'nullable|string|max:15'
        ], [
            'email.unique' => 'Email sudah terdaftar oleh user lain'
        ]);

        $user->update($validated);

        return back()->with('updated', 'Profil berhasil diperbarui');
    }

    public function changePassword(Request $request) {
        $user = User::find(Auth::id());

        $this->validate($request,[
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);

        if(Hash::check($request['oldPassword'], $user->password)) {
            $user->update([
                'password' => Hash::make($request['newPassword'])
            ]);
            return back()->with('updated','Password berhasil diubah');
        } else {
            return back()->with('message','Password saat ini salah');
        }

    }
}
