<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecurityController extends Controller
{
    /**
     * Menampilkan Form Privasi & Keamanan
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.security', compact('user'));
    }

    /**
     * Memproses pembaruan kata sandi
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.mixedCase' => 'Kata sandi baru harus kombinasi huruf besar dan kecil.',
            'password.numbers' => 'Kata sandi baru harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Validasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.']);
        }

        // Simpan password baru terenkripsi
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui!');
    }
}