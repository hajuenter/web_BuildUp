<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PHPMailerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LupaPasswordController extends Controller
{
    public function showLupaPasswordForm()
    {
        return view('auth.lupa_pasword');
    }

    public function sendOTP(Request $request)
    {
        // Validasi input email
        $request->validate([
            'email' => 'required|email'
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user || !in_array($user->role, ['admin', 'petugas'])) {
            return back()->withErrors(['email' => 'Email tidak ditemukan atau Anda tidak memiliki akses.'])->withInput();
        }

        $otp = rand(1000, 9999);

        // Simpan OTP ke database dengan batas waktu 5 menit
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        session(['reset_email' => $request->email]);

        // Kirim email OTP
        $emailService = new PHPMailerService();
        $to = $user->email;
        $subject = "Kode OTP Lupa Password";
        $body = "<h3>Halo, {$user->name}!</h3>
                 <p>Kode OTP Anda adalah: <strong>{$otp}</strong></p>
                 <p>OTP ini hanya berlaku selama 5 menit.</p>";

        $result = $emailService->sendEmail($to, $subject, $body);

        if ($result) {
            return redirect()->route('konfir.password')->with('successotp', 'Kode OTP telah dikirim ke email Anda.');
        } else {
            return back()->withErrors(['email' => 'Gagal mengirim OTP. Coba lagi nanti.']);
        }
    }

    public function showKonfirPasswordForm()
    {
        // Cek apakah ada session reset_email
        if (!session()->has('reset_email')) {
            return redirect()->route('lupa.password')->withErrors(['email' => 'Silakan masukkan email terlebih dahulu.']);
        }

        return view('auth.konfir_password');
    }

    public function updatePassword(Request $request)
    {
        // Pastikan ada session email
        if (!session()->has('reset_email')) {
            return redirect()->route('lupa.password')->withErrors(['email' => 'Silakan masukkan email terlebih dahulu.']);
        }

        // Validasi input
        $request->validate([
            'otp' => 'required|digits:4',
            'newpassword' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\W]{8,}$/'
            ],
            'newkonfirpassword' => 'required|same:newpassword',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus 4 digit.',
            'newpassword.required' => 'Password baru wajib diisi.',
            'newpassword.min' => 'Password baru minimal 8 karakter.',
            'newpassword.regex' => 'Password harus mengandung huruf besar, angka, dan karakter khusus.',
            'newkonfirpassword.required' => 'Konfirmasi password wajib diisi.',
            'newkonfirpassword.same' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        // Ambil email dari session
        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('lupa.password')->withErrors(['email' => 'Terjadi kesalahan, silakan coba lagi.']);
        }

        // Cek apakah OTP valid
        if ($user->otp != $request->otp || Carbon::parse($user->otp_expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        // Update password dan hapus OTP
        $user->update([
            'password' => Hash::make($request->newpassword),
            'otp' => null,
            'otp_expires_at' => null
        ]);

        // Hapus session email
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui, silakan login.');
    }
}
