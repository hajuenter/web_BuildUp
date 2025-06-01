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

    public function showEditDataVerifCPB($id)
    {
        $verifCPB = DataVerifikasiCPB::findOrFail($id);
        return view('screen_admin.data.edit_verif_cpb', compact('verifCPB'));
    }

    public function updateDataVerifCPB(Request $request, string $id)
    {
        $verifikasi = DataVerifikasiCPB::findOrFail($id);

        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                // Validasi untuk dropdown decimal (0.25, 0.5, 0.75, 1.0)
                'penutup_atap' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'rangka_atap' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'kolom' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'ring_balok' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'dinding_pengisi' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'kusen' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'pintu' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'jendela' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'struktur_bawah' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'penutup_lantai' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',
                'mck' => 'required|numeric|in:0.0,0.25,0.5,0.75,1.0',

                // Validasi untuk dropdown 0.0 dan 1.0
                'sloof' => 'required|numeric|in:0.0,1.0',
                'air_kotor' => 'required|numeric|in:0.0,1.0',

                // Validasi untuk kesanggupan berswadaya
                'kesanggupan_berswadaya' => 'required|in:0,1',

                // Validasi untuk tipe
                'tipe' => 'required|in:T,K',
                'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                // Validasi untuk foto-foto (optional)
                'foto_penutup_atap' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_rangka_atap' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_kolom' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_ring_balok' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_dinding_pengisi' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_kusen' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_pintu' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_jendela' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_struktur_bawah' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_penutup_lantai' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_sloof' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_air_kotor' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
                'foto_mck' => 'nullable|image|mimes:jpg,jpeg,png|max:15360',
            ],
            [
                // Custom error messages
                'penutup_atap.required' => 'Penilaian penutup atap harus diisi.',
                'penutup_atap.in' => 'Penilaian penutup atap harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'rangka_atap.required' => 'Penilaian rangka atap harus diisi.',
                'rangka_atap.in' => 'Penilaian rangka atap harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'kolom.required' => 'Penilaian kolom harus diisi.',
                'kolom.in' => 'Penilaian kolom harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'ring_balok.required' => 'Penilaian ring balok harus diisi.',
                'ring_balok.in' => 'Penilaian ring balok harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'dinding_pengisi.required' => 'Penilaian dinding pengisi harus diisi.',
                'dinding_pengisi.in' => 'Penilaian dinding pengisi harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'kusen.required' => 'Penilaian kusen harus diisi.',
                'kusen.in' => 'Penilaian kusen harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'pintu.required' => 'Penilaian pintu harus diisi.',
                'pintu.in' => 'Penilaian pintu harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'jendela.required' => 'Penilaian jendela harus diisi.',
                'jendela.in' => 'Penilaian jendela harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'struktur_bawah.required' => 'Penilaian struktur bawah harus diisi.',
                'struktur_bawah.in' => 'Penilaian struktur bawah harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'penutup_lantai.required' => 'Penilaian penutup lantai harus diisi.',
                'penutup_lantai.in' => 'Penilaian penutup lantai harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'mck.required' => 'Penilaian sanitasi harus diisi.',
                'mck.in' => 'Penilaian sanitasi harus bernilai 0.25, 0.5, 0.75, atau 1.0.',
                'sloof.required' => 'Penilaian sloof harus diisi.',
                'sloof.in' => 'Penilaian sloof harus bernilai 0.0 atau 1.0.',
                'air_kotor.required' => 'Penilaian air bersih harus diisi.',
                'air_kotor.in' => 'Penilaian air bersih harus bernilai 0.0 atau 1.0.',
                'kesanggupan_berswadaya.required' => 'Kesanggupan berswadaya harus diisi.',
                'kesanggupan_berswadaya.in' => 'Kesanggupan berswadaya harus bernilai Ya atau Tidak.',
                'tipe.required' => 'Tipe rumah wajib diisi.',
                'tipe.in' => 'Tipe rumah harus bernilai "T" (Tembok) atau "K" (Kayu).',
            ]
        );

        // Jika validasi gagal, redirect kembali dengan error
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil semua data dari request
        $data = $request->all();
        $nik = $verifikasi->nik; // Gunakan NIK dari data yang sudah ada

        // Set bobot berdasarkan tipe rumah
        $bobot_tembok = [12.4, 13.65, 5.675, 5.675, 16.1, 2.81, 3.02, 6.3, 3.4, 10.52, 13.1, 3.91, 2.01, 1.43];
        $bobot_kayu = [15.19, 3.39, 5.825, 5.825, 29.58, 4.6, 1.08, 1.09, 0.71, 4.49, 3.93, 19.05, 1.42, 3.82];
        $bobot = $data['tipe'] === 'T' ? $bobot_tembok : $bobot_kayu;

        // Hitung penilaian kerusakan
        $kerusakan_fields = [
            'penutup_atap',
            'rangka_atap',
            'kolom',
            'ring_balok',
            'dinding_pengisi',
            'kusen',
            'pintu',
            'jendela',
            'struktur_bawah',
            'penutup_lantai',
            'pondasi',
            'sloof',
            'mck',
            'air_kotor'
        ];

        $penilaian_kerusakan = 0;
        foreach ($kerusakan_fields as $index => $field) {
            $penilaian_kerusakan += ($data[$field] ?? 0) * $bobot[$index];
        }
        $penilaian_kerusakan *= $data['kesanggupan_berswadaya'];

        // Menentukan nilai bantuan dan catatan
        if ($penilaian_kerusakan > 66) {
            $nilai_bantuan = 20000000;
            $catatan = "Rusak Berat";
        } elseif ($penilaian_kerusakan > 46) {
            $nilai_bantuan = 10000000;
            $catatan = "Rusak Sedang";
        } elseif ($penilaian_kerusakan > 30) {
            $nilai_bantuan = 5000000;
            $catatan = "Rusak Ringan";
        } else {
            $nilai_bantuan = 0;
            $catatan = "Tidak mendapat bantuan";
        }

        // Proses upload foto
        $fotoFields = [
            'foto_penutup_atap',
            'foto_pondasi',
            'foto_rangka_atap',
            'foto_kolom',
            'foto_ring_balok',
            'foto_dinding_pengisi',
            'foto_kusen',
            'foto_pintu',
            'foto_jendela',
            'foto_struktur_bawah',
            'foto_penutup_lantai',
            'foto_sloof',
            'foto_mck',
            'foto_air_kotor',
            'foto_kk',
            'foto_ktp',
        ];

        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama SEBELUM upload file baru
                if (!empty($verifikasi->$field) && file_exists(public_path($verifikasi->$field))) {
                    unlink(public_path($verifikasi->$field));
                }

                // Buat direktori dan upload file baru
                $nikFolder = public_path('up/verifikasi/' . $nik);
                if (!file_exists($nikFolder)) {
                    mkdir($nikFolder, 0755, true);
                }

                // Generate nama file dan upload
                $customName = $field;
                if ($field === 'foto_air_kotor') {
                    $customName = 'foto_air_bersih';
                } elseif ($field === 'foto_mck') {
                    $customName = 'foto_sanitasi';
                }

                $filename = $customName . '_' . time() . '_' . uniqid() . '.' . $request->file($field)->getClientOriginalExtension();
                $request->file($field)->move($nikFolder, $filename);

                $data[$field] = 'up/verifikasi/' . $nik . '/' . $filename;
            } else {
                // Gunakan foto yang sudah ada
                $data[$field] = $verifikasi->$field;
            }
        }

        // Update data verifikasi
        $verifikasi->update([
            'foto_kk' => $data['foto_kk'],
            'foto_ktp' => $data['foto_ktp'],
            'kesanggupan_berswadaya' => $data['kesanggupan_berswadaya'],
            'tipe' => $data['tipe'],
            'penutup_atap' => $data['penutup_atap'],
            'rangka_atap' => $data['rangka_atap'],
            'kolom' => $data['kolom'],
            'pondasi' => $data['pondasi'],
            'ring_balok' => $data['ring_balok'],
            'dinding_pengisi' => $data['dinding_pengisi'],
            'kusen' => $data['kusen'],
            'pintu' => $data['pintu'],
            'jendela' => $data['jendela'],
            'struktur_bawah' => $data['struktur_bawah'],
            'penutup_lantai' => $data['penutup_lantai'],
            'sloof' => $data['sloof'],
            'mck' => $data['mck'],
            'air_kotor' => $data['air_kotor'],
            'foto_penutup_atap' => $data['foto_penutup_atap'],
            'foto_rangka_atap' => $data['foto_rangka_atap'],
            'foto_kolom' => $data['foto_kolom'],
            'foto_pondasi' => $data['foto_pondasi'],
            'foto_ring_balok' => $data['foto_ring_balok'],
            'foto_dinding_pengisi' => $data['foto_dinding_pengisi'],
            'foto_kusen' => $data['foto_kusen'],
            'foto_pintu' => $data['foto_pintu'],
            'foto_jendela' => $data['foto_jendela'],
            'foto_struktur_bawah' => $data['foto_struktur_bawah'],
            'foto_penutup_lantai' => $data['foto_penutup_lantai'],
            'foto_sloof' => $data['foto_sloof'],
            'foto_mck' => $data['foto_mck'],
            'foto_air_kotor' => $data['foto_air_kotor'],
            'penilaian_kerusakan' => $penilaian_kerusakan,
            'nilai_bantuan' => $nilai_bantuan,
            'catatan' => $catatan
        ]);

        // Update status di tabel data_cpb
        $dataCPB = DataCPB::where('nik', $nik)->first();
        if ($dataCPB) {
            $dataCPB->pengecekan = 'Sudah Dicek';
            $dataCPB->status = $nilai_bantuan > 0 ? 'Terverifikasi' : 'Tidak Terverifikasi';
            $dataCPB->save();
        }

        // Redirect dengan pesan sukses
        return redirect()->route('admin.data_verif_cpb')
            ->with('success', 'Data verifikasi CPB berhasil diperbarui');
    }
}
