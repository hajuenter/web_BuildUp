<?php

namespace App\Http\Controllers\Admin;

use App\Models\DataCPB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Http\Controllers\Controller;

class RekapanCPBController extends Controller
{
    public function showRekapCPB(Request $request)
    {
        $perPageAll = $request->get('perPageAll', 5);
        $perPageTrue = $request->get('perPageTrue', 5);
        $perPageFalse = $request->get('perPageFalse', 5);
        $perPageVerif = $request->get('perPageverif', 5);
        $perPageUnverif = $request->get('perPageUnverif', 5);

        // Semua Data CPB
        $queryAll = DataCPB::query();
        $dataCPB = ($perPageAll == "all")
            ? $queryAll->get()
            : $queryAll->paginate($perPageAll)->appends(['perPageAll' => $perPageAll]);

        // Data yang Sudah Dicek
        $queryTrue = DataCPB::where('pengecekan', 'Sudah Dicek');
        $dataCekTrue = ($perPageTrue == "all")
            ? $queryTrue->get()
            : $queryTrue->paginate($perPageTrue)->appends(['perPageTrue' => $perPageTrue]);

        // Data yang "Belum Dicek"
        $queryFalse = DataCPB::where('pengecekan', 'Belum Dicek');
        $dataCekFalse = ($perPageFalse == "all")
            ? $queryFalse->get()
            : $queryFalse->paginate($perPageFalse)->appends(['perPageFalse' => $perPageFalse]);

        $queryVerif = DataCPB::where('status', 'Terverifikasi');
        $dataCekVerif = ($perPageVerif == "all")
            ? $queryVerif->get()
            : $queryVerif->paginate($perPageVerif)->appends(['perPageverif' => $perPageVerif]);

        $queryUnverif = DataCPB::where('status', 'Tidak Terverifikasi');
        $dataCekUnverif = ($perPageUnverif == "all")
            ? $queryUnverif->get()
            : $queryUnverif->paginate($perPageUnverif)->appends(['perPageUnverif' => $perPageUnverif]);

        return view('screen_admin.rekapan.rekapan_cpb', compact('dataCPB', 'dataCekTrue', 'dataCekFalse', 'dataCekVerif', 'dataCekUnverif', 'perPageVerif', 'perPageUnverif', 'perPageAll', 'perPageTrue', 'perPageFalse'));
    }

    public function downloadCpbPdf(Request $request)
    {
        $status = $request->status;

        $data = DataCPB::when($status == 'checked', function ($query) {
            return $query->where('pengecekan', 'Sudah Dicek');
        })
            ->when($status == 'unchecked', function ($query) {
                return $query->where('pengecekan', 'Belum Dicek');
            })
            ->when($status == 'verif', function ($query) {
                return $query->where('status', 'Terverifikasi');
            })
            ->when($status == 'unverif', function ($query) {
                return $query->where('status', 'Tidak Terverifikasi');
            })
            ->get();

        $pdf = Pdf::loadView('exports.rekap_cpb_pdf', compact('data'))
            ->setPaper('a4', 'potrait');

        $statusText = match ($status) {
            'checked' => 'Sudah_Dicek',
            'unchecked' => 'Belum_Dicek',
            'verif' => 'Verifikasi',
            'unverif' => 'Unverifikasi',
            default => 'Semua_Data',
        };

        $filename = "Rekap_CPB_{$statusText}.pdf";

        return $pdf->download($filename);
    }

    public function downloadCpbExcel(Request $request)
    {
        $status = $request->status;

        $data = DataCPB::when($status == 'checked', function ($query) {
            return $query->where('pengecekan', 'Sudah Dicek');
        })
            ->when($status == 'unchecked', function ($query) {
                return $query->where('pengecekan', 'Belum Dicek');
            })
            ->when($status == 'verif', function ($query) {
                return $query->where('status', 'Terverifikasi');
            })
            ->when($status == 'unverif', function ($query) {
                return $query->where('status', 'Tidak Terverifikasi');
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Merge cell untuk judul
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'DAFTAR REKAPITULASI USULAN BANTUAN SOSIAL');
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'PENYEDIAAN RUMAH LAYAK HUNI');

        // Format judul
        $styleTitle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:A2')->applyFromArray($styleTitle);

        // Info Desa & Kecamatan
        $sheet->setCellValue('A4', 'DESA');
        $sheet->setCellValue('B4', ': ……………………………..');

        $sheet->setCellValue('A5', 'KECAMATAN');
        $sheet->setCellValue('B5', ': ……………………………..');

        // Header tabel
        $headerRow = 7;
        $sheet->setCellValue('A' . $headerRow, 'No')
            ->setCellValue('B' . $headerRow, 'Nama')
            ->setCellValue('C' . $headerRow, 'NIK')
            ->setCellValue('D' . $headerRow, 'No KK')
            ->setCellValue('E' . $headerRow, 'Pekerjaan')
            ->setCellValue('F' . $headerRow, 'Alamat');

        // Styling header
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->applyFromArray($styleHeader);

        // Data tabel
        $row = 8;
        foreach ($data as $index => $cpb) {
            $sheet->setCellValue('A' . $row, $index + 1)
                ->setCellValue('B' . $row, $cpb->nama)
                ->setCellValueExplicit('C' . $row, $cpb->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValueExplicit('D' . $row, $cpb->no_kk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                ->setCellValue('E' . $row, $cpb->pekerjaan)
                ->setCellValue('F' . $row, $cpb->alamat);

            // Tambahkan border pada setiap baris
            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]);

            $row++;
        }

        // Tambahkan tanda tangan Kepala Desa
        $signatureRow = $row + 3;
        $sheet->mergeCells('D' . $signatureRow . ':F' . $signatureRow);
        $sheet->setCellValue('D' . $signatureRow, 'Kepala Desa');
        $sheet->mergeCells('E' . ($signatureRow + 4) . ':F' . ($signatureRow + 4));
        $sheet->setCellValue('E' . ($signatureRow + 4), '……………………………..');

        // Format tanda tangan
        $styleSignature = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'indent' => 1
            ],
            'font' => ['bold' => true]
        ];

        $sheet->getStyle('C' . $signatureRow . ':E' . ($signatureRow + 4))->applyFromArray($styleSignature);
        // Set auto-size untuk kolom
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Simpan dan download file
        $statusText = match ($status) {
            'checked' => 'Sudah_Dicek',
            'unchecked' => 'Belum_Dicek',
            'verif' => 'Verifikasi',
            'unverif' => 'Unverifikasi',
            default => 'Semua_Data',
        };

        $filename = "Rekap_CPB_{$statusText}.xlsx";

        $writer = new Xlsx($spreadsheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function downloadCpbWord(Request $request)
    {
        $status = $request->status;

        $data = DataCPB::when($status == 'checked', function ($query) {
            return $query->where('pengecekan', 'Sudah Dicek');
        })
            ->when($status == 'unchecked', function ($query) {
                return $query->where('pengecekan', 'Belum Dicek');
            })
            ->when($status == 'verif', function ($query) {
                return $query->where('status', 'Terverifikasi');
            })
            ->when($status == 'unverif', function ($query) {
                return $query->where('status', 'Tidak Terverifikasi');
            })
            ->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Title
        $section->addText('DAFTAR REKAPITULASI USULAN BANTUAN SOSIAL', ['bold' => true, 'size' => 12], ['align' => 'center']);
        $section->addText('PENYEDIAAN RUMAH LAYAK HUNI', ['bold' => true, 'size' => 12], ['align' => 'center']);

        $infoTable = $section->addTable();
        $infoTable->addRow();
        $infoTable->addCell(2000)->addText('DESA', ['size' => 12]);
        $infoTable->addCell(300)->addText(':');
        $infoTable->addCell(5000)->addText('……………………………..');

        $infoTable->addRow();
        $infoTable->addCell(2000)->addText('KECAMATAN', ['size' => 12]);
        $infoTable->addCell(300)->addText(':');
        $infoTable->addCell(5000)->addText('……………………………..');

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
        ];
        $table = $section->addTable($tableStyle);

        $table->addRow();
        $table->addCell(500)->addText('No', ['bold' => true]);
        $table->addCell(2000)->addText('Nama', ['bold' => true]);
        $table->addCell(2000)->addText('NIK', ['bold' => true]);
        $table->addCell(2000)->addText('KK', ['bold' => true]);
        $table->addCell(2000)->addText('Pekerjaan', ['bold' => true]);
        $table->addCell(3000)->addText('Alamat', ['bold' => true]);

        // Isi tabel
        $no = 1;
        foreach ($data as $cpb) {
            $table->addRow();
            $table->addCell(500)->addText($no++);
            $table->addCell(2000)->addText($cpb->nama);
            $table->addCell(2000)->addText($cpb->nik);
            $table->addCell(2000)->addText($cpb->no_kk);
            $table->addCell(2000)->addText($cpb->pekerjaan);
            $table->addCell(3000)->addText($cpb->alamat);
        }

        $section->addTextBreak(1);

        // Tambahkan bagian Kepala Desa
        $section->addText('Kepala Desa', ['bold' => true], ['alignment' => 'right']);
        $section->addTextBreak(3); // Beri jarak untuk tanda tangan
        $section->addText('…………………………….', ['bold' => true], ['alignment' => 'right']);

        $statusText = match ($status) {
            'checked' => 'Sudah_Dicek',
            'unchecked' => 'Belum_Dicek',
            'verif' => 'Verifikasi',
            'unverif' => 'Unverifikasi',
            default => 'Semua_Data',
        };

        $filename = "Rekap_CPB_{$statusText}.docx";

        $path = storage_path($filename);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
}
