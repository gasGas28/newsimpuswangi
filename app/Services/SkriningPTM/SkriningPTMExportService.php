<?php

namespace App\Services\SkriningPTM;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SkriningPTMExportService
{
    protected RegisterPasienPTMService $registerService;

    public function __construct(RegisterPasienPTMService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function export(string $nik): Spreadsheet
    {
        $data = $this->registerService->getRegister($nik);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Skrining PTM');

        // Header kolom - sesuaikan dengan field yang benar-benar mau ditampilkan
        $headers = [
            'NIK',
            'Nama Pasien',
            'Tanggal Kunjungan',
            'ID Skrining',
            'Berat Badan',
            'Tinggi Badan',
            'IMT',
            'Tekanan Darah Sistolik',
            'Tekanan Darah Diastolik',
        ];

        $sheet->fromArray($headers, null, 'A1');

        // Style header
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E1F2');
        $sheet->getStyle('A1:I1')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Isi data (sesuaikan nama field dengan kolom di tabel kamu)
        $row = 2;
        foreach ($data as $item) {
            $sheet->fromArray([
                $item->NIK ?? $item->nik_pasien,
                $item->nama ?? '-',
                $item->tanggal_kunjungan ?? '-',
                $item->idSkrining,
                $item->berat_badan ?? '-',
                $item->tinggi_badan ?? '-',
                $item->imt ?? '-',
                $item->sistolik ?? '-',
                $item->diastolik ?? '-',
            ], null, 'A' . $row);
            $row++;
        }

        // Auto-size kolom
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }
}