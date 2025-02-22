<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'name.regex' => 'Nama hanya boleh mengandung huruf, spasi, tanda petik tunggal, dan titik.',

            'email.required' => 'Email tidak boleh kosong.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            'password.required' => 'Password tidak boleh kosong.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal harus 8 karakter.',

            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\'.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
            'email_verified_at' => null, // Menunggu verifikasi admin
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
            return response()->json(['message' => 'Anda tidak memiliki akses sebagai user'], 403);
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

    // public function register(Request $request)
    // {
    //     // Validasi input
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email', // Pastikan email unik
    //         'password' => 'required|string|min:6', // Password minimal 6 karakter
    //         'password_confirmation' => 'required|same:password', // Memastikan konfirmasi password sama
    //     ]);

    //     // Menangani jika validasi gagal
    //     if ($validator->fails()) {
    //         // Mengirim pesan error yang dikustomisasi
    //         return response()->json([
    //             'errors' => $this->formatValidationErrors($validator->errors())
    //         ], 422);
    //     }

    //     // Jika password dan password_confirmation tidak cocok
    //     if ($request->password !== $request->password_confirmation) {
    //         return response()->json([
    //             'errors' => ['password_confirmation' => ['Konfirmasi password tidak cocok.']]
    //         ], 422);
    //     }

    //     // Membuat user baru
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'user', // Default role
    //         'email_verified_at' => null, // Menunggu verifikasi admin
    //     ]);

    //     // Menyusun response sukses
    //     return response()->json([
    //         'message' => 'Registrasi berhasil, menunggu verifikasi admin',
    //         'user' => $user
    //     ], 201);
    // }

    // // Fungsi untuk mengformat error agar lebih mudah dikustomisasi
    // private function formatValidationErrors($errors)
    // {
    //     $formattedErrors = [];

    //     foreach ($errors->messages() as $field => $messages) {
    //         foreach ($messages as $message) {
    //             // Pesan error dalam Bahasa Indonesia
    //             switch ($message) {
    //                 case 'The email has already been taken.':
    //                     $formattedErrors[$field][] = 'Email sudah terdaftar.';
    //                     break;
    //                 case 'The password must be at least 6 characters.':
    //                     $formattedErrors[$field][] = 'Password minimal 6 karakter.';
    //                     break;
    //                 case 'The password confirmation does not match.':
    //                     $formattedErrors[$field][] = 'Konfirmasi password tidak cocok.';
    //                     break;
    //                 default:
    //                     $formattedErrors[$field][] = $message;
    //             }
    //         }
    //     }

    //     return $formattedErrors;
    // }
}
