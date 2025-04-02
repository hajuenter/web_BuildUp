<?php

namespace App\Http\Controllers\Petugas;

use App\Models\DataCPB;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Models\DataVerifikasiCPB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Validator;

class InputCPBController extends Controller
{
    public function showFormInpuCPB(Request $request)
    {
        $query = DataCPB::query();

        // Ambil input pencarian
        $nik = $request->input('nik');
        $no_kk = $request->input('no_kk');
        $nama = $request->input('nama');
        $perPage = $request->input('perPage', 5); // Default 5

        // Filter berdasarkan input yang diisi
        if (!empty($nik)) {
            $query->where('nik', 'like', "%$nik%");
        }
        if (!empty($no_kk)) {
            $query->where('no_kk', 'like', "%$no_kk%");
        }
        if (!empty($nama)) {
            $query->where('nama', 'like', "%$nama%");
        }

        $perPage = $request->input('perPage', 5); // Default 5 jika tidak ada input

        // Jika pilih "Semua", ambil semua data tanpa pagination
        if ($perPage == "all") {
            $dataCPB = $query->get();
        } else {
            $dataCPB = $query->paginate($perPage);
            $dataCPB->appends(request()->query());
        }

        return view('screen_petugas.input_cbp.data_cpb', compact('dataCPB', 'perPage'));
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
            'nama'       => strtoupper($request->nama),
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

    public function showEditCPB($id)
    {
        $cpb = DataCPB::findOrFail($id);
        return view('screen_petugas.input_cbp.edit_data_cpb', compact('cpb'));
    }

    public function updateCPB(Request $request, $id)
    {
        // Ambil data CPB berdasarkan ID
        $cpb = DataCPB::findOrFail($id);

        // Validasi Input
        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|regex:/^[A-Za-z\s\.\'\-]+$/u|max:255',
            'alamat'     => 'required|string',
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
            'nama.required'       => 'Nama wajib diisi.',
            'nama.regex'          => 'Nama tidak valid.',
            'nama.max'            => 'Nama tidak boleh lebih dari 255 karakter.',
            'alamat.required'     => 'Alamat wajib diisi.',
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

        // Update Data di Database
        $cpb->update([
            'nama'       => strtoupper($request->nama),
            'alamat'     => $request->alamat,
            'nik'        => $request->nik,
            'no_kk'      => $request->no_kk,
            'pekerjaan'  => $request->pekerjaan,
            'email'      => $request->email,
            'foto_rumah' => $fotoPath,
            'koordinat'  => $request->koordinat,
        ]);

        return redirect()->route('petugas.inputcpb')->with('successEditCPB', 'Data CPB berhasil diperbarui.');
    }

    public function deleteCPB($id)
    {
        // Ambil data berdasarkan ID
        $cpb = DataCPB::findOrFail($id);
        $nik = $cpb->nik;

        $verifikasiExists = DataVerifikasiCPB::where('nik', $nik)->exists();

        if ($verifikasiExists) {
            DataVerifikasiCPB::where('nik', $nik)->delete();
        }

        // Hapus file gambar jika ada
        if ($cpb->foto_rumah && file_exists(public_path($cpb->foto_rumah))) {
            unlink(public_path($cpb->foto_rumah));
        }

        // Hapus data dari database
        $cpb->delete();

        return redirect()->route('petugas.inputcpb')->with('successDeleteCPB', 'Data CPB berhasil dihapus.');
    }

    public function cetakSurat($id)
    {
        // Ambil data penerima berdasarkan ID
        $cpb = DataCPB::findOrFail($id);

        // Buat dokumen Word baru
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'pageSizeW' => 11906, // A4 width
            'pageSizeH' => 16838, // A4 height
            'marginSTART' => 1000,
            'marginEND' => 1000,
            'marginTop' => 500,
            'marginBottom' => 50,
        ]);

        // Header Surat
        $section->addText("SURAT PERMOHONAN BANTUAN SOSIAL", ['bold' => true], ['alignment' => Jc::CENTER]);

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(8000);
        $table->addCell(4000)->addText(
            "Nganjuk, ……- ….. – 20..",
            [],
            ['alignment' => Jc::END]
        );
        // Membuat tabel
        $table = $section->addTable();

        // Data yang akan ditampilkan dalam format sejajar
        $dataSurat = [
            "Nomor" => "--",
            "Sifat" => "--",
            "Lampiran" => "1 (satu) berkas",
            "Hal" => "Permohonan Bantuan Sosial Penyediaan Rumah Layak Huni",
        ];

        foreach ($dataSurat as $label => $value) {
            $table->addRow();
            $table->addCell(1450)->addText($label); // Kolom pertama (label)
            $table->addCell(500)->addText(":"); // Kolom kedua (titik dua)
            $table->addCell(6000)->addText($value); // Kolom ketiga (isi)
        }

        // Tujuan Surat
        $section->addText("Yth. Bupati Nganjuk");
        $section->addText("Di – NGANJUK");
        // Data Pemohon
        $section->addText("Yang bertanda tangan di bawah ini:");
        // Membuat tabel
        $table = $section->addTable();
        // Data pemohon
        $dataPemohon = [
            "Nama" => $cpb->nama,
            "NIK" => $cpb->nik,
            "Nomor KK" => $cpb->no_kk,
            "Pekerjaan" => $cpb->pekerjaan,
            "Alamat Rumah" => $cpb->alamat,
        ];

        foreach ($dataPemohon as $label => $value) {
            $table->addRow();
            $table->addCell(3000)->addText($label); // Kolom pertama (label)
            $table->addCell(500)->addText(":"); // Kolom kedua (titik dua)
            $table->addCell(6000)->addText($value); // Kolom ketiga (isi)
        }

        // Isi Surat
        $isiSurat = "Dalam rangka meningkatkan kesejahteraan masyarakat utamanya dalam pemenuhan kebutuhan atas rumah layak huni, bersama ini kami mengajukan permohonan untuk diberikan bantuan sosial penyediaan rumah layak huni sebesar Rp…..…..(….. juta rupiah) yang diperuntukkan untuk Perbaikan/Pembangunan *) rumah, dengan perkiraan rincian penggunaan sebagai berikut :";

        $section->addText(
            $isiSurat,
            ['size' => 11],
            ['alignment' => Jc::BOTH, 'spaceAfter' => 0, 'spaceBefore' => 0]
        );

        $styleTable = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
            'width' => 100 * 50 // Mengisi seluruh lebar halaman
        ];

        $styleFirstRow = ['bgColor' => 'D3D3D3'];
        $styleLastRow = ['bgColor' => 'D3D3D3'];

        $phpWord->addTableStyle('TableStyle', $styleTable);
        $table = $section->addTable('TableStyle');

        // Baris pertama (judul tabel)
        $table->addRow();
        $table->addCell(500, $styleFirstRow)->addText("No", ['bold' => true]);
        $table->addCell(4000, $styleFirstRow)->addText("Nama Bahan", ['bold' => true]); // Lebar besar
        $table->addCell(1000, $styleFirstRow)->addText("Vol", ['bold' => true]);
        $table->addCell(1000, $styleFirstRow)->addText("Sat", ['bold' => true]);
        $table->addCell(1500, $styleFirstRow)->addText("Harga Satuan", ['bold' => true]);
        $table->addCell(1500, $styleFirstRow)->addText("Jumlah", ['bold' => true]);

        for ($i = 1; $i <= 3; $i++) {
            $table->addRow();
            $table->addCell(500)->addText($i);
            $table->addCell(4000)->addText("");
            $table->addCell(1000)->addText("");
            $table->addCell(1000)->addText("");
            $table->addCell(1500)->addText("");
            $table->addCell(1500)->addText("");
        }

        $table->addRow(null, $styleLastRow);
        $table->addCell(500, $styleLastRow)->addText(""); // No tetap ada
        $table->addCell(4000, $styleLastRow)->addText(""); // Nama Bahan tetap ada
        $table->addCell(1000, $styleLastRow)->addText(""); // Vol tetap ada
        $table->addCell(1000, $styleLastRow)->addText(""); // Sat tetap ada
        $table->addCell(1500, $styleLastRow)->addText("TOTAL", ['bold' => true], ['alignment' => Jc::START]);
        $table->addCell(1500, $styleLastRow)->addText("Rp", ['bold' => true], ['alignment' => Jc::START]);

        // Penutup
        $penutup = "Perbaikan / Pembangunan *) rumah kami laksanakan dalam 1 (satu) tahun anggaran di lokasi sesuai alamat rumah. Berikut kami lampirkan salinan KTP/KK, dan foto kondisi rumah saat ini dan berkas pendukung lainnya.\nDemikian surat permohonan bantuan sosial ini disampaikan. Atas perhatian dan bantuanya disampaikan terima kasih.";
        $section->addText(
            $penutup,
            ['size' => 11],
            ['alignment' => Jc::BOTH, 'spaceAfter' => 0, 'spaceBefore' => 0]
        );
        $section->addTextBreak(1);
        // Buat tabel dengan dua kolom untuk tanda tangan dan coret yang tidak perlu
        $table = $section->addTable();

        $table->addRow();
        $cellKiri = $table->addCell(6000); // Kolom pertama (Coret yang tidak perlu)
        $cellTengah = $table->addCell(3000); // Kolom kedua (spasi kosong agar tanda tangan lebih ke kanan)
        $cellKanan = $table->addCell(5000, ['alignment' => Jc::END]); // Kolom ketiga (Tanda tangan)

        $cellKiri->addTextBreak(3);
        $cellKiri->addText("*) Coret yang tidak perlu", ['size' => 10]);

        $cellKanan->addText("Pengusul,", ['alignment' => Jc::END]);
        $cellKanan->addText("Calon Penerima Bansos", ['alignment' => Jc::END]);
        $cellKanan->addTextBreak(1);
        $cellKanan->addText("…………………………", ['bold' => true, 'alignment' => Jc::END]);

        // Simpan dan Unduh
        $fileName = 'Surat_Permohonan_' . $cpb->id . '.docx';
        $tempFile = storage_path($fileName);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
