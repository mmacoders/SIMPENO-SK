<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ActivityLogger;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            ActivityLogger::log('Login', 'User berhasil login');
            return redirect()->intended('/')->with('success', 'Berhasil login!');
        }
        return back()->with('error', 'Email atau password salah!');
    }
    public function logout(Request $request)
    {
        ActivityLogger::log('Logout', 'User logout');
        Auth::logout(); // keluar dari session user
        $request->session()->invalidate(); // hapus session lama
        $request->session()->regenerateToken(); // buat token baru (CSRF)

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
    
}