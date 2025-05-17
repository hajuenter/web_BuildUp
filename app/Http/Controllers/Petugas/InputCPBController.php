<?php

namespace App\Http\Controllers\Petugas;

use App\Models\DataCPB;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Models\DataVerifikasiCPB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Validator;

class InputCPBController extends Controller
{
    public function showDataCPB(Request $request)
    {
        $query = DataCPB::query();

        // Ambil input pencarian
        $nik = $request->input('nik');
        $no_kk = $request->input('no_kk');
        $nama = $request->input('nama');
        $perPage = $request->input('perPage', 5);

        $desa = session('desa_petugas');
        $kecamatan = session('kecamatan_petugas');

        // Filter berdasarkan alamat petugas
        if ($desa && $kecamatan) {
            $query->whereRaw("TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(alamat, ';', 2), ';', -1)) = ?", [$desa])
                ->whereRaw("TRIM(SUBSTRING_INDEX(alamat, ';', -1)) = ?", [$kecamatan]);
        }

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

        return view('screen_petugas.data_cpb.data_cpb', compact('dataCPB', 'perPage'));
    }

    public function showFormInputCPB()
    {
        $desa = session('desa_petugas', '');
        $kecamatan = session('kecamatan_petugas', '');

        return view('screen_petugas.input_cbp.data_cpb', compact('desa', 'kecamatan'));
    }

    public function inputCPB(Request $request)
    {
        // Aturan Validasi
        $validator = Validator::make($request->all(), [
            'desa'      => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
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
            'desa.required'      => 'Desa/Kelurahan wajib diisi.',
            'desa.max'           => 'Desa/Kelurahan tidak boleh lebih dari 255 karakter.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'kecamatan.max'      => 'Kecamatan tidak boleh lebih dari 255 karakter.',
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

        $gabunganAlamat = $request->alamat . '; ' . $request->desa . '; ' . $request->kecamatan;

        // Simpan Data ke Database
        DataCPB::create([
            'nama'       => strtoupper($request->nama),
            'alamat'     => $gabunganAlamat,
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
        return view('screen_petugas.data_cpb.edit_data_cpb', compact('cpb'));
    }

    public function updateCPB(Request $request, $id)
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
            'nama.required'       => 'Nama wajib diisi.',
            'nama.regex'          => 'Nama tidak valid.',
            'nama.max'            => 'Nama tidak boleh lebih dari 255 karakter.',
            'alamat_jalan.required'     => 'Alamat wajib diisi.',
            'alamat_desa.required'     => 'Alamat wajib diisi.',
            'alamat_kecamatan.required'     => 'Alamat wajib diisi.',
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

        return redirect()->route('petugas.datacpb')->with('successEditCPB', 'Data CPB berhasil diperbarui.');
    }

    public function deleteCPB($id)
    {
        // Ambil data berdasarkan ID
        $cpb = DataCPB::findOrFail($id);
        $nik = $cpb->nik;

        $verifikasi = DataVerifikasiCPB::where('nik', $nik)->get();

        $folderPath = public_path("up/verifikasi/{$nik}");

        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
        }

        // Hapus data verifikasi jika ada
        if ($verifikasi->isNotEmpty()) {
            DataVerifikasiCPB::where('nik', $nik)->delete();
        }

        // Hapus gambar rumah CPB jika ada
        if ($cpb->foto_rumah && file_exists(public_path($cpb->foto_rumah))) {
            unlink(public_path($cpb->foto_rumah));
        }

        // Hapus data CPB dari database
        $cpb->delete();


        return redirect()->route('petugas.datacpb')->with('successDeleteCPB', 'Data CPB berhasil dihapus.');
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

        // Tambahkan section baru (opsional agar halaman baru)
        $section = $phpWord->addSection([
            'pageBreakBefore' => true,
        ]);

        // Judul lampiran
        $section->addText("Lampiran 5", ['bold' => true]);
        $section->addText("Surat Pernyataan Tanggung Jawab Pengusul Bantuan Sosial");
        $section->addTextBreak(1);
        $section->addText(
            "SURAT PERNYATAAN TANGGUNG JAWAB PENGUSUL BANTUAN SOSIAL",
            ['bold' => true, 'size' => 14],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 300]
        );

        // Identitas
        $section->addText("Yang bertanda tangan di bawah ini :");

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(3000)->addText("Nama");
        $table->addCell(500)->addText(":");
        $table->addCell(6000)->addText($cpb->nama);

        $table->addRow();
        $table->addCell(3000)->addText("NIK");
        $table->addCell(500)->addText(":");
        $table->addCell(6000)->addText($cpb->nik);

        $table->addRow();
        $table->addCell(3000)->addText("Alamat");
        $table->addCell(500)->addText(":");
        $table->addCell(6000)->addText(str_replace(';', ',', $cpb->alamat));

        // Isi pernyataan
        $section->addTextBreak(1);
        $justify = ['alignment' => Jc::BOTH]; // digunakan berulang biar rapi
        $section->addText(
            "Saya selaku masyarakat pemohon bantuan sosial, dengan ini menyatakan bahwa:",
            [],
            $justify
        );

        $section->addText(
            "1. Bertanggung jawab sepenuhnya terhadap kebenaran data yang diajukan di dalam proposal Bantuan Sosial untuk Tahun Anggaran ……… dan apabila dikemudian hari ternyata ditemukan data yang tidak benar, maka saya siap bertanggung jawab dan menanggung segala konsekuensi hukum yang timbul.",
            [],
            $justify
        );

        $section->addText(
            "2. Akan menggunakan bantuan sesuai dengan proposal dan bertanggung jawab penggunaannya secara formal dan material apabila mendapatkan bantuan dari Pemerintah Daerah.",
            [],
            $justify
        );

        $section->addTextBreak(1);

        $section->addText(
            "Demikian surat pernyataan ini saya buat dengan sebenar-benarnya tanpa ada unsur paksaan untuk dapat digunakan sebagaimana mestinya.",
            [],
            $justify
        );
        $section->addTextBreak(2);

        // Buat tabel utama dengan 3 kolom
        $table = $section->addTable();
        $table->addRow();

        // Buat sel untuk setiap kolom
        $cellKiri = $table->addCell(4000);
        $cellTengah = $table->addCell(3000); // Kolom tengah sebagai pemisah/spasi
        $cellKanan = $table->addCell(4000);

        // Tambahkan spasi di awal
        $cellKiri->addTextBreak(1);
        $cellTengah->addTextBreak(1);
        $cellKanan->addTextBreak(1);

        // Tambahkan teks di kolom kanan
        $cellKanan->addText("Pengusul,", null, ['alignment' => Jc::CENTER]);
        $cellKanan->addText("Calon Penerima Bansos", null, ['alignment' => Jc::CENTER]);

        // Buat tabel untuk area tanda tangan dan materai dalam sel kanan
        $tableTandaTangan = $cellKanan->addTable(['alignment' => Jc::CENTER]);
        $tableTandaTangan->addRow();
        $cellTandaTangan = $tableTandaTangan->addCell(2500);

        // Tambahkan kotak materai di area tanda tangan
        $tableMaterai = $cellTandaTangan->addTable(['alignment' => Jc::CENTER]);
        $tableMaterai->addRow();
        $cellMaterai = $tableMaterai->addCell(1500, [
            'borderSize' => 6,
            'borderColor' => '000000',
            'valign' => 'center',
        ]);

        // Tambahkan teks materai di dalam kotak
        $cellMaterai->addText("Materai", ['color' => 'AA0000'], ['alignment' => Jc::CENTER]);
        $cellMaterai->addText("Rp.10.000,00", ['bold' => true], ['alignment' => Jc::CENTER]);

        // Tambahkan garis untuk tanda tangan di bawah materai
        $cellKanan->addText("…………………………", ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::CENTER]);

        // Simpan dan Unduh
        $fileName = 'Surat_Permohonan_' . $cpb->id . '.docx';
        $tempFile = storage_path($fileName);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
