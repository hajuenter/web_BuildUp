<?php

namespace App\Http\Controllers\Petugas;

use App\Models\DataCPB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InputCPBController extends Controller
{
    public function showFormInpuCPB()
    {
        $dataCPB = DataCPB::paginate(5);
        return view('screen_petugas.input_cbp.data_cpb', compact('dataCPB'));
    }

    public function inputCPB(Request $request)
    {
        // Aturan Validasi
        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|regex:/^[A-Za-z\s\.\'\-]+$/u|max:255',
            'alamat'     => 'required|string',
            'nik'        => 'required|digits:16|unique:data_cpb,nik',
            'no_kk'      => 'required|digits:16|unique:data_cpb,no_kk',
            'pekerjaan'  => 'required|string|max:255',
            'email'      => 'required|email|unique:data_cpb,email',
            'foto_rumah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'koordinat'  => [
                'required',
                'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'
            ],
        ], [
            'nama.required'       => 'Nama wajib diisi.',
            'nama.regex'          => 'Nama tidak valid.',
            'nama.max'            => 'Nama tidak boleh lebih dari 255 karakter.',
            'alamat.required'     => 'Alamat wajib diisi.',
            'nik.required'        => 'NIK wajib diisi.',
            'nik.digits'          => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique'          => 'NIK sudah digunakan, gunakan NIK lain.',
            'no_kk.required'      => 'No KK wajib diisi.',
            'no_kk.digits'        => 'No KK harus terdiri dari 16 digit angka.',
            'no_kk.unique'        => 'No KK sudah digunakan, gunakan No KK lain.',
            'pekerjaan.required'  => 'Pekerjaan wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah digunakan, gunakan email lain.',
            'foto_rumah.required' => 'Foto rumah wajib diunggah.',
            'foto_rumah.image'    => 'File harus berupa gambar.',
            'foto_rumah.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
            'foto_rumah.max'      => 'Ukuran gambar maksimal 2MB.',
            'koordinat.required'  => 'Koordinat wajib diisi.',
            'koordinat.regex'     => 'Format koordinat tidak valid (contoh: -6.200, 106.800).',
        ]);

        // Jika Validasi Gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan Foto Rumah
        if ($request->hasFile('foto_rumah')) {
            $file = $request->file('foto_rumah');

            // Pastikan folder tujuan ada
            $folderPath = public_path('up/data_cpb');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Simpan file dengan nama unik
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($folderPath, $fileName);

            $fotoPath = 'up/data_cpb/' . $fileName;
        } else {
            $fotoPath = null;
        }


        // Simpan Data ke Database
        DataCPB::create([
            'nama'       => $request->nama,
            'alamat'     => $request->alamat,
            'nik'        => $request->nik,
            'no_kk'      => $request->no_kk,
            'pekerjaan'  => $request->pekerjaan,
            'email'      => $request->email,
            'foto_rumah' => $fotoPath,
            'koordinat'  => $request->koordinat,
        ]);

        return redirect()->back()->with('success', 'Data CPB berhasil ditambahkan.');
    }
}
