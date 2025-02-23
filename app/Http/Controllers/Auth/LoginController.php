<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'password.required' => 'Password wajib diisi!'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar!'])->withInput();
        }

        $throttleKey = 'login_attempts_' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors(['email' => 'Terlalu banyak percobaan login. Coba lagi nanti setelah 5 menit.'])->withInput();
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($throttleKey, 300); // Tambah percobaan login, reset setelah 5 menit
            return back()->withErrors(['password' => 'Password salah!'])->withInput();
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akses ditolak! Hanya admin yang dapat login.'])->withInput();
        }

        $request->session()->regenerate();

        DB::table('sessions')
        ->where('id', session()->getId()) // Ambil session yang baru dibuat
        ->update(['user_id' => $user->id]); // Tambahkan user_id ke dalam session database

        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'login_time' => now()
        ]);

        RateLimiter::clear($throttleKey);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!');
    }
}
