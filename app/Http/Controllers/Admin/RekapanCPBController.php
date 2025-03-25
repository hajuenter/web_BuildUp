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
        $query = DataCPB::query();

        $perPage = $request->input('perPage', 5);

        if ($perPage == "all") {
            $dataCPB = $query->get();
        } else {
            $dataCPB = $query->paginate($perPage);
            $dataCPB->appends(request()->except(['page']));
        }

        return view('screen_admin.rekapan.rekapan_cpb', compact('dataCPB', 'perPage'));
    }

    public function downloadCpbPdf(Request $request)
    {
        $data = DataCPB::all();

        $pdf = Pdf::loadView('exports.rekap_cpb_pdf', compact('data'))
            ->setPaper('a4', 'potrait');

        $filename = 'Rekap_CPB_' .
            ($request->start_date ? $request->start_date : 'Awal') .
            '_to_' .
            ($request->end_date ? $request->end_date : 'Akhir') .
            '.pdf';

        return $pdf->download($filename);
    }

    public function downloadCpbExcel(Request $request)
    {
        $data = DataCPB::all();

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
        $filename = 'Rekap_CPB_' .
            ($request->start_date ? $request->start_date : 'Awal') .
            '_to_' .
            ($request->end_date ? $request->end_date : 'Akhir') .
            '.xlsx';

        $writer = new Xlsx($spreadsheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function downloadCpbWord(Request $request)
    {
        $data = DataCPB::all();

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

        $filename = 'Rekap_CPB_' .
            ($request->start_date ? $request->start_date : 'Awal') .
            '_to_' .
            ($request->end_date ? $request->end_date : 'Akhir') .
            '.docx';

        $path = storage_path($filename);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
}
