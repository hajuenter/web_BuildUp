<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'alamat_jalan' => ['required', 'string', 'max:255'],
            'desa_kelurahan' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/', 'min:3', 'max:230'],
            'no_hp' => ['required', 'numeric', 'digits_between:10,15', 'unique:users,no_hp'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'token' => ['required'],
        ], [
            'alamat_jalan.required' => 'Alamat jalan wajib diisi!',
            'desa_kelurahan.required' => 'Desa / Kelurahan wajib diisi!',
            'kecamatan.required' => 'Kecamatan wajib diisi!',

            'name.required' => 'Nama wajib diisi!',
            'name.string' => 'Nama harus berupa huruf!',
            'name.regex' => 'Nama tidak boleh mengandung angka atau simbol!',
            'name.min' => 'Nama minimal 5 karakter',
            'name.max' => 'Nama maksimal 230 karakter',

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
            'token.required' => 'Token wajib diisi!',
        ]);
        $tokenUserInput = $request->token;
        $tokenValid = Token::all()->contains(function ($token) use ($tokenUserInput) {
            try {
                return Crypt::decryptString($token->token_login) === $tokenUserInput;
            } catch (\Exception $e) {
                return false;
            }
        });

        if (!$tokenValid) {
            return redirect()->back()->withErrors([
                'token' => 'Token tidak valid!',
            ])->withInput();
        }

        $desa_kelurahan = trim($request->desa_kelurahan);
        $alamatSama = User::pluck('alamat');
        foreach ($alamatSama as $alamat) {
            $bagian = explode(';', $alamat);
            if (count($bagian) >= 2 && strtolower(trim($bagian[1])) === strtolower($desa_kelurahan)) {
                return redirect()->back()->withErrors([
                    'desa_kelurahan' => 'Desa / Kelurahan sudah terdaftar!',
                ])->withInput();
            }
        }
        $alamatLengkap = $request->alamat_jalan . '; ' . $request->desa_kelurahan . '; ' . $request->kecamatan;
        $user = User::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $alamatLengkap,
            'role' => 'petugas', // Default role
            'email_verified_at' => null,
        ]);

        return redirect()->back()->with('success', 'Akun berhasil dibuat! Silakan tunggu konfirmasi dari Admin.');
    }
}
