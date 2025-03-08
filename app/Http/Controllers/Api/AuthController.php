<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PHPMailerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'name.regex' => 'Nama hanya boleh mengandung huruf, spasi, petik tunggal, dan titik.',

            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email tidak valid.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password tidak valid.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password harus sama.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\'.]+$/'],
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'password_confirmation' => ['required', 'same:password'],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil, menunggu verifikasi admin',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if ($user->role !== 'user') {
            return response()->json(['message' => 'Anda tidak memiliki akses'], 403);
        }

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Akun belum diverifikasi oleh admin'], 403);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->email_verified_at === null || $user->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid atau belum diverifikasi'
            ], 422);
        }

        // 🔹 Buat OTP (angka acak 4 digit)
        $otp = rand(1000, 9999);

        // 🔹 Simpan OTP ke database dengan masa berlaku 5 menit
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        $emailService = new PHPMailerService();
        $to = $user->email;
        $subject = "Kode OTP Lupa Password";
        $body = "<h3>Halo, {$user->name}!</h3>
                 <p>Kode OTP Anda adalah: <strong>{$otp}</strong></p>
                 <p>OTP ini hanya berlaku selama 5 menit.</p>";

        $result = $emailService->sendEmail($to, $subject, $body);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'OTP telah dikirim ke email Anda'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP. Coba lagi nanti.'
            ], 500);
        }
    }

    public function verifOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:4'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan',
            'otp.required' => 'OTP tidak boleh kosong',
            'otp.digits' => 'OTP harus 4 digit'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau tidak valid'
            ], 422);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kadaluarsa'
            ], 422);
        }

        // Hapus OTP setelah digunakan
        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil diverifikasi'
        ]);
    }

    public function resetPassword(Request $request)
    {
        // Validasi input dengan pesan error dalam Bahasa Indonesia
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
            'password.required' => 'Password baru wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.regex' => 'Password harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 simbol.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password baru.',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Email tidak ditemukan.'
            ], 404);
        }

        // Update password dengan hash
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login dengan password baru Anda.'
        ]);
    }
}
