<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DataVerifikasiCPB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ImageVerifikasiController extends Controller
{
    public function showImageVerif(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $query = DataVerifikasiCPB::query();
        if ($perPage == "all") {
            $dataFotoVerif = $query->get(); 
        } else {
            $dataFotoVerif = $query->paginate($perPage)->appends($request->query());
        }

        return view('screen_admin.image_verifikasi.image_verifikasi', compact('dataFotoVerif', 'perPage'));
    }

    public function downloadFolder(Request $request)
    {
        $nik = $request->input('nik');
        $folderPath = public_path("up/verifikasi/$nik");

        if (!File::exists($folderPath)) {
            return back()->with('error', 'Folder tidak ditemukan.');
        }

        $zipFileName = "foto_verifikasi_$nik.zip";
        $zipFilePath = public_path($zipFileName);

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $files = File::files($folderPath);
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }
            $zip->close();
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
