<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;
use App\Models\DataVerifikasiCPB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapanVerifikasiController extends Controller
{
    public function showRekapVerif(Request $request)
    {
        $perPageAll = $request->get('perPageAll', 5);
        $perPageTrue = $request->get('perPageTrue', 5);
        $perPageFalse = $request->get('perPageFalse', 5);
        $filterDesa = $request->get('desa');

        $queryAll = DataVerifikasiCPB::with('cpb');
        $queryTrue = DataVerifikasiCPB::with('cpb')->where('nilai_bantuan', '>', 0);
        $queryFalse = DataVerifikasiCPB::with('cpb')->where('nilai_bantuan', 0);

        // FILTER DESA
        if ($filterDesa && $filterDesa !== 'all') {
            $queryAll->whereHas('cpb', function ($query) use ($filterDesa) {
                $query->where('alamat', 'like', "%$filterDesa%");
            });

            $queryTrue->whereHas('cpb', function ($query) use ($filterDesa) {
                $query->where('alamat', 'like', "%$filterDesa%");
            });

            $queryFalse->whereHas('cpb', function ($query) use ($filterDesa) {
                $query->where('alamat', 'like', "%$filterDesa%");
            });
        }

        // PAGINATION
        $dataVerifCPB = ($perPageAll == "all")
            ? $queryAll->get()
            : $queryAll->paginate($perPageAll)->appends(['perPageAll' => $perPageAll, 'desa' => $filterDesa]);

        $dataCekTrue = ($perPageTrue == "all")
            ? $queryTrue->get()
            : $queryTrue->paginate($perPageTrue)->appends(['perPageTrue' => $perPageTrue, 'desa' => $filterDesa]);

        $dataCekFalse = ($perPageFalse == "all")
            ? $queryFalse->get()
            : $queryFalse->paginate($perPageFalse)->appends(['perPageFalse' => $perPageFalse, 'desa' => $filterDesa]);

        // Generate List Desa dari relasi cpb
        $desaList = DataVerifikasiCPB::with('cpb')->get()->map(function ($item) {
            $alamatParts = explode(';', $item->cpb->alamat ?? '');
            return Str::ucfirst(Str::lower(trim($alamatParts[1] ?? 'Tidak Diketahui')));
        })->unique()->filter()->sort()->values()->all();

        return view('screen_admin.rekapan.rekapan_verif', compact(
            'dataVerifCPB',
            'dataCekTrue',
            'dataCekFalse',
            'perPageAll',
            'perPageTrue',
            'perPageFalse',
            'desaList',
            'filterDesa'
        ));
    }


    public function downloadVerifikasiPdf(Request $request)
    {
        $status = $request->status;
        $desa = $request->desa;
        $data = DataVerifikasiCPB::when($status == 'checked', function ($query) {
            return $query->where('nilai_bantuan', '>', 0);
        })
            ->when($status == 'unchecked', function ($query) {
                return $query->where('nilai_bantuan', 0);
            })
            ->when($desa && $desa !== 'all', function ($query) use ($desa) {
                return $query->whereHas('cpb', function ($q) use ($desa) {
                    $q->where('alamat', 'like', "%$desa%");
                });
            })
            ->get();
        $pdf = Pdf::loadView('exports.rekap_verif_cpb_pdf', compact('data'))
            ->setPaper('a4', 'landscape');

        $statusText = match ($status) {
            'checked' => 'Mendapatkan_Bantuan',
            'unchecked' => 'Tidak_Mendapatkan_Bantuan',
            default => 'Semua_Data',
        };


        $filename = "Rekap_Verifikasi_CPB_{$statusText}.pdf";

        return $pdf->download($filename);
    }

    public function downloadVerifikasiExcel(Request $request)
    {
        $status = $request->status;
        $desa = $request->desa;
        $data = DataVerifikasiCPB::when($status == 'checked', function ($query) {
            return $query->where('nilai_bantuan', '>', 0);
        })
            ->when($status == 'unchecked', function ($query) {
                return $query->where('nilai_bantuan', 0);
            })
            ->when($desa && $desa !== 'all', function ($query) use ($desa) {
                return $query->whereHas('cpb', function ($q) use ($desa) {
                    $q->where('alamat', 'like', "%$desa%");
                });
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Judul
        $sheet->mergeCells('A1:T1');
        $sheet->setCellValue('A1', 'DAFTAR REKAPITULASI VERIFIKASI HASIL USULAN BANTUAN SOSIAL');
        $sheet->mergeCells('A2:T2');
        $sheet->setCellValue('A2', 'PENYEDIAAN RUMAH LAYAK HUNI');

        // Format judul
        $styleTitle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:A2')->applyFromArray($styleTitle);

        // Info Desa & Kecamatan
        $sheet->setCellValue('A4', 'DESA');
        $sheet->setCellValue('B4', ': ……………………………..');
        $sheet->setCellValue('A5', 'KECAMATAN');
        $sheet->setCellValue('B5', ': ……………………………..');

        // Header
        $headerRow = 7;
        $headers = ['No', 'NIK', 'Penutup Atap', 'Rangka Atap', 'Kolom', 'Ring Balok', 'Kusen', 'Pintu', 'Jendela', 'Struktur Bawah', 'Penutup Lantai', 'Pondasi', 'Sloof', 'Sanitasi', 'Air Bersih', 'Kesanggupan Berswadaya', 'Tipe', 'Penilaian Kerusakan', 'Nilai Bantuan', 'Catatan'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $col++;
        }

        // Format header
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A' . $headerRow . ':T' . $headerRow)->applyFromArray($styleHeader);

        // Data
        $row = 8;
        foreach ($data as $index => $cpb) {
            $sheet->setCellValue('A' . $row, $index + 1)
                ->setCellValueExplicit('B' . $row, $cpb->nik, DataType::TYPE_STRING)
                ->setCellValue('C' . $row, $cpb->penutup_atap)
                ->setCellValue('D' . $row, $cpb->rangka_atap)
                ->setCellValue('E' . $row, $cpb->kolom)
                ->setCellValue('F' . $row, $cpb->ring_balok)
                ->setCellValue('G' . $row, $cpb->kusen)
                ->setCellValue('H' . $row, $cpb->pintu)
                ->setCellValue('I' . $row, $cpb->jendela)
                ->setCellValue('J' . $row, $cpb->struktur_bawah)
                ->setCellValue('K' . $row, $cpb->penutup_lantai)
                ->setCellValue('L' . $row, $cpb->pondasi)
                ->setCellValue('M' . $row, $cpb->sloof)
                ->setCellValue('N' . $row, $cpb->mck)
                ->setCellValue('O' . $row, $cpb->air_kotor)
                ->setCellValue('P' . $row, $cpb->kesanggupan_berswadaya)
                ->setCellValue('Q' . $row, $cpb->tipe)
                ->setCellValue('R' . $row, $cpb->penilaian_kerusakan)
                ->setCellValue('S' . $row, $cpb->nilai_bantuan)
                ->setCellValue('T' . $row, $cpb->catatan);

            // Border setiap baris
            $sheet->getStyle('A' . $row . ':T' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            $row++;
        }

        $signatureRow = $row + 3;

        // Tim Verifikator
        $sheet->mergeCells('N' . $signatureRow . ':P' . $signatureRow);
        $sheet->setCellValue('N' . $signatureRow, 'Tim Verifikator');
        $sheet->mergeCells('N' . ($signatureRow + 4) . ':P' . ($signatureRow + 4));
        $sheet->setCellValue('N' . ($signatureRow + 4), '……………………………..');

        // Kepala Desa
        $sheet->mergeCells('R' . $signatureRow . ':T' . $signatureRow);
        $sheet->setCellValue('R' . $signatureRow, 'Kepala Desa');
        $sheet->mergeCells('R' . ($signatureRow + 4) . ':T' . ($signatureRow + 4));
        $sheet->setCellValue('R' . ($signatureRow + 4), '……………………………..');

        // Format tanda tangan
        $styleSignature = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font' => ['bold' => true]
        ];
        $sheet->getStyle('N' . $signatureRow . ':P' . ($signatureRow + 4))->applyFromArray($styleSignature);
        $sheet->getStyle('R' . $signatureRow . ':T' . ($signatureRow + 4))->applyFromArray($styleSignature);

        // Set auto-size untuk semua kolom
        foreach (range('A', 'T') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $statusText = match ($status) {
            'checked' => 'Mendapatkan_Bantuan',
            'unchecked' => 'Tidak_Mendapatkan_Bantuan',
            default => 'Semua_Data',
        };

        $filename = "Rekap_Verifikasi_CPB_{$statusText}.xlsx";

        $writer = new Xlsx($spreadsheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function downloadVerifikasiWord(Request $request)
    {
        $status = $request->status;
        $desa = $request->desa;
        $data = DataVerifikasiCPB::when($status == 'checked', function ($query) {
            return $query->where('nilai_bantuan', '>', 0);
        })
            ->when($status == 'unchecked', function ($query) {
                return $query->where('nilai_bantuan', 0);
            })
            ->when($desa && $desa !== 'all', function ($query) use ($desa) {
                return $query->whereHas('cpb', function ($q) use ($desa) {
                    $q->where('alamat', 'like', "%$desa%");
                });
            })
            ->get();

        $phpWord = new PhpWord();

        // Configure page setup for landscape orientation
        $section = $phpWord->addSection([
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(11.69),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.27),
            'orientation' => 'landscape',
            'marginLeft' => 400,
            'marginRight' => 400,
            'marginTop' => 400,
            'marginBottom' => 400
        ]);

        // Add document title
        $section->addText('DAFTAR REKAPITULASI VERIFIKASI HASIL USULAN BANTUAN SOSIAL', ['bold' => true, 'size' => 12], ['alignment' => 'center']);
        $section->addText('PENYEDIAAN RUMAH LAYAK HUNI', ['bold' => true, 'size' => 12], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Add info table
        $infoTable = $section->addTable();
        $infoTable->addRow();
        $infoTable->addCell(2000)->addText('DESA', ['size' => 10]);
        $infoTable->addCell(300)->addText(':');
        $infoTable->addCell(5000)->addText('……………………………..');

        $infoTable->addRow();
        $infoTable->addCell(2000)->addText('KECAMATAN', ['size' => 10]);
        $infoTable->addCell(300)->addText(':');
        $infoTable->addCell(5000)->addText('……………………………..');
        $section->addTextBreak(1);

        // Create table with simpler style definition
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 20
        ];

        $table = $section->addTable($tableStyle);
        $table->addRow();

        $headers = ['No', 'NIK', 'Penutup Atap', 'Rangka Atap', 'Kolom', 'Ring Balok', 'Dinding Pengisi', 'Kusen', 'Pintu', 'Jendela', 'Struktur Bawah', 'Penutup Lantai', 'Pondasi', 'Sloof', 'Sanitasi', 'Air Bersih', 'Kesanggupan Berswadaya', 'Tipe', 'Penilaian Kerusakan', 'Nilai Bantuan', 'Catatan'];

        $headerWidths = [
            500,   // No
            1800,  // NIK
            1200,  // Penutup Atap
            1200,  // Rangka Atap
            1000,  // Kolom
            1200,  // Ring Balok
            1400,  // Dinding Pengisi
            1000,  // Kusen
            1000,  // Pintu
            1000,  // Jendela
            1400,  // Struktur Bawah
            1400,  // Penutup Lantai
            1000,  // Pondasi
            1000,  // Sloof
            1000,  // MCK
            1000,  // Air Kotor
            1800,  // Kesanggupan Berswadaya
            800,   // Tipe
            1400,  // Penilaian Kerusakan
            1200,  // Nilai Bantuan
            1500   // Catatan
        ];

        foreach ($headers as $index => $header) {
            $cell = $table->addCell($headerWidths[$index], ['borderSize' => 6, 'valign' => 'center']);
            $cell->addText($header, ['bold' => true, 'size' => 7], ['alignment' => 'center']);
        }

        foreach ($data as $index => $cpb) {
            $table->addRow();
            $table->addCell($headerWidths[0], ['valign' => 'center'])->addText($index + 1, ['size' => 7], ['alignment' => 'center']);
            $table->addCell($headerWidths[1], ['valign' => 'center'])->addText($cpb->nik, ['size' => 7]);
            $table->addCell($headerWidths[2], ['valign' => 'center'])->addText($cpb->penutup_atap, ['size' => 7]);
            $table->addCell($headerWidths[3], ['valign' => 'center'])->addText($cpb->rangka_atap, ['size' => 7]);
            $table->addCell($headerWidths[4], ['valign' => 'center'])->addText($cpb->kolom, ['size' => 7]);
            $table->addCell($headerWidths[5], ['valign' => 'center'])->addText($cpb->ring_balok, ['size' => 7]);
            $table->addCell($headerWidths[6], ['valign' => 'center'])->addText($cpb->dinding_pengisi, ['size' => 7]);
            $table->addCell($headerWidths[7], ['valign' => 'center'])->addText($cpb->kusen, ['size' => 7]);
            $table->addCell($headerWidths[8], ['valign' => 'center'])->addText($cpb->pintu, ['size' => 7]);
            $table->addCell($headerWidths[9], ['valign' => 'center'])->addText($cpb->jendela, ['size' => 7]);
            $table->addCell($headerWidths[10], ['valign' => 'center'])->addText($cpb->struktur_bawah, ['size' => 7]);
            $table->addCell($headerWidths[11], ['valign' => 'center'])->addText($cpb->penutup_lantai, ['size' => 7]);
            $table->addCell($headerWidths[12], ['valign' => 'center'])->addText($cpb->pondasi, ['size' => 7]);
            $table->addCell($headerWidths[13], ['valign' => 'center'])->addText($cpb->sloof, ['size' => 7]);
            $table->addCell($headerWidths[14], ['valign' => 'center'])->addText($cpb->mck, ['size' => 7]);
            $table->addCell($headerWidths[15], ['valign' => 'center'])->addText($cpb->air_kotor, ['size' => 7]);
            $table->addCell($headerWidths[16], ['valign' => 'center'])->addText($cpb->kesanggupan_berswadaya, ['size' => 7]);
            $table->addCell($headerWidths[17], ['valign' => 'center'])->addText($cpb->tipe, ['size' => 7]);
            $table->addCell($headerWidths[18], ['valign' => 'center'])->addText($cpb->penilaian_kerusakan, ['size' => 7]);
            $table->addCell($headerWidths[19], ['valign' => 'center'])->addText($cpb->nilai_bantuan, ['size' => 7]);
            $table->addCell($headerWidths[20], ['valign' => 'center'])->addText($cpb->catatan, ['size' => 7]);
        }

        // Add signature section
        $section->addTextBreak(2);

        // Buat tabel tanda tangan
        $signTable = $section->addTable();
        $signTable->addRow();

        $cell1 = $signTable->addCell(5000);
        $cell1->addText('Tim Verifikator', ['bold' => true, 'size' => 12], ['alignment' => 'center']);
        $signTable->addCell(5000)->addText('Kepala Desa', ['bold' => true, 'size' => 12], ['alignment' => 'center']);

        $signTable->addRow();
        $signTable->addCell(5000)->addTextBreak(3);
        $signTable->addCell(5000)->addTextBreak(3);

        $signTable->addRow();
        $signTable->addCell(5000)->addText('……………………………..', ['size' => 12], ['alignment' => 'center']);
        $signTable->addCell(5000)->addText('……………………………..', ['size' => 12], ['alignment' => 'center']);

        $statusText = match ($status) {
            'checked' => 'Mendapatkan_Bantuan',
            'unchecked' => 'Tidak_Mendapatkan_Bantuan',
            default => 'Semua_Data',
        };

        $filename = "Rekap_Verifikasi_CPB_{$statusText}.docx";
        $path = storage_path($filename);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
}
