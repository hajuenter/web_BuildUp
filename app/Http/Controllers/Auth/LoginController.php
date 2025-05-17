<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
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

        if (is_null($user->email_verified_at)) {
            return back()->withErrors(['email' => 'Akun Anda belum diverifikasi oleh admin.'])->withInput();
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

        if ($user->role === 'admin') {
            $redirectRoute = 'admin.dashboard';
        } elseif ($user->role === 'petugas') {
            $alamatParts = explode(';', $user->alamat);
            $desa = isset($alamatParts[1]) ? trim($alamatParts[1]) : null;
            $kecamatan = isset($alamatParts[2]) ? trim($alamatParts[2]) : null;
            session([
                'desa_petugas' => $desa,
                'kecamatan_petugas' => $kecamatan,
            ]);
            $redirectRoute = 'petugas.inputcpb';
        } else {
            Auth::logout();
            return back()->withErrors(['email' => 'Akses ditolak! Hanya admin & petugas yang dapat login di web.'])->withInput();
        }

        $request->session()->regenerate();

        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'login_time' => now()
        ]);

        RateLimiter::clear($throttleKey);

        return redirect()->route($redirectRoute)->with('yaya', 'Selamat datang, ' . ucfirst($user->role) . '!');
    }
}
