<?php

namespace App\Http\Controllers\Petugas;

use App\Models\DataCPB;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\SimpleType\Jc;
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
            'nama'       => $request->nama,
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
        $section = $phpWord->addSection();

        // Header Surat
        $section->addText("SURAT PERNYATAAN", [
            'bold' => true,
            'size' => 14
        ], [
            'alignment' => Jc::CENTER
        ]);

        $section->addText("Yang bertanda tangan di bawah ini:", [
            'size' => 12
        ]);
        $section->addTextBreak(1);

        // Data Penerima Bantuan
        $section->addText("Nama          : " . $cpb->nama, ['size' => 12]);
        $section->addText("NIK           : " . $cpb->nik, ['size' => 12]);
        $section->addText("No KK         : " . $cpb->no_kk, ['size' => 12]);
        $section->addText("Alamat        : " . $cpb->alamat, ['size' => 12]);
        $section->addText("Pekerjaan     : " . $cpb->pekerjaan, ['size' => 12]);
        $section->addText("Email         : " . $cpb->email, ['size' => 12]);
        $section->addTextBreak(1);

        // Isi Pernyataan
        $isiPernyataan = "Dengan ini saya menyatakan bahwa saya benar-benar merupakan penerima bantuan sosial "
            . "dan data yang saya berikan adalah benar. Jika dikemudian hari ditemukan kesalahan dalam data saya, "
            . "saya bersedia menerima konsekuensi sesuai ketentuan yang berlaku.";
        $section->addText($isiPernyataan, ['size' => 12], ['alignment' => Jc::BOTH]);
        $section->addTextBreak(2);

        // Tanda Tangan
        $tanggal = date("d F Y"); // Format tanggal otomatis
        $section->addText("Dibuat pada: " . $tanggal, ['size' => 12]);
        $section->addText("Penerima Bantuan,", ['size' => 12], ['alignment' => Jc::RIGHT]);
        $section->addTextBreak(3);
        $section->addText($cpb->nama, ['size' => 12, 'bold' => true], ['alignment' => Jc::RIGHT]);

        // Simpan dan Unduh
        $fileName = 'Surat_Pernyataan_' . $cpb->id . '.docx';
        $tempFile = storage_path($fileName);
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
