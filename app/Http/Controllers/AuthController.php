<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['pesan' => 'Email/Username atau password salah'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'pesan' => 'Login berhasil',
            'access_token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required', 
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telphone' => 'required',
        ]);

        $user = User::create([
            'name' => $request->nama, 
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => 'konsumen',
            'status' => 'aktif',
            'no_telphone' => $request->no_telphone,
        ]);

        return response()->json([
            'pesan' => 'Registrasi berhasil',
            'data' => $user,
        ], 201);
    }
}
