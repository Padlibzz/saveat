<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (! $user || ! Hash::check($request->password, $user->password)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['pesan' => 'Email/Username atau password salah.'], 401);
            }

            return back()->withErrors(['login_identifier' => 'Email/Username atau password salah.'])->withInput();
        }

        if ($user->status !== UserStatus::AKTIF->value) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['pesan' => 'Akun Anda telah dinonaktifkan. Hubungi admin.'], 403);
            }

            return back()->withErrors(['login_identifier' => 'Akun Anda dinonaktifkan. Hubungi admin.']);
        }

        ActivityLog::catat($user->id, 'login', 'User login berhasil.');

        if ($request->expectsJson() || $request->is('api/*')) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'pesan' => 'Login berhasil',
                'access_token' => $token,
                'peran' => $user->peran,
            ], 200);
        }

        Auth::login($user); 
        $request->session()->regenerate(); 

        if ($user->peran === 'admin') {
            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
        } elseif ($user->peran === 'merchant') {
            return redirect()->intended('/merchant/dashboard')->with('success', 'Selamat datang, Merchant!');
        }

        return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
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

        ActivityLog::catat($user->id, 'register', 'User baru mendaftar.');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'pesan' => 'Registrasi berhasil',
                'data' => $user,
            ], 201);
        }

        Auth::login($user); 

        return redirect('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'pesan' => 'Token berhasil diperbarui',
            'access_token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        ActivityLog::catat($request->user()->id, 'logout', 'User logout.');

        if ($request->expectsJson() || $request->is('api/*')) {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['pesan' => 'Logout berhasil.'], 200);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
