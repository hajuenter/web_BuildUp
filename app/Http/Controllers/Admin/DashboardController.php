<?php

namespace App\Http\Controllers\Admin;

use App\Models\Berita;
use App\Models\Jadwal;
use App\Models\DataCPB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function showDashboardAdmin(Request $request)
    {
        $filterCPB = $request->query('filter_cpb', 'tahun');
        $filterBerita = $request->query('filter_berita', 'tahun');
        $filterJadwal = $request->query('filter_jadwal', 'tahun');
        $filterLaporan = $request->query('filter_laporan', 'tahun');

        // Query Data CPB
        $queryCPB = DataCPB::query();
        if ($filterCPB === 'hari') {
            $queryCPB->whereDate('created_at', now()->toDateString());
        } elseif ($filterCPB === 'bulan') {
            $queryCPB->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } else {
            $queryCPB->whereYear('created_at', now()->year);
        }
        $jumlahCPB = $queryCPB->count();
        $totalCPB = DataCPB::count();

        // Query Data Berita
        $queryBerita = Berita::query();
        if ($filterBerita === 'hari') {
            $queryBerita->whereDate('created_at', now()->toDateString());
        } elseif ($filterBerita === 'bulan') {
            $queryBerita->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } else {
            $queryBerita->whereYear('created_at', now()->year);
        }
        $jumlahBerita = $queryBerita->count();
        $totalBerita = Berita::count();

        // Query Data Jadwal
        $queryJadwal = Jadwal::query();
        if ($filterJadwal === 'hari') {
            $queryJadwal->whereDate('created_at', now()->toDateString());
        } elseif ($filterJadwal === 'bulan') {
            $queryJadwal->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } else {
            $queryJadwal->whereYear('created_at', now()->year);
        }
        $jumlahJadwal = $queryJadwal->count();
        $totalJadwal = Jadwal::count();

        // Query Data Laporan
        $queryLaporanCPB = DataCPB::query();
        $queryLaporanBerita = Berita::query();
        $queryLaporanJadwal = Jadwal::query();

        if ($filterLaporan === 'hari') {
            $queryLaporanCPB->whereDate('created_at', now()->toDateString());
            $queryLaporanBerita->whereDate('created_at', now()->toDateString());
            $queryLaporanJadwal->whereDate('created_at', now()->toDateString());
        } elseif ($filterLaporan === 'bulan') {
            $queryLaporanCPB->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $queryLaporanBerita->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $queryLaporanJadwal->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } else {
            $queryLaporanCPB->whereYear('created_at', now()->year);
            $queryLaporanBerita->whereYear('created_at', now()->year);
            $queryLaporanJadwal->whereYear('created_at', now()->year);
        }

        // Data untuk laporan
        $dataLaporan = [
            'cpb' => $queryLaporanCPB->count(),
            'berita' => $queryLaporanBerita->count(),
            'jadwal' => $queryLaporanJadwal->count(),
        ];

        return view('screen_admin.dashboard.dashboard', compact(
            'jumlahCPB',
            'totalCPB',
            'jumlahBerita',
            'totalBerita',
            'jumlahJadwal',
            'totalJadwal',
            'filterCPB',
            'filterBerita',
            'filterJadwal',
            'filterLaporan',
            'dataLaporan'
        ));
    }
}
