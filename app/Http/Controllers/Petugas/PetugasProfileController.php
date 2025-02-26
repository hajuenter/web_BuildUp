<?php

namespace App\Http\Controllers\Petugas;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetugasProfileController extends Controller
{
    public function showPetugasProfile()
    {
        $user = Auth::user();
        return view('screen_petugas.profile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('up/profile/' . $user->foto))) {
                unlink(public_path('up/profile/' . $user->foto));
            }

            // Ambil file dari request
            $file = $request->file('foto');

            // Buat nama unik untuk file
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Pindahkan file ke folder public/up/profile
            $file->move(public_path('up/profile'), $filename);

            // Simpan nama file ke database
            $user->foto = $filename;
        }

        // Update data user
        $user->name = $request->nama;
        $user->no_hp = $request->no_hp;
        $user->email = $request->email;
        $user->save(); // Pastikan ini bekerja dengan benar

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function changePassword(Request $request)
    {
        // Validasi input dengan aturan khusus
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',                 // Minimal 8 karakter
                'regex:/[A-Z]/',         // Harus ada huruf besar
                'regex:/[a-z]/',         // Harus ada huruf kecil
                'regex:/[0-9]/',         // Harus ada angka
                'regex:/[\W]/',          // Harus ada karakter khusus
            ],
            'renew_password' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru harus minimal 8 karakter.',
            'new_password.regex' => 'Password baru harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'renew_password.required' => 'Konfirmasi password baru wajib diisi.',
            'renew_password.same' => 'Konfirmasi password baru harus sama dengan password baru.',
        ]);

        // Ambil user yang sedang login dan pastikan instance dari User
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        // Cek apakah password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        // Update password baru dengan memastikan instance dari User
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui.');
    }
}
