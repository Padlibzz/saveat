<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login_identifier' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->login_identifier)
            ->orWhere('username', $request->login_identifier)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email/Username atau password salah.');
        }

        if ($user->status !== UserStatus::AKTIF->value) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        Auth::login($user);

        ActivityLog::catat($user->id, 'login', 'User login berhasil.');

        return redirect('/dashboard-konsumen')
            ->with('success', 'Login berhasil.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telphone' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telphone' => $request->no_telphone,
            'peran' => 'konsumen',
            'status' => UserStatus::AKTIF->value,
        ]);

        ActivityLog::catat(
            $user->id,
            'register',
            'User baru mendaftar.'
        );

        return redirect('/auth/login')
            ->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        // PERBAIKAN: Tidak menghapus token saat ini seketika agar tidak terjadi race condition di Frontend
        // Biarkan token lama expired dengan sendirinya sesuai konfigurasi sanctum (config/sanctum.php)

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'pesan' => 'Token berhasil diperbarui',
            'access_token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        ActivityLog::catat($request->user()->id, 'logout', 'User logout.');

        $request->user()->currentAccessToken()->delete();

        return response()->json(['pesan' => 'Logout berhasil.'], 200);
    }
}
