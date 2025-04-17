<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataCPB;
use Illuminate\Http\Request;

class ApiHomeController extends Controller
{
    public function index()
    {
        $dataProsesVerifikasi = DataCPB::where('pengecekan', 'Belum Dicek')->count();
        $dataTerverifikasi = DataCPB::where('status', 'Terverifikasi')->count();
        $dataMasihLayakHuni = DataCPB::where('status', 'Tidak Terverifikasi')->count();
        $dataPengajuan = DataCPB::count();

        return response()->json([
            'status' => true,
            'message' => 'Statistik data CPB berhasil diambil.',
            'data' => [
                'proses_verifikasi' => $dataProsesVerifikasi,
                'terverifikasi' => $dataTerverifikasi,
                'masih_layak_huni' => $dataMasihLayakHuni,
                'pengajuan' => $dataPengajuan,
            ]
        ]);
    }
}
