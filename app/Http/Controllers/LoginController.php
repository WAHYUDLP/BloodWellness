<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan model User sudah ada
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login', [
            'accountDeleted' => session('accountDeleted', false),
            'success' => session('success'),
            'error' => session('error'),
            'email' => old('email'),
        ]);
    }



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/beranda'); // Redirect sesuai rute beranda kamu
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda berhasil keluar.');
    }
}
