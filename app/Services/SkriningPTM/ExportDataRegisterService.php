<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportDataRegisterService


{
    protected array $monthNames = [
        1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
        5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
        9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER',
    ];

    /**
     * Bangun 1 sheet register kohort.
     *
     * @param Spreadsheet $spreadsheet
     * @param int         $sheetIndex     0 = sheet pertama (pakai active sheet bawaan), >0 = buat sheet baru
     * @param string      $sheetName      Nama tab sheet, mis. "Hipertensi"
     * @param string      $judul          Judul di baris 2, mis. "REGISTER PELAYANAN HIPERTENSI ... TAHUN 2025"
     * @param string      $vitalLabel     Label baris ke-2 tiap blok, mis. "TENSI" atau "GDS/GDP"
     * @param Collection  $groupedPatients Collection hasil groupBy('nik'), tiap item = collection of visit rows
     */
    public function buildSheet(
        Spreadsheet $spreadsheet,
        int $sheetIndex,
        string $sheetName,
        string $judul,
        string $vitalLabel,
        Collection $groupedPatients
    ): void {
        if ($sheetIndex === 0) {
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->createSheet();
        }
        $sheet->setTitle($sheetName);

        // ===== Judul =====
        $sheet->setCellValue('B2', $judul);
        $sheet->mergeCells('B2:AR2');
        $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(24);

        // ===== Header baris 4 =====
        $headerRow = 4;
        $staticHeaders = ['NO', 'NAMA PENDERITA', 'NIK', 'TGL LAHIR ', 'JENIS KELAMIN', 'ALAMAT', 'KET *'];
        $col = 2; // kolom B
        foreach ($staticHeaders as $h) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue("{$colLetter}{$headerRow}", $h);
            $col++;
        }

        $col = 9; // kolom I
        foreach ($this->monthNames as $name) {
            $startLetter = Coordinate::stringFromColumnIndex($col);
            $endLetter = Coordinate::stringFromColumnIndex($col + 2);
            $sheet->setCellValue("{$startLetter}{$headerRow}", $name);
            $sheet->mergeCells("{$startLetter}{$headerRow}:{$endLetter}{$headerRow}");
            $col += 3;
        }

        $sheet->getStyle('B4:AR4')->getFont()->setBold(true);
        $sheet->getStyle('B4:AR4')->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
        $sheet->getStyle('B4:AR4')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        // ===== Body: 4 baris per pasien =====
        $currentRow = 5;
        $no = 1;

        foreach ($groupedPatients as $nik => $visits) {
            $first = $visits->first();
            $blockStart = $currentRow;
            $blockEnd = $blockStart + 3;

            // Data pasien (merge vertikal 4 baris)
            $sheet->setCellValue("B{$blockStart}", $no);
            $sheet->setCellValue("C{$blockStart}", $first->nama);
            $sheet->setCellValue("D{$blockStart}", $nik);
            $sheet->setCellValue("E{$blockStart}", $this->formatDate($first->tgl_lahir));
            $sheet->setCellValue("F{$blockStart}", $first->jenis_kelamin);
            $sheet->setCellValue("G{$blockStart}", $first->alamat);
            $sheet->setCellValue("G{$blockEnd}", 'no. hp : ' . ($first->no_hp ?: '-'));

            foreach (['B', 'C', 'D', 'E', 'F'] as $colLetter) {
                $sheet->mergeCells("{$colLetter}{$blockStart}:{$colLetter}{$blockEnd}");
            }
            $sheet->mergeCells("G{$blockStart}:G" . ($blockEnd - 1)); // alamat 3 baris, no hp baris ke-4

            // Kode KET 1-4 di kolom H
            $sheet->setCellValue("H{$blockStart}", 1);
            $sheet->setCellValue("H" . ($blockStart + 1), 2);
            $sheet->setCellValue("H" . ($blockStart + 2), 3);
            $sheet->setCellValue("H{$blockEnd}", 4);

            // Sebar kunjungan ke slot bulan (maks 3 kunjungan tercatat / bulan)
            $slotUsed = array_fill(1, 12, 0);

            foreach ($visits->sortBy('tanggal_kunjungan') as $visit) {
                if (empty($visit->tanggal_kunjungan)) {
                    continue;
                }

                $month = (int) date('n', strtotime($visit->tanggal_kunjungan));

                if ($slotUsed[$month] >= 3) {
                    // Kunjungan ke-4+ dalam 1 bulan yang sama TIDAK tertampung
                    // di template ini (kolomnya cuma 3). Lihat catatan di bawah.
                    continue;
                }

                $slot = $slotUsed[$month];
                $slotUsed[$month]++;

                $colIndex = 9 + ($month - 1) * 3 + $slot;
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);

                $tanggal = date('d/m', strtotime($visit->tanggal_kunjungan));
                $tempat = $visit->tempat_layanan ?: 'P';

                $sheet->setCellValue("{$colLetter}{$blockStart}", "{$tanggal} / {$tempat}");
                $sheet->setCellValue("{$colLetter}" . ($blockStart + 1), $visit->vital ?? '-');
                // Kosong (bukan '-') selama sumber data obat belum tersedia,
                // supaya jelas beda antara "tidak ada obat" vs "belum ada datanya".
                $sheet->setCellValue("{$colLetter}" . ($blockStart + 2), $visit->jumlah_obat ?? '');
                $sheet->setCellValue("{$colLetter}{$blockEnd}", $visit->jenis_obat ?? '');
            }

            $currentRow += 4;
            $no++;
        }

        // ===== Legenda di bawah tabel =====
        $legendRow = $currentRow + 1;
        $sheet->setCellValue("C{$legendRow}", 'KET *');
        $sheet->setCellValue("F{$legendRow}", 'TEMPAT LAYANAN **');

        $sheet->setCellValue("B" . ($legendRow + 1), 1);
        $sheet->setCellValue("C" . ($legendRow + 1), 'TANGGAL KUNJUNGAN/ TEMPAT LAYANAN**');
        $sheet->setCellValue("F" . ($legendRow + 1), 'P      : PUSKESMAS');

        $sheet->setCellValue("B" . ($legendRow + 2), 2);
        $sheet->setCellValue("C" . ($legendRow + 2), $vitalLabel);
        $sheet->setCellValue("F" . ($legendRow + 2), 'KS    : RUMAH SAKIT');

        $sheet->setCellValue("B" . ($legendRow + 3), 3);
        $sheet->setCellValue("C" . ($legendRow + 3), 'JUMLAH OBAT YANG DIBERIKAN');
        $sheet->setCellValue("F" . ($legendRow + 3), 'KL     : KLINIK');

        $sheet->setCellValue("B" . ($legendRow + 4), 4);
        $sheet->setCellValue("C" . ($legendRow + 4), 'JENIS OBAT YANG DIBERIKAN');
        $sheet->setCellValue("F" . ($legendRow + 4), 'PTP  : DOKTER PRATAMA');

        $sheet->getStyle("B{$legendRow}:F" . ($legendRow + 4))->getFont()->setSize(9);

        // ===== Border & sizing =====
        $lastDataRow = $currentRow - 1;
        if ($lastDataRow >= 5) {
            $sheet->getStyle("B4:AR{$lastDataRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(22);
        foreach (range(9, 44) as $colIndex) { // I (9) sampai AR (44)
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getColumnDimension($colLetter)->setWidth(10);
        }

        $sheet->getStyle("B5:AR{$lastDataRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
        $sheet->getStyle("C5:C{$lastDataRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("G5:G{$lastDataRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }

    protected function formatDate($date): string
    {
        if (empty($date)) {
            return '-';
        }
        try {
            return date('d/m/Y', strtotime($date));
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }
}
