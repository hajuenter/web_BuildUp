<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('screen_admin.profile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.string' => 'Nomor HP harus berupa teks.',
            'no_hp.max' => 'Nomor HP tidak boleh lebih dari 15 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email ini sudah digunakan, silakan gunakan email lain.',

            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'profile-edit');
        }

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

        return redirect()->back()->with([
            'success' => 'Profil berhasil diperbarui',
            'active_tab' => 'profile-edit'
        ]);
    }
}
