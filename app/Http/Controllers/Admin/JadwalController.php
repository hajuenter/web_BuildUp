<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function showJadwal(Request $request)
    {
        // Ambil semua input pencarian dari request
        $id = $request->id;
        $judul = $request->judul;
        $kategori = $request->kategori;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Query dasar
        $query = Jadwal::query();

        // Filter berdasarkan ID
        if (!empty($id)) {
            $query->where('id', $id);
        }

        // Filter berdasarkan Judul
        if (!empty($judul)) {
            $query->where('judul', 'like', "%$judul%");
        }

        // Filter berdasarkan Kategori
        if (!empty($kategori)) {
            $query->where('kategori', 'like', "%$kategori%");
        }

        // Filter berdasarkan Tanggal
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('tanggal_start', [$start_date, $end_date]);
        } elseif (!empty($start_date)) {
            $query->whereDate('tanggal_start', '>=', $start_date);
        } elseif (!empty($end_date)) {
            $query->whereDate('tanggal_end', '<=', $end_date);
        }

        $perPage = $request->input('perPage', 5);

        if ($perPage == "all") {
            $dataJadwal = $query->get();
        } else {
            $dataJadwal = $query->paginate($perPage);
            $dataJadwal->appends(request()->query());
        }

        return view('screen_admin.jadwal.jadwal', compact('dataJadwal', 'perPage'));
    }

    public function showAddJadwal()
    {
        return view('screen_admin.jadwal.tambah_jadwal');
    }

    public function addJadwal(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:pengecekan,verifikasi,sosialisasi,perbaikan',
            'alamat' => 'required|string',
            'tanggal_start' => 'required|date',
            'tanggal_end' => 'required|date|after_or_equal:tanggal_start',
            'waktu_start' => 'required',
            'waktu_end' => 'required|after:waktu_start',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.string' => 'Judul harus berupa teks.',
            'judul.max' => 'Judul maksimal 255 karakter.',

            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',

            'tanggal_start.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_start.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',

            'tanggal_end.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_end.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'tanggal_end.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'waktu_start.required' => 'Waktu mulai wajib diisi.',

            'waktu_end.required' => 'Waktu selesai wajib diisi.',
            'waktu_end.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        // Simpan data jadwal ke database
        Jadwal::create([
            'id_user' => Auth::id(),
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'tanggal_start' => $request->tanggal_start,
            'tanggal_end' => $request->tanggal_end,
            'waktu_start' => $request->waktu_start,
            'waktu_end' => $request->waktu_end,
        ]);

        return redirect()->route('admin.jadwal')->with('successAddJadwal', 'Jadwal berhasil ditambahkan!');
    }

    public function showEditJadwal($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('screen_admin.jadwal.edit_jadwal', compact('jadwal'));
    }

    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_start' => 'required|date',
            'tanggal_end' => 'required|date|after_or_equal:tanggal_start',
            'waktu_start' => 'required',
            'waktu_end' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tanggal_start === $request->tanggal_end && $value <= $request->waktu_start) {
                        $fail('Waktu selesai harus setelah waktu mulai jika tanggal sama.');
                    }
                }
            ],
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.string' => 'Judul harus berupa teks.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'kategori.required' => 'Kategori wajib diisi.',
            'kategori.string' => 'Kategori harus berupa teks.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',

            'tanggal_start.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_start.date' => 'Tanggal mulai harus berupa tanggal yang valid.',

            'tanggal_end.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_end.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'tanggal_end.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'waktu_start.required' => 'Waktu mulai wajib diisi.',

            'waktu_end.required' => 'Waktu selesai wajib diisi.',
        ]);

        // Cari jadwal berdasarkan ID
        $jadwal = Jadwal::findOrFail($id);

        // Update data jadwal
        $jadwal->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'tanggal_start' => $request->tanggal_start,
            'tanggal_end' => $request->tanggal_end,
            'waktu_start' => $request->waktu_start,
            'waktu_end' => $request->waktu_end,
        ]);

        return redirect()->route('admin.jadwal')->with('successEditJadwal', 'Jadwal berhasil diperbarui!');
    }

    public function deleteJadwal($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Hapus jadwal dari database
        $jadwal->delete();

        return redirect()->route('admin.jadwal')->with('successDeleteJadwal', 'Jadwal berhasil dihapus!');
    }
}
