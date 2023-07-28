<?php

namespace App\Http\Controllers\LoginSystem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Coba otentikasi sebagai pengguna
        if (Auth::attempt($credentials)) {
            // Otentikasi pengguna berhasil
            $user = Auth::user();

            // Periksa status verifikasi email
            if ($user->email_verified_at) {
                if ($user->role === 'admin') {
                    $token = $user->createToken('AdminToken')->accessToken;
                    return response()->json(['token' => $token, 'role' => 'admin'], 200);
                } else {
                    $token = $user->createToken('UserToken')->accessToken;
                    return response()->json(['token' => $token, 'role' => 'user'], 200);
                }
            } else {
                Auth::logout();
                return response()->json(['message' => 'Email not verified, Please Verify Your Email'], 401);
            }
        }

        // Otentikasi gagal
        return response()->json(['message' => 'Email or password is incorrect.'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );
    }
}
