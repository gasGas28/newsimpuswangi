<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;

/**
 * Isi data skrining PTM Klaster 3 (Kardiovaskular & Metabolik) ke template resmi
 * "Klaster_3_PTM.xlsx" (header 6 baris, merged cell, warna, data validation/dropdown, dsb).
 *
 * Tidak pakai DTO -- $rows cukup Collection<object> (stdClass) yang propertinya
 * sudah dinamai persis sesuai COLUMN_MAP (lihat LaporanService::mapRow()).
 *
 * Alur dibuat serupa dengan ExportDataRegisterService (Register Kohort):
 *   - Controller yang bikin/siapkan Spreadsheet & yang men-stream hasil akhirnya.
 *   - Service ini cuma bertanggung jawab MENGISI DATA ke Spreadsheet yang diberikan.
 *
 * Bedanya dengan Register (yang bikin sheet dari nol via new Spreadsheet()):
 * di sini Spreadsheet WAJIB berasal dari template resmi (loadTemplate()), karena
 * file yang di-download harus identik formatnya dengan Klaster_3_PTM.xlsx.
 *
 * Cara pakai (di controller):
 *   $rows = $laporanService->build($filters);
 *   $spreadsheet = $exportService->loadTemplate();
 *   $exportService->fillData($spreadsheet, $rows);
 *   $writer = new Xlsx($spreadsheet);
 *   // ...stream seperti di RegisterExportController
 */
class LaporanExportService
{
    /** Nama sheet data pada file template asli. */
    private const SHEET_NAME = 'Gangguan Kardiovaskular, Metabo';

    /** Baris pertama tempat data mulai diisi (baris 1-6 = judul & header). */
    private const FIRST_DATA_ROW = 7;

    /**
     * Baris terakhir yang SUDAH punya style & data validation bawaan template asli.
     * Kalau jumlah data melebihi ini, style & validation di-copy otomatis ke baris tambahan.
     */
    private const TEMPLATE_PREPARED_LAST_ROW = 106;

    private const LAST_COLUMN = 'AW';

    /** Path relatif template terhadap storage/app (disk 'local'). */
    private const TEMPLATE_RELATIVE_PATH = 'templates/klaster_3_ptm.xlsx';

    /**
     * Peta kolom Excel -> nama properti pada $row (object) yang dikirim LaporanService.
     * Urutan HARUS sesuai urutan kolom asli template.
     */
    private const COLUMN_MAP = [
        'A' => 'nik',
        'B' => 'nama',
        'C' => 'riwayat_penyakit_keluarga',
        'D' => 'riwayat_penyakit_diri_1',
        'E' => 'riwayat_penyakit_diri_2',
        'F' => 'riwayat_penyakit_diri_3',
        'G' => 'merokok_status',
        'H' => 'merokok_batang_per_hari',
        'I' => 'merokok_lama_tahun',
        'J' => 'terpapar_asap_rokok',
        'K' => 'puma_napas_pendek',
        'L' => 'puma_dahak',
        'M' => 'puma_batuk',
        'N' => 'puma_spirometri',
        'O' => 'konsumsi_gula_berlebih',
        'P' => 'konsumsi_garam_berlebih',
        'Q' => 'konsumsi_minyak_berlebih',
        'R' => 'kurang_sayur_buah',
        'S' => 'kurang_aktivitas_fisik',
        'T' => 'konsumsi_alkohol',
        'U' => 'tinggi_badan',
        'V' => 'berat_badan',
        'W' => 'lingkar_perut',
        'X' => 'sistolik',
        'Y' => 'diastolik',
        'Z' => 'gds',
        'AA' => 'gdp',
        'AB' => 'gd2jampp',
        'AC' => 'hba1c',
        'AD' => 'kolesterol_total',
        'AE' => 'hdl',
        'AF' => 'ldl',
        'AG' => 'trigliserida',
        'AH' => 'iva',
        'AI' => 'hpv_dna',
        'AJ' => 'sadanis',
        'AK' => 'usg_payudara',
        'AL' => 'diagnosis_1',
        'AM' => 'terapi_1',
        'AN' => 'diagnosis_2',
        'AO' => 'terapi_2',
        'AP' => 'diagnosis_3',
        'AQ' => 'terapi_3',
        'AR' => 'edukasi_berhenti_merokok',
        'AS' => 'edukasi_asap_rokok',
        'AT' => 'edukasi_diet',
        'AU' => 'edukasi_aktivitas_fisik',
        'AV' => 'ekg',
        'AW' => 'rujuk',
    ];

    /** Kolom yang wajib ditulis sebagai TEXT (bukan number) agar tidak berubah format. */
    private const STRING_COLUMNS = ['A']; // NIK: 16 digit, harus tetap string agar leading zero aman

    /**
     * Load template resmi dari storage. Dipanggil sekali di awal oleh controller,
     * mirror dengan `new Spreadsheet()` di RegisterExportController.
     */
    public function loadTemplate(): Spreadsheet
    {
        $path = storage_path('app/'.self::TEMPLATE_RELATIVE_PATH);

        if (! file_exists($path)) {
            throw new RuntimeException(
                "Template tidak ditemukan di: {$path}\n".
                'Salin file Klaster_3_PTM.xlsx ke storage/app/templates/klaster_3_ptm.xlsx terlebih dahulu.'
            );
        }

        return IOFactory::load($path);
    }

    /**
     * Isi data mulai baris ke-7 pada sheet Klaster 3.
     *
     * @param  Collection<int, object>  $rows  object/stdClass, propertinya sesuai COLUMN_MAP
     */
    public function fillData(Spreadsheet $spreadsheet, Collection $rows): void
    {
        $sheet = $spreadsheet->getSheetByName(self::SHEET_NAME);

        if (! $sheet instanceof Worksheet) {
            throw new RuntimeException(
                'Sheet "'.self::SHEET_NAME.'" tidak ditemukan pada template. '.
                'Cek apakah nama sheet berubah, atau file template korup.'
            );
        }

        $lastRowNeeded = self::FIRST_DATA_ROW + max($rows->count(), 1) - 1;
        $this->extendStyleAndValidation($sheet, $lastRowNeeded);

        $rowIndex = self::FIRST_DATA_ROW;

        foreach ($rows as $row) {
            $data = (array) $row;

            foreach (self::COLUMN_MAP as $column => $key) {
                $value = $data[$key] ?? null;

                if ($value === null || $value === '') {
                    continue; // biarkan sel kosong, jangan tulis "" agar dropdown/validation tetap bersih
                }

                $coordinate = $column.$rowIndex;

                if (in_array($column, self::STRING_COLUMNS, true)) {
                    $sheet->setCellValueExplicit($coordinate, (string) $value, DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($coordinate, $value);
                }
            }

            $rowIndex++;
        }
    }

    /**
     * Kalau data lebih banyak dari baris yang sudah disiapkan template asli
     * (style + dropdown/data validation cuma sampai baris 106), copy style &
     * validation dari baris terakhir template ke baris-baris tambahan.
     *
     * Best-effort: setelah export, cek ulang beberapa baris terakhir di Excel
     * untuk memastikan dropdown & format ikut ke-copy dengan benar.
     */
    private function extendStyleAndValidation(Worksheet $sheet, int $lastRowNeeded): void
    {
        if ($lastRowNeeded <= self::TEMPLATE_PREPARED_LAST_ROW) {
            return;
        }

        $templateLastRow = self::TEMPLATE_PREPARED_LAST_ROW;

        for ($r = $templateLastRow + 1; $r <= $lastRowNeeded; $r++) {
            $sheet->duplicateStyle(
                $sheet->getStyle("A{$templateLastRow}:".self::LAST_COLUMN.$templateLastRow),
                "A{$r}:".self::LAST_COLUMN.$r
            );

            foreach (self::COLUMN_MAP as $column => $key) {
                $refCell = $sheet->getCell($column.$templateLastRow);
                $refValidation = $refCell->getDataValidation();

                if ($refValidation === null || $refValidation->getFormula1() === null) {
                    continue;
                }

                $newCell = $sheet->getCell($column.$r);
                $newValidation = clone $refValidation;
                $newValidation->setSqref($column.$r);
                $newCell->setDataValidation($newValidation);
            }
        }
    }
}
