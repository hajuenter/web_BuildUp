<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'min:5', 'max:230'],
            'no_hp' => ['required', 'numeric', 'digits_between:10,15', 'unique:users,no_hp'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ], [
            'name.required' => 'Nama wajib diisi!',
            'name.string' => 'Nama harus berupa huruf!',
            'name.regex' => 'Nama tidak boleh mengandung angka atau simbol!',
            'nama.min' => 'Nama minimal 5 karakter',
            'nama.max' => 'Nama maksimal 230 karakter',

            'no_hp.required' => 'Nomor HP wajib diisi!',
            'no_hp.numeric' => 'Nomor HP harus berupa angka!',
            'no_hp.digits_between' => 'Nomor HP harus memiliki panjang antara 10-15 digit!',
            'no_hp.unique' => 'Nnomor HP sudah terdaftar, gunakan yang lain!',

            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain!',

            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal harus 8 karakter!',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol!',

            'password.confirmed' => 'Konfirmasi password tidak cocok!',
        ]);

        $user = User::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas', // Default role
            'email_verified_at' => null,
        ]);

        return redirect()->back()->with('success', 'Akun berhasil dibuat! Silakan tunggu konfirmasi dari Admin.');
    }
}
