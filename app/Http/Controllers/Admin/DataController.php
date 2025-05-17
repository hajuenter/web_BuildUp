<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Token;
use App\Models\ApiKey;
use App\Models\DataCPB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DataVerifikasiCPB;
use App\Services\PHPMailerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
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

    public function showEditDataCPB($id)
    {
        $cpb = DataCPB::findOrFail($id);
        return view('screen_admin.data.edit_data_cpb', compact('cpb'));
    }

    public function updateDataCPB(Request $request, $id)
    {
        // Ambil data CPB berdasarkan ID
        $cpb = DataCPB::findOrFail($id);

        // Validasi Input
        $validator = Validator::make($request->all(), [
            'alamat_jalan'     => 'required|string',
            'alamat_desa'      => 'required|string',
            'alamat_kecamatan' => 'required|string',
            'nama'       => 'required|string|regex:/^[A-Za-z\s\.\'\-]+$/u|max:255',
            'nik'        => 'required|digits:16|unique:data_cpb,nik,' . $id,
            'no_kk'      => 'required|digits:16|unique:data_cpb,no_kk,' . $id,
            'pekerjaan'  => 'required|string|max:255',
            'email'      => 'required|email|unique:data_cpb,email,' . $id,
            'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'koordinat'  => [
                'required',
                'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'
            ],
        ], [
            'alamat_jalan.required' => 'Alamat wajib diisi',
            'alamat_desa.required' => 'Alamat wajib diisi',
            'alamat_kecamatan.required' => 'Alamat wajib diisi',
            'nama.required'       => 'Nama wajib diisi.',
            'nama.regex'          => 'Nama tidak valid.',
            'nama.max'            => 'Nama tidak boleh lebih dari 255 karakter.',
            'nik.required'        => 'NIK wajib diisi.',
            'nik.digits'          => 'NIK harus 16 digit angka.',
            'nik.unique'          => 'NIK sudah digunakan.',
            'no_kk.required'      => 'No KK wajib diisi.',
            'no_kk.digits'        => 'No KK harus 16 digit angka.',
            'no_kk.unique'        => 'No KK sudah digunakan.',
            'pekerjaan.required'  => 'Pekerjaan wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah digunakan.',
            'foto_rumah.image'    => 'File harus berupa gambar.',
            'foto_rumah.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_rumah.max'      => 'Ukuran gambar maksimal 2MB.',
            'koordinat.required'  => 'Koordinat wajib diisi.',
            'koordinat.regex'     => 'Format koordinat tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cek apakah ada file gambar baru diunggah
        if ($request->hasFile('foto_rumah')) {
            $file = $request->file('foto_rumah');

            // Pastikan folder tujuan ada
            $folderPath = public_path('up/data_cpb');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Hapus gambar lama jika ada
            if ($cpb->foto_rumah && file_exists(public_path($cpb->foto_rumah))) {
                unlink(public_path($cpb->foto_rumah));
            }

            // Simpan gambar baru dengan nama unik
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($folderPath, $fileName);
            $fotoPath = 'up/data_cpb/' . $fileName;
        } else {
            $fotoPath = $cpb->foto_rumah; // Gunakan gambar lama jika tidak ada perubahan
        }

        $nikLama = $cpb->nik;
        $alamatGabung = trim($request->alamat_jalan) . '; ' . trim($request->alamat_desa) . '; ' . trim($request->alamat_kecamatan);
        // Update Data di Database
        $cpb->update([
            'nama'       => strtoupper($request->nama),
            'alamat'     => $alamatGabung,
            'nik'        => $request->nik,
            'no_kk'      => $request->no_kk,
            'pekerjaan'  => $request->pekerjaan,
            'email'      => $request->email,
            'foto_rumah' => $fotoPath,
            'koordinat'  => $request->koordinat,
        ]);

        if ($nikLama !== $request->nik) {

            $oldFolder = public_path("up/verifikasi/{$nikLama}");
            $newFolder = public_path("up/verifikasi/{$request->nik}");

            if (File::exists($oldFolder)) {
                File::move($oldFolder, $newFolder);
            }

            DataVerifikasiCPB::where('nik', $nikLama)
                ->update(['nik' => $request->nik]);
        }

        return redirect()->route('admin.data_cpb')->with('successEditCPB', 'Data CPB berhasil diperbarui.');
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
        $perPagePetugas = $request->input('perPage_petugas', 5);
        $perPageUser = $request->input('perPage_user', 5);

        // Ambil data sesuai role
        $dataPetugas = $perPagePetugas === 'all'
            ? User::where('role', 'petugas')->get()
            : User::where('role', 'petugas')->paginate($perPagePetugas, ['*'], 'petugas_page');

        $dataUser = $perPageUser === 'all'
            ? User::where('role', 'user')->get()
            : User::where('role', 'user')->paginate($perPageUser, ['*'], 'user_page');

        $token = Token::first();
        $originalToken = null;

        if ($token && $token->token_login) {
            try {
                $originalToken = Crypt::decryptString($token->token_login);
            } catch (\Exception $e) {
                $originalToken = 'Token tidak valid atau gagal didekripsi';
            }
        }

        return view('screen_admin.data.data_role', compact(
            'dataPetugas',
            'dataUser',
            'perPagePetugas',
            'perPageUser',
            'originalToken'
        ));
    }

    public function updateToken(Request $request)
    {
        $request->validate([
            'token_login' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]+$/',
                'max:255',
            ],
        ], [
            'token_login.required' => 'Token tidak boleh kosong!',
            'token_login.min' => 'Token minimal harus 8 karakter!',
            'token_login.regex' => 'Token harus mengandung huruf besar, huruf kecil, angka, simbol, dan tanpa spasi!',
            'token_login.max' => 'Token tidak boleh lebih dari 255 karakter!',
        ]);

        $token = Token::first();

        $hashedToken = Crypt::encryptString($request->token_login);
        $userId = Auth::id();
        if ($token) {
            $token->update(['token_login' => $hashedToken]);
        } else {
            Token::create(['user_id' => $userId, 'token_login' => $hashedToken]);
        }

        return redirect()->route('admin.data_role')->with('success', 'Token berhasil disimpan!');
    }

    public function verifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();
        ApiKey::create([
            'user_id' => $user->id,
            'api_key' => hash('sha256', Str::random(64)),
        ]);
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
        ApiKey::where('user_id', $user->id)->delete();
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

        if ($request->role === 'petugas') {
            $validator->after(function ($validator) use ($request) {
                if (empty($request->alamat_desa)) {
                    $validator->errors()->add('alamat_desa', 'Desa/Kelurahan wajib diisi untuk petugas input.');
                }
                if (empty($request->kecamatan)) {
                    $validator->errors()->add('kecamatan', 'Kecamatan wajib diisi untuk petugas input.');
                }

                $inputDesa = strtolower(trim($request->alamat_desa));
                if (!empty($inputDesa)) {
                    $semuaAlamat = User::pluck('alamat');
                    foreach ($semuaAlamat as $alamat) {
                        $bagian = explode(';', $alamat);
                        if (count($bagian) >= 2) {
                            $desa = strtolower(trim($bagian[1]));
                            if ($desa === $inputDesa) {
                                $validator->errors()->add('alamat_desa', 'Desa/Kelurahan sudah terdaftar oleh pengguna lain!');
                                break;
                            }
                        }
                    }
                }
            });
        }

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat . '; ' . $request->alamat_desa . '; ' . $request->kecamatan,
            'foto' => $foto,
        ]);

        ApiKey::create([
            'user_id' => $user->id,
            'api_key' => hash('sha256', Str::random(64)),
        ]);

        return redirect()->route('admin.data_role')->with('successY', 'Pengguna berhasil ditambahkan.');
    }
}
