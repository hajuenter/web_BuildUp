<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ApiProfileController extends Controller
{

    public function getProfile(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        // Cari API Key di database
        $apiKeyData = \App\Models\ApiKey::where('api_key', $apiKey)->first();

        if (!$apiKeyData || !$apiKeyData->user) {
            return response()->json(['error' => 'User tidak ditemukan atau API Key tidak valid'], 401);
        }

        $user = $apiKeyData->user;

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'alamat' => $user->alamat,
                'foto' => $user->foto ? url("up/profile/{$user->foto}") : null,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $apiKey = $request->header('X-API-KEY');

            // Cari API Key di database
            $apiKeyData = \App\Models\ApiKey::where('api_key', $apiKey)->first();

            if (!$apiKeyData || !$apiKeyData->user) {
                return response()->json([
                    'success' => false,
                    'message' => 'API Key tidak ditemukan atau tidak valid.'
                ], 401);
            }

            $user = $apiKeyData->user;

            // Validasi input dengan pesan error yang lebih informatif
            $validatedData = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email|unique:users,email,' . $user->id,
                'no_hp'  => 'nullable|string|max:20|unique:users,no_hp,' . $user->id,
                'alamat' => 'nullable|string|max:255',
                'foto'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'name.required'   => 'Nama wajib diisi.',
                'name.max'        => 'Nama maksimal 255 karakter.',
                'email.required'  => 'Email wajib diisi.',
                'email.email'     => 'Format email tidak valid.',
                'email.unique'    => 'Email sudah digunakan, silakan gunakan email lain.',
                'no_hp.max'       => 'Nomor HP maksimal 20 karakter.',
                'no_hp.unique'    => 'Nomor HP sudah digunakan, silakan gunakan nomor lain.',
                'alamat.max'      => 'Alamat maksimal 255 karakter.',
                'foto.image'      => 'File harus berupa gambar.',
                'foto.mimes'      => 'Format gambar harus jpeg, png, atau jpg.',
                'foto.max'        => 'Ukuran gambar maksimal 2MB.'
            ]);

            // Update data user
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->no_hp = $validatedData['no_hp'] ?? null;
            $user->alamat = $validatedData['alamat'] ?? null;

            // Handle foto upload jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($user->foto && file_exists(public_path('up/profile/' . $user->foto))) {
                    unlink(public_path('up/profile/' . $user->foto));
                }

                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('up/profile'), $filename);

                $user->foto = $filename;
            }

            if (!$user->save()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui profil. Silakan coba lagi.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'user'    => $user
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
