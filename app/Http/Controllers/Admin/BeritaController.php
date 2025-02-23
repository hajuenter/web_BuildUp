<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Berita;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BeritaController extends Controller
{
    public function showBerita(Request $request)
    {
        // Ambil semua input pencarian dari request
        $id = $request->id;
        $judul = $request->judul;
        $penulis = $request->penulis;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Query dasar
        $query = Berita::query();

        // Filter berdasarkan ID
        if (!empty($id)) {
            $query->where('id', $id);
        }

        // Filter berdasarkan Judul
        if (!empty($judul)) {
            $query->where('judul', 'like', "%$judul%");
        }

        // Filter berdasarkan Penulis
        if (!empty($penulis)) {
            $query->where('penulis', 'like', "%$penulis%");
        }

        // Filter berdasarkan Tanggal
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
        } elseif (!empty($start_date)) {
            $query->whereDate('created_at', '>=', $start_date);
        } elseif (!empty($end_date)) {
            $query->whereDate('created_at', '<=', $end_date);
        }

        // Ambil data dengan pagination
        $dataBerita = $query->paginate(5);

        // Pastikan pagination tetap mempertahankan parameter pencarian
        $dataBerita->appends(request()->query());

        return view('screen_admin.berita.berita', compact('dataBerita'));
    }



    public function showAddBerita()
    {
        return view('screen_admin.berita.tambah_berita');
    }

    public function addBerita(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tempat' => 'required|string',
            'penulis' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048', // Maksimal 2MB
        ]);

        // Proses upload foto
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Generate nama unik untuk foto
            $fileName = 'berita_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Inisialisasi ImageManager dengan driver GD
            $manager = new ImageManager(new Driver());

            // Resize gambar
            $image = $manager->read($file)->scale(width: 992, height: 950);

            // Simpan gambar ke folder public/up/berita/
            $image->save(public_path('up/berita/' . $fileName));

            // Simpan data berita ke database
            Berita::create([
                'id_user' => Auth::id(),
                'judul' => $request->judul,
                'isi' => $request->isi,
                'penulis' => $request->penulis,
                'tempat' => $request->tempat,
                'photo' => $fileName,
                'tanggal' => Carbon::now(),
            ]);

            return redirect()->route('admin.berita')->with('success', 'Berita berhasil ditambahkan!');
        }

        return back()->with('error', 'Gagal mengupload foto');
    }

    public function showEditBerita($id)
    {
        $berita = Berita::findOrFail($id);
        return view('screen_admin.berita.edit_berita', compact('berita'));
    }

    public function updateBerita(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'penulis' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $berita = Berita::findOrFail($id);

        $berita->judul = $request->judul;
        $berita->isi = $request->isi;
        $berita->penulis = $request->penulis;

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($berita->photo && file_exists(public_path('up/berita/' . $berita->photo))) {
                unlink(public_path('up/berita/' . $berita->photo));
            }

            // Ambil file dari request
            $file = $request->file('photo');
            $filename = 'berita_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Resize dan simpan menggunakan Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file)->scale(width: 992, height: 950);
            $image->save(public_path('up/berita/' . $filename));

            // Simpan nama file di database
            $berita->photo = $filename;
        }

        $berita->save();

        return redirect()->route('admin.berita')->with('success', 'Berita berhasil diperbarui!');
    }

    public function deleteBerita($id)
    {
        $berita = Berita::findOrFail($id);

        // Hapus foto jika ada
        if ($berita->photo && file_exists(public_path('up/berita/' . $berita->photo))) {
            unlink(public_path('up/berita/' . $berita->photo));
        }

        // Hapus berita dari database
        $berita->delete();

        return redirect()->route('admin.berita')->with('success', 'Berita berhasil dihapus!');
    }
}
