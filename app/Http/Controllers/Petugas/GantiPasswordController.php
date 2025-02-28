<?php

namespace App\Http\Controllers\Petugas;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GantiPasswordController extends Controller
{
    public function showChangePassword()
    {
        return view('screen_petugas.ganti_password.ganti_password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        // Validasi input
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[\W]/',
            ],
            'renew_password' => 'required|same:new_password',
        ], [
            'new_password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
        ]);

        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
