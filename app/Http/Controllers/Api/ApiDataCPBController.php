<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Data_CPB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiDataCPBController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Data_CPB::orderBy('id', 'asc')->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'nama'          => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'umur'          => 'required|integer|min:1|max:500',
                'nik'           => 'required|string|size:16|unique:data_cpb,nik',
                'no_kk'         => 'required|string|size:16|unique:data_cpb,no_kk',
                'alamat'        => 'required|string',
            ],
            [
                'nama.required'         => 'Nama harus diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
                'umur.required'         => 'Umur harus diisi.',
                'nik.required'          => 'NIK harus diisi.',
                'no_kk.required'        => 'Nomor KK harus diisi.',
                'alamat.required'       => 'Alamat harus diisi.',
                'nik.unique'            => 'NIK sudah terdaftar.',
                'no_kk.unique'          => 'Nomor KK sudah terdaftar.',
                'jenis_kelamin.in'      => 'Jenis kelamin harus Laki-laki atau Perempuan.',
                'umur.min'              => 'Umur harus lebih dari 0.',
                'umur.max'              => 'Umur tidak boleh lebih dari 500.',
                'nik.size'              => 'NIK harus terdiri dari 16 karakter.',
                'no_kk.size'            => 'Nomor KK harus terdiri dari 16 karakter.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = Data_CPB::create([
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur'          => $request->umur,
            'nik'           => $request->nik,
            'no_kk'         => $request->no_kk,
            'alamat'        => $request->alamat,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil disimpan',
            'data'    => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Data_CPB::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama'          => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'umur'          => 'required|integer|min:1|max:500',
                'nik'           => 'required|string|size:16|unique:data_cpb,nik,' . $id,
                'no_kk'         => 'required|string|size:16|unique:data_cpb,no_kk,' . $id,
                'alamat'        => 'required|string',
            ],
            [
                'nama.required'         => 'Nama harus diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
                'umur.required'         => 'Umur harus diisi.',
                'nik.required'          => 'NIK harus diisi.',
                'no_kk.required'        => 'Nomor KK harus diisi.',
                'alamat.required'       => 'Alamat harus diisi.',
                'nik.unique'            => 'NIK sudah terdaftar.',
                'no_kk.unique'          => 'Nomor KK sudah terdaftar.',
                'jenis_kelamin.in'      => 'Jenis kelamin harus Laki-laki atau Perempuan.',
                'umur.min'              => 'Umur harus lebih dari 0.',
                'umur.max'              => 'Umur tidak boleh lebih dari 500.',
                'nik.size'              => 'NIK harus terdiri dari 16 karakter.',
                'no_kk.size'            => 'Nomor KK harus terdiri dari 16 karakter.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = Data_CPB::findOrFail($id);

        $data->update([
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur'          => $request->umur,
            'nik'           => $request->nik,
            'no_kk'         => $request->no_kk,
            'alamat'        => $request->alamat,
        ]);

        // Mengembalikan response JSON setelah data diperbarui
        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil diperbarui',
            'data'    => $data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Data_CPB::findOrFail($id);

            // Menghapus data
            $data->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data dihapus'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
}
