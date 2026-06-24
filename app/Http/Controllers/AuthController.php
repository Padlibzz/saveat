<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\ActivityLog;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/auth/login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            ActivityLog::catat($user->id, 'login', 'User login berhasil.');

            if ($request->wantsJson()) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'pesan' => 'Login berhasil',
                    'access_token' => $token,
                ], 200);
            } else {
                $request->session()->regenerate();

                return redirect()->route('dashboard')->with('success', 'Login berhasil.');
            }
        }

        $errorMessage = ($loginType === 'email') 
            ? 'Email atau password salah.' 
            : 'Username atau password salah.';

        if ($request->wantsJson()) {
            return response()->json(['pesan' => $errorMessage], 401);
        } else {
            return back()->withErrors([
                'login' => $errorMessage,
            ])->onlyInput('login');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telphone' => 'required|string|max:20',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
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

        Profil::create([
            'user_id' => $user->id,
            'tipe_profil' => 'konsumen',
        ]);

        ActivityLog::catat($user->id, 'register', 'User baru mendaftar.');

        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'pesan' => 'Registrasi berhasil',
                'data' => $user,
                'access_token' => $token,
            ], 201);
        } else {
            return redirect('/auth/login')
                ->with('success', 'Registrasi berhasil, silakan login.');
        }
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'pesan' => 'Token berhasil diperbarui',
            'access_token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            $user = $request->user();
            ActivityLog::catat($user->id, 'logout', 'User logout.');
            if (auth()->check()) {
                auth()->user()->currentAccessToken()->delete();
            }

            return response()->json(['pesan' => 'Logout berhasil.'], 200);

        } else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Logout berhasil.');
        }
    }
}
