<?php

namespace App\Http\Controllers\Api;

use App\Models\DataCPB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DataVerifikasiCPB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiDataVerifikasiCPBController extends Controller
{
    public function addVerifikasiCPB(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_kk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'nik' => 'required|exists:data_cpb,nik',
            'kesanggupan_berswadaya' => 'required|boolean',
            'tipe' => 'required|in:T,K',
            'penutup_atap' => 'required|numeric|min:0|max:1',
            'rangka_atap' => 'required|numeric|min:0|max:1',
            'kolom' => 'required|numeric|min:0|max:1',
            'ring_balok' => 'required|numeric|min:0|max:1',
            'dinding_pengisi' => 'required|numeric|min:0|max:1',
            'kusen' => 'required|numeric|min:0|max:1',
            'pintu' => 'required|numeric|min:0|max:1',
            'jendela' => 'required|numeric|min:0|max:1',
            'struktur_bawah' => 'required|numeric|min:0|max:1',
            'penutup_lantai' => 'required|numeric|min:0|max:1',
            'pondasi' => 'required|numeric|min:0|max:1',
            'sloof' => 'required|numeric|min:0|max:1',
            'mck' => 'required|numeric|min:0|max:1',
            'air_kotor' => 'required|numeric|min:0|max:1',

            'foto_penutup_atap' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_rangka_atap' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_kolom' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_ring_balok' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_dinding_pengisi' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_kusen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_pintu' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_jendela' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_struktur_bawah' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_penutup_lantai' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_pondasi' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_sloof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_mck' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_air_kotor' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.exists' => 'NIK tidak ditemukan dalam database penerima bantuan.',
            'kesanggupan_berswadaya.required' => 'Kesanggupan berswadaya harus diisi.',
            'kesanggupan_berswadaya.boolean' => 'Kesanggupan berswadaya harus bernilai benar atau salah.',
            'tipe.required' => 'Tipe rumah wajib diisi.',
            'tipe.in' => 'Tipe rumah harus bernilai "T" (Tembok) atau "K" (Kayu).',

            'penutup_atap.required' => 'Penilaian penutup atap harus diisi.',
            'penutup_atap.numeric' => 'Penilaian penutup atap harus berupa angka.',
            'penutup_atap.min' => 'Penilaian penutup atap minimal 0.',
            'penutup_atap.max' => 'Penilaian penutup atap maksimal 1.',

            'rangka_atap.required' => 'Penilaian rangka atap harus diisi.',
            'rangka_atap.numeric' => 'Penilaian rangka atap harus berupa angka.',
            'rangka_atap.min' => 'Penilaian rangka atap minimal 0.',
            'rangka_atap.max' => 'Penilaian rangka atap maksimal 1.',

            'kolom.required' => 'Penilaian kolom harus diisi.',
            'kolom.numeric' => 'Penilaian kolom harus berupa angka.',
            'kolom.min' => 'Penilaian kolom minimal 0.',
            'kolom.max' => 'Penilaian kolom maksimal 1.',

            'ring_balok.required' => 'Penilaian ring balok harus diisi.',
            'ring_balok.numeric' => 'Penilaian ring balok harus berupa angka.',
            'ring_balok.min' => 'Penilaian ring balok minimal 0.',
            'ring_balok.max' => 'Penilaian ring balok maksimal 1.',

            'dinding_pengisi.required' => 'Penilaian dinding pengisi harus diisi.',
            'dinding_pengisi.numeric' => 'Penilaian dinding pengisi harus berupa angka.',
            'dinding_pengisi.min' => 'Penilaian dinding pengisi minimal 0.',
            'dinding_pengisi.max' => 'Penilaian dinding pengisi maksimal 1.',

            'kusen.required' => 'Penilaian kusen harus diisi.',
            'kusen.numeric' => 'Penilaian kusen harus berupa angka.',
            'kusen.min' => 'Penilaian kusen minimal 0.',
            'kusen.max' => 'Penilaian kusen maksimal 1.',

            'pintu.required' => 'Penilaian pintu harus diisi.',
            'pintu.numeric' => 'Penilaian pintu harus berupa angka.',
            'pintu.min' => 'Penilaian pintu minimal 0.',
            'pintu.max' => 'Penilaian pintu maksimal 1.',

            'jendela.required' => 'Penilaian jendela harus diisi.',
            'jendela.numeric' => 'Penilaian jendela harus berupa angka.',
            'jendela.min' => 'Penilaian jendela minimal 0.',
            'jendela.max' => 'Penilaian jendela maksimal 1.',

            'struktur_bawah.required' => 'Penilaian struktur bawah harus diisi.',
            'struktur_bawah.numeric' => 'Penilaian struktur bawah harus berupa angka.',
            'struktur_bawah.min' => 'Penilaian struktur bawah minimal 0.',
            'struktur_bawah.max' => 'Penilaian struktur bawah maksimal 1.',

            'penutup_lantai.required' => 'Penilaian penutup lantai harus diisi.',
            'penutup_lantai.numeric' => 'Penilaian penutup lantai harus berupa angka.',
            'penutup_lantai.min' => 'Penilaian penutup lantai minimal 0.',
            'penutup_lantai.max' => 'Penilaian penutup lantai maksimal 1.',

            'pondasi.required' => 'Penilaian pondasi harus diisi.',
            'pondasi.numeric' => 'Penilaian pondasi harus berupa angka.',
            'pondasi.min' => 'Penilaian pondasi minimal 0.',
            'pondasi.max' => 'Penilaian pondasi maksimal 1.',

            'sloof.required' => 'Penilaian sloof harus diisi.',
            'sloof.numeric' => 'Penilaian sloof harus berupa angka.',
            'sloof.min' => 'Penilaian sloof minimal 0.',
            'sloof.max' => 'Penilaian sloof maksimal 1.',

            'mck.required' => 'Penilaian MCK harus diisi.',
            'mck.numeric' => 'Penilaian MCK harus berupa angka.',
            'mck.min' => 'Penilaian MCK minimal 0.',
            'mck.max' => 'Penilaian MCK maksimal 1.',

            'air_kotor.required' => 'Penilaian air kotor harus diisi.',
            'air_kotor.numeric' => 'Penilaian air kotor harus berupa angka.',
            'air_kotor.min' => 'Penilaian air kotor minimal 0.',
            'air_kotor.max' => 'Penilaian air kotor maksimal 1.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terdapat kesalahan dalam input data.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        $bobot_tembok = [12.4, 13.65, 5.675, 5.675, 16.1, 2.81, 3.02, 6.3, 3.4, 10.52, 13.1, 3.91, 2.01, 1.43];
        $bobot_kayu = [15.19, 3.39, 5.825, 5.825, 29.58, 4.6, 1.08, 1.09, 0.71, 4.49, 3.93, 19.05, 1.42, 3.82];

        $bobot = $data['tipe'] === 'T' ? $bobot_tembok : $bobot_kayu;
        $kesanggupan_berswadaya = $data['kesanggupan_berswadaya'];

        // Hitung total penilaian kerusakan
        $kerusakan_fields = ['penutup_atap', 'rangka_atap', 'kolom', 'ring_balok', 'dinding_pengisi', 'kusen', 'pintu', 'jendela', 'struktur_bawah', 'penutup_lantai', 'pondasi', 'sloof', 'mck', 'air_kotor'];
        $penilaian_kerusakan = 0;

        foreach ($kerusakan_fields as $index => $field) {
            $penilaian_kerusakan += ($data[$field] ?? 0) * $bobot[$index];
        }
        $penilaian_kerusakan *= $kesanggupan_berswadaya;

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

        $fotoFields = [
            'foto_kk',
            'foto_ktp',
            'foto_penutup_atap',
            'foto_rangka_atap',
            'foto_kolom',
            'foto_ring_balok',
            'foto_dinding_pengisi',
            'foto_kusen',
            'foto_pintu',
            'foto_jendela',
            'foto_struktur_bawah',
            'foto_penutup_lantai',
            'foto_pondasi',
            'foto_sloof',
            'foto_mck',
            'foto_air_kotor'
        ];

        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                // Ambil NIK dari data
                $nik = $data['nik'];

                // Buat direktori unik berdasarkan NIK
                $nikFolder = public_path('up/verifikasi/' . $nik);

                // Buat direktori jika belum ada
                if (!file_exists($nikFolder)) {
                    mkdir($nikFolder, 0755, true);
                }

                $customName = $field;
                if ($field === 'foto_air_kotor') {
                    $customName = 'foto_air_bersih';
                } elseif ($field === 'foto_mck') {
                    $customName = 'foto_sanitasi';
                }

                $filename = $customName . '_' . time() . '_' . uniqid() . '.' . $request->file($field)->getClientOriginalExtension();
                // Pindahkan file ke direktori NIK
                $request->file($field)->move($nikFolder, $filename);

                // Simpan path relatif ke database
                $data[$field] = 'up/verifikasi/' . $nik . '/' . $filename;
            }
        }

        // Simpan ke database
        $verifikasi = DataVerifikasiCPB::create(array_merge($data, [
            'penilaian_kerusakan' => $penilaian_kerusakan,
            'nilai_bantuan' => $nilai_bantuan,
            'catatan' => $catatan
        ]));

        // Update pengecekan di tabel data_cpb
        $dataCPB = DataCPB::where('nik', $data['nik'])->first();
        if ($dataCPB) {
            $dataCPB->pengecekan = 'Sudah Dicek';

            if ($nilai_bantuan > 0) {
                $dataCPB->status = 'Terverifikasi';
            }

            $dataCPB->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi data CPB berhasil disimpan',
            'data' => $verifikasi
        ], 201);
    }

    public function updateVerifCPB(Request $request, string $id)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'nik' => 'required|exists:data_cpb,nik',
                    'kesanggupan_berswadaya' => 'required|in:0,1',
                    'tipe' => 'required|in:T,K',
                    'penutup_atap' => 'required|numeric|min:0|max:1',
                    'rangka_atap' => 'required|numeric|min:0|max:1',
                    'kolom' => 'required|numeric|min:0|max:1',
                    'ring_balok' => 'required|numeric|min:0|max:1',
                    'dinding_pengisi' => 'required|numeric|min:0|max:1',
                    'kusen' => 'required|numeric|min:0|max:1',
                    'pintu' => 'required|numeric|min:0|max:1',
                    'jendela' => 'required|numeric|min:0|max:1',
                    'struktur_bawah' => 'required|numeric|min:0|max:1',
                    'penutup_lantai' => 'required|numeric|min:0|max:1',
                    'pondasi' => 'required|numeric|min:0|max:1',
                    'sloof' => 'required|numeric|min:0|max:1',
                    'mck' => 'required|numeric|min:0|max:1',
                    'air_kotor' => 'required|numeric|min:0|max:1',
                    'foto_penutup_atap' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_rangka_atap' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_kolom' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_ring_balok' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_dinding_pengisi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_kusen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_pintu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_jendela' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_struktur_bawah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_penutup_lantai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_pondasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_sloof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_mck' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'foto_air_kotor' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                ],
                [
                    'nik.required' => 'NIK wajib diisi.',
                    'nik.exists' => 'NIK tidak ditemukan dalam database penerima bantuan.',
                    'kesanggupan_berswadaya.required' => 'Kesanggupan berswadaya harus diisi.',
                    'tipe.required' => 'Tipe rumah wajib diisi.',
                    'tipe.in' => 'Tipe rumah harus bernilai "T" (Tembok) atau "K" (Kayu).',
                    'penutup_atap.required' => 'Penilaian penutup atap harus diisi.',
                    'penutup_atap.numeric' => 'Penilaian penutup atap harus berupa angka.',
                    'penutup_atap.min' => 'Penilaian penutup atap minimal 0.',
                    'penutup_atap.max' => 'Penilaian penutup atap maksimal 1.',
                    'rangka_atap.required' => 'Penilaian rangka atap harus diisi.',
                    'rangka_atap.numeric' => 'Penilaian rangka atap harus berupa angka.',
                    'rangka_atap.min' => 'Penilaian rangka atap minimal 0.',
                    'rangka_atap.max' => 'Penilaian rangka atap maksimal 1.',
                    'kolom.required' => 'Penilaian kolom harus diisi.',
                    'kolom.numeric' => 'Penilaian kolom harus berupa angka.',
                    'kolom.min' => 'Penilaian kolom minimal 0.',
                    'kolom.max' => 'Penilaian kolom maksimal 1.',
                    'ring_balok.required' => 'Penilaian ring balok harus diisi.',
                    'ring_balok.numeric' => 'Penilaian ring balok harus berupa angka.',
                    'ring_balok.min' => 'Penilaian ring balok minimal 0.',
                    'ring_balok.max' => 'Penilaian ring balok maksimal 1.',
                    'dinding_pengisi.required' => 'Penilaian dinding pengisi harus diisi.',
                    'dinding_pengisi.numeric' => 'Penilaian dinding pengisi harus berupa angka.',
                    'dinding_pengisi.min' => 'Penilaian dinding pengisi minimal 0.',
                    'dinding_pengisi.max' => 'Penilaian dinding pengisi maksimal 1.',
                    'kusen.required' => 'Penilaian kusen harus diisi.',
                    'kusen.numeric' => 'Penilaian kusen harus berupa angka.',
                    'kusen.min' => 'Penilaian kusen minimal 0.',
                    'kusen.max' => 'Penilaian kusen maksimal 1.',
                    'pintu.required' => 'Penilaian pintu harus diisi.',
                    'pintu.numeric' => 'Penilaian pintu harus berupa angka.',
                    'pintu.min' => 'Penilaian pintu minimal 0.',
                    'pintu.max' => 'Penilaian pintu maksimal 1.',
                    'jendela.required' => 'Penilaian jendela harus diisi.',
                    'jendela.numeric' => 'Penilaian jendela harus berupa angka.',
                    'jendela.min' => 'Penilaian jendela minimal 0.',
                    'jendela.max' => 'Penilaian jendela maksimal 1.',
                    'struktur_bawah.required' => 'Penilaian struktur bawah harus diisi.',
                    'struktur_bawah.numeric' => 'Penilaian struktur bawah harus berupa angka.',
                    'struktur_bawah.min' => 'Penilaian struktur bawah minimal 0.',
                    'struktur_bawah.max' => 'Penilaian struktur bawah maksimal 1.',
                    'penutup_lantai.required' => 'Penilaian penutup lantai harus diisi.',
                    'penutup_lantai.numeric' => 'Penilaian penutup lantai harus berupa angka.',
                    'penutup_lantai.min' => 'Penilaian penutup lantai minimal 0.',
                    'penutup_lantai.max' => 'Penilaian penutup lantai maksimal 1.',
                    'pondasi.required' => 'Penilaian pondasi harus diisi.',
                    'pondasi.numeric' => 'Penilaian pondasi harus berupa angka.',
                    'pondasi.min' => 'Penilaian pondasi minimal 0.',
                    'pondasi.max' => 'Penilaian pondasi maksimal 1.',
                    'sloof.required' => 'Penilaian sloof harus diisi.',
                    'sloof.numeric' => 'Penilaian sloof harus berupa angka.',
                    'sloof.min' => 'Penilaian sloof minimal 0.',
                    'sloof.max' => 'Penilaian sloof maksimal 1.',
                    'mck.required' => 'Penilaian MCK harus diisi.',
                    'mck.numeric' => 'Penilaian MCK harus berupa angka.',
                    'mck.min' => 'Penilaian MCK minimal 0.',
                    'mck.max' => 'Penilaian MCK maksimal 1.',
                    'air_kotor.required' => 'Penilaian air kotor harus diisi.',
                    'air_kotor.numeric' => 'Penilaian air kotor harus berupa angka.',
                    'air_kotor.min' => 'Penilaian air kotor minimal 0.',
                    'air_kotor.max' => 'Penilaian air kotor maksimal 1.',
                ]
            );

            // Jika validasi gagal
            if ($validator->fails()) {
                Log::error('Validasi gagal:', $validator->errors()->toArray());
                return response()->json([
                    'status' => 'errors',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Ambil data yang sudah ada di database
            $verifikasi = DataVerifikasiCPB::findOrFail($id);

            // Ambil semua data dari request
            $data = $request->all();
            $nik = $data['nik'];

            // Set bobot berdasarkan tipe rumah
            $bobot_tembok = [12.4, 13.65, 5.675, 5.675, 16.1, 2.81, 3.02, 6.3, 3.4, 10.52, 13.1, 3.91, 2.01, 1.43];
            $bobot_kayu = [15.19, 3.39, 5.825, 5.825, 29.58, 4.6, 1.08, 1.09, 0.71, 4.49, 3.93, 19.05, 1.42, 3.82];
            $bobot = $data['tipe'] === 'T' ? $bobot_tembok : $bobot_kayu;

            // Hitung penilaian kerusakan
            $kerusakan_fields = ['penutup_atap', 'rangka_atap', 'kolom', 'ring_balok', 'dinding_pengisi', 'kusen', 'pintu', 'jendela', 'struktur_bawah', 'penutup_lantai', 'pondasi', 'sloof', 'mck', 'air_kotor'];
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
                'foto_kk',
                'foto_ktp',
                'foto_penutup_atap',
                'foto_rangka_atap',
                'foto_kolom',
                'foto_ring_balok',
                'foto_dinding_pengisi',
                'foto_kusen',
                'foto_pintu',
                'foto_jendela',
                'foto_struktur_bawah',
                'foto_penutup_lantai',
                'foto_pondasi',
                'foto_sloof',
                'foto_mck',
                'foto_air_kotor'
            ];

            foreach ($fotoFields as $field) {
                if ($request->hasFile($field)) {
                    // Buat direktori unik berdasarkan NIK
                    $nikFolder = public_path('up/verifikasi/' . $nik);

                    // Buat direktori jika belum ada
                    if (!file_exists($nikFolder)) {
                        mkdir($nikFolder, 0755, true);
                    }
                    $customName = $field;
                    if ($field === 'foto_air_kotor') {
                        $customName = 'foto_air_bersih';
                    } elseif ($field === 'foto_mck') {
                        $customName = 'foto_sanitasi';
                    }
                    // Generate nama file unik
                    $filename = $customName . '_' . time() . '_' . uniqid() . '.' . $request->file($field)->getClientOriginalExtension();
                    $request->file($field)->move($nikFolder, $filename);

                    // Hapus file lama jika ada
                    if (!empty($verifikasi->$field) && file_exists(public_path($verifikasi->$field))) {
                        unlink(public_path($verifikasi->$field));
                    }

                    // Simpan path relatif ke database
                    $data[$field] = 'up/verifikasi/' . $nik . '/' . $filename;
                } else {
                    // Jika tidak ada file baru, gunakan file yang sudah ada
                    $data[$field] = $verifikasi->$field;
                }
            }

            // Update data verifikasi
            $verifikasi->updateOrFail([
                'nik' => $nik,
                'kesanggupan_berswadaya' => $data['kesanggupan_berswadaya'],
                'tipe' => $data['tipe'],
                'penutup_atap' => $data['penutup_atap'],
                'rangka_atap' => $data['rangka_atap'],
                'kolom' => $data['kolom'],
                'ring_balok' => $data['ring_balok'],
                'dinding_pengisi' => $data['dinding_pengisi'],
                'kusen' => $data['kusen'],
                'pintu' => $data['pintu'],
                'jendela' => $data['jendela'],
                'struktur_bawah' => $data['struktur_bawah'],
                'penutup_lantai' => $data['penutup_lantai'],
                'pondasi' => $data['pondasi'],
                'sloof' => $data['sloof'],
                'mck' => $data['mck'],
                'air_kotor' => $data['air_kotor'],
                'foto_kk' => $data['foto_kk'],
                'foto_ktp' => $data['foto_ktp'],
                'foto_penutup_atap' => $data['foto_penutup_atap'],
                'foto_rangka_atap' => $data['foto_rangka_atap'],
                'foto_kolom' => $data['foto_kolom'],
                'foto_ring_balok' => $data['foto_ring_balok'],
                'foto_dinding_pengisi' => $data['foto_dinding_pengisi'],
                'foto_kusen' => $data['foto_kusen'],
                'foto_pintu' => $data['foto_pintu'],
                'foto_jendela' => $data['foto_jendela'],
                'foto_struktur_bawah' => $data['foto_struktur_bawah'],
                'foto_penutup_lantai' => $data['foto_penutup_lantai'],
                'foto_pondasi' => $data['foto_pondasi'],
                'foto_sloof' => $data['foto_sloof'],
                'foto_mck' => $data['foto_mck'],
                'foto_air_kotor' => $data['foto_air_kotor'],
                'penilaian_kerusakan' => $penilaian_kerusakan,
                'nilai_bantuan' => $nilai_bantuan,
                'catatan' => $catatan
            ]);

            // Update status di tabel data_cpb
            $dataCPB = DataCPB::where('nik', $data['nik'])->first();

            if ($dataCPB) {
                $dataCPB->pengecekan = 'Sudah Dicek';
                $dataCPB->status = $nilai_bantuan > 0 ? 'Terverifikasi' : 'Tidak Terverifikasi';
                $dataCPB->save();
            }

            // Mengembalikan response JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Data verifikasi CPB berhasil diperbarui',
                'data' => $verifikasi
            ], 200);
        } catch (\Throwable $th) {
            Log::error('kesalahan : ' .  $th->getMessage());
            return response()->json('error : ' . $th->getMessage());
        }
    }

    public function getVerifCPB(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nik' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 400);
            }

            $nik = $request->query('nik');

            // Ambil data verifikasi berdasarkan NIK
            $data = DataVerifikasiCPB::where('nik', $nik)->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data verifikasi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data verifikasi berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in getVerifCPB: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }

    // Di DataVerifikasiCPBController.php
    public function destroyByCpbId($cpbId)
    {
        try {
            // Cari data CPB berdasarkan ID
            $dataCPB = DataCPB::findOrFail($cpbId);
            $nik = $dataCPB->nik;

            // Cari data verifikasi berdasarkan NIK
            $dataVerif = DataVerifikasiCPB::where('nik', $nik)->first();

            if (!$dataVerif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data verifikasi tidak ditemukan untuk data CPB ini',
                    'errors' => ['id' => 'Data verifikasi tidak ditemukan']
                ], 404);
            }

            // Hapus folder foto di public/up/verifikasi/{nik}
            $folderPath = public_path('up/verifikasi/' . $nik);
            if (file_exists($folderPath) && is_dir($folderPath)) {
                $this->deleteDirectory($folderPath);
            }

            // Hapus data verifikasi
            $dataVerif->delete();

            // Update status dan pengecekan pada DataCPB
            $dataCPB->status = 'Tidak Terverifikasi';
            $dataCPB->pengecekan = 'Belum Dicek';
            $dataCPB->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data verifikasi berhasil dihapus',
                'data' => new \stdClass() // gae respon ben dadi object kosong
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data CPB tidak ditemukan',
                'errors' => ['id' => 'Data tidak ditemukan']
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan dalam menghapus data',
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    // Fungsi helper untuk menghapus direktori beserta isinya
    private function deleteDirectory($dirPath)
    {
        if (!is_dir($dirPath)) {
            return;
        }

        $files = array_diff(scandir($dirPath), array('.', '..'));

        foreach ($files as $file) {
            $path = $dirPath . '/' . $file;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        return rmdir($dirPath);
    }
}
