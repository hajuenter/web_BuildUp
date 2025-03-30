<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ApiKey;
use App\Models\DataCPB;
use Illuminate\Http\Request;
use App\Models\DataVerifikasiCPB;
use App\Services\PHPMailerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function showDataCPB(Request $request)
    {
        $nik = $request->input('nik');
        $no_kk = $request->input('no_kk');
        $nama = $request->input('nama');

        // Query dasar
        $query = DataCPB::query();

        // Filter jika ada input NIK
        if (!empty($nik)) {
            $query->where('nik', 'LIKE', "%$nik%");
        }

        // Filter jika ada input No KK
        if (!empty($no_kk)) {
            $query->where('no_kk', 'LIKE', "%$no_kk%");
        }

        // Filter jika ada input Nama
        if (!empty($nama)) {
            $query->where('nama', 'LIKE', "%$nama%");
        }

        // Ambil hasil pencarian
        $dataCPB = $query->get();
        $totalSudahDicek = DataCPB::where('pengecekan', 'Sudah Dicek')->count();
        $totalBelumDicek = DataCPB::where('pengecekan', 'Belum Dicek')->count();

        $totalTerverifikasi = DataCPB::where('status', 'Terverifikasi')->count();
        $totalTidakTerverifikasi = DataCPB::where('status', 'Tidak Terverifikasi')->count();
        $perPage = $request->input('perPage', 5); // Default 5 jika tidak ada input

        // Jika pilih "Semua", ambil semua data tanpa pagination
        if ($perPage == "all") {
            $dataCPB = $query->get(); // Gunakan query agar filter tetap berfungsi
        } else {
            $dataCPB = $query->paginate($perPage);
            $dataCPB->appends(request()->query());
        }
        return view('screen_admin.data.data_cpb', compact(
            'dataCPB',
            'perPage',
            'totalSudahDicek',
            'totalBelumDicek',
            'totalTerverifikasi',
            'totalTidakTerverifikasi'
        ));
    }

    public function deleteDataCPB($id)
    {
        $data = DataCPB::find($id);

        if (!$data) {
            return redirect()->route('admin.data_cpb')->with('error', 'Data tidak ditemukan!');
        }

        $verifikasi = DataVerifikasiCPB::where('nik', $data->nik)->get();

        // Path folder tempat menyimpan gambar berdasarkan NIK
        $folderPath = public_path("up/verifikasi/{$data->nik}");

        // Hapus folder jika ada
        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
        }
        DataVerifikasiCPB::where('nik', $data->nik)->delete();

        // Hapus data
        $data->delete();

        return redirect()->route('admin.data_cpb')->with('success', 'Data berhasil dihapus!');
    }

    public function showDataVerifCPB(Request $request)
    {
        $nik = $request->input('nik');
        $query = DataVerifikasiCPB::query();

        // Filter jika ada input NIK
        if (!empty($nik)) {
            $query->where('nik', 'LIKE', "%$nik%");
        }

        $dataVerifCPB = $query->get();
        $totalRusakBerat = DataVerifikasiCPB::where('catatan', 'Rusak Berat')->count();
        $totalRusakSedang = DataVerifikasiCPB::where('catatan', 'Rusak Sedang')->count();
        $totalRusakRingan = DataVerifikasiCPB::where('catatan', 'Rusak Ringan')->count();
        $totalTidakDapatBantuan = DataVerifikasiCPB::where('catatan', 'Tidak mendapat bantuan')->count();
        $perPage = $request->input('perPage', 5); // Default 5 jika tidak ada input

        // Jika pilih "Semua", ambil semua data tanpa pagination
        if ($perPage == "all") {
            $dataVerifCPB = $query->get();
        } else {
            $dataVerifCPB = $query->paginate($perPage);
            $dataVerifCPB->appends(request()->query());
        }

        return view('screen_admin.data.data_verif_cpb', compact('dataVerifCPB', 'perPage', 'totalRusakBerat', 'totalRusakSedang', 'totalRusakRingan', 'totalTidakDapatBantuan'));
    }

    public function deleteDataVerifCPB(Request $request)
    {
        $id = $request->input('id');
        $nik = $request->input('nik');

        // Cari data verifikasi berdasarkan ID
        $dataVerif = DataVerifikasiCPB::find($id);

        if (!$dataVerif) {
            return redirect()->route('admin.data_verif_cpb')->with('error', 'Data tidak ditemukan!');
        }

        // Path folder tempat menyimpan gambar berdasarkan NIK
        $folderPath = public_path("up/verifikasi/{$nik}");

        // Hapus folder jika ada
        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
        }

        // Hapus data verifikasi
        $dataVerif->delete();

        // Update status dan pengecekan di tabel data_cpb
        DataCPB::where('nik', $nik)->update([
            'status' => 'Tidak Terverifikasi',
            'pengecekan' => 'Belum Dicek'
        ]);

        return redirect()->route('admin.data_verif_cpb')->with('success', 'Data, gambar, dan status terkait berhasil diperbarui!');
    }

    public function showDataRole(Request $request)
    {
        $perPage = $request->input('perPage', 5);

        if ($perPage == "all") {
            $dataRole = User::whereIn('role', ['petugas', 'user'])->get();
        } else {
            $dataRole = User::whereIn('role', ['petugas', 'user'])->paginate($perPage);
        }

        return view('screen_admin.data.data_role', compact('dataRole', 'perPage'));
    }

    public function verifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        $mailService = new PHPMailerService();
        $subject = "Verifikasi Akun Berhasil";
        $body = "
        <h2>Halo, {$user->name}</h2>
        <p>Selamat! Akun Anda dengan email {$user->email} telah berhasil diverifikasi.</p>
        <p>Silakan login dan mulai menggunakan layanan kami.</p>
        <br>
        <p>Terima kasih,</p>
        <p><strong>Tim Support</strong></p>
    ";

        $mailService->sendEmail($user->email, $subject, $body);

        return back()->with('successY', 'User berhasil diaktifkan!');
    }

    public function unverifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = null;
        $user->save();

        $mailService = new PHPMailerService();
        $subject = "Akun Anda Telah Dinonaktifkan";
        $body = "
        <h2>Halo, {$user->name}</h2>
        <p>Kami ingin memberitahukan bahwa akun Anda dengan email {$user->email} telah dinonaktifkan.</p>
        <p>Jika ini terjadi karena kesalahan atau Anda memerlukan bantuan, silakan hubungi tim support kami.</p>
        <br>
        <p>Terima kasih,</p>
        <p><strong>Tim Support</strong></p>
    ";

        $mailService->sendEmail($user->email, $subject, $body);

        return back()->with('successG', 'User berhasil dinonaktifkan!');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        ApiKey::where('user_id', $user->id)->delete();

        if ($user->foto && file_exists(public_path('up/profile/' . $user->foto))) {
            unlink(public_path('up/profile/' . $user->foto));
        }

        $user->delete();

        return redirect()->back()->with('successG', 'User berhasil dihapus.');
    }

    public function showPetugasAdd()
    {
        return view('screen_admin.data.tambah_pengguna');
    }

    public function createPengguna(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'password' => [
                    'required',
                    'min:8',
                    'regex:/[A-Z]/',   // Harus ada huruf besar
                    'regex:/[a-z]/',   // Harus ada huruf kecil
                    'regex:/[0-9]/',   // Harus ada angka
                    'regex:/[\W]/'     // Harus ada karakter khusus
                ],
                'no_hp' => 'required|string|max:15|unique:users,no_hp',
                'email' => 'required|email|max:255|unique:users,email',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'role' => 'required',
                'alamat' => 'required|string|max:255',
            ],
            [
                'alamat.required' => 'Alamat wsjib di isi',
                'alamat.string' => 'Alamat harus text',
                'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter',
                'role.required' => 'Role tidak boleh kosong',
                'name.required' => 'Nama tidak boleh kosong.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',

                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.string' => 'Nomor HP harus berupa teks.',
                'no_hp.max' => 'Nomor HP tidak boleh lebih dari 15 karakter.',
                'no_hp.unique' => 'Nomor HP sudah terdaftar, gunakan nomor lain.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
                'email.unique' => 'Email sudah terdaftar, gunakan email lain.',

                'foto.image' => 'File yang diunggah harus berupa gambar.',
                'foto.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
                'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // dd($validator->errors());
        $foto = 'profile.png';
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); // Nama unik
            $file->move(public_path('up/profile'), $filename); // Simpan ke public/up/profile/
            $foto = $filename; // Simpan nama file ke database
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => null,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto' => $foto,
        ]);

        return redirect()->route('admin.data_role')->with('successY', 'Pengguna berhasil ditambahkan.');
    }
}
