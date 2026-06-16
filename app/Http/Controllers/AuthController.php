<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login_identifier'  => 'required',
            'password'          => 'required',
        ]);

        $user = User::where('email', $request->login_identifier)
            ->orWhere('username', $request->login_identifier)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['pesan' => 'Email/Username atau password salah.'], 401);
        }

        // PERBAIKAN: Menggunakan Enum
        if ($user->status !== UserStatus::AKTIF->value) {
            return response()->json(['pesan' => 'Akun Anda telah dinonaktifkan. Hubungi admin.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        ActivityLog::catat($user->id, 'login', 'User login berhasil.');

        return response()->json([
            'pesan'         => 'Login berhasil',
            'access_token'  => $token,
            'peran'         => $user->peran,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|unique:users,username|max:50',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'no_telphone'   => 'required|string|max:20',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'no_telphone'   => $request->no_telphone,
            'peran'         => 'konsumen',
            'status'        => UserStatus::AKTIF->value, // PERBAIKAN: Menggunakan Enum
        ]);

        ActivityLog::catat($user->id, 'register', 'User baru mendaftar.');

        return response()->json([
            'pesan' => 'Registrasi berhasil',
            'data'  => $user,
        ], 201);
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