<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Services\SkriningPTM\LaporanService;
use App\Services\SkriningPTM\LaporanExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanPTMController extends Controller
{
    /**
     * Halaman filter export (Inertia page).
     * GET /export/klaster-3-ptm
     */
    public function index(): Response
    {
        return Inertia::render('Export/KlasterPtm');
    }

    /**
     * Generate & download file Excel.
     * GET /export/klaster-3-ptm/download?tanggal_mulai=...&tanggal_selesai=...
     *
     * Alur ini sengaja dibuat serupa dengan RegisterExportController::exportRegister():
     * controller yang menyiapkan Spreadsheet, memanggil service untuk mengisi data,
     * lalu men-stream hasilnya langsung di sini (bukan lewat method download() terpisah).
     */
    public function download(
        Request $request,
        LaporanService $laporanService,
        LaporanExportService $exportService,
    ): StreamedResponse {
        $filters = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        // 1. Ambil & susun data (mirror $htService->getData() / $dmService->getData())
        $rows = $laporanService->build($filters);

        // 2. Siapkan Spreadsheet dari template resmi (mirror new Spreadsheet(), tapi
        //    di sini WAJIB load template karena harus identik dgn Klaster_3_PTM.xlsx)
        $spreadsheet = $exportService->loadTemplate();

        // 3. Isi data (mirror $exportService->buildSheet(...))
        $exportService->fillData($spreadsheet, $rows);

        // 4. Tulis & stream (mirror bagian akhir RegisterExportController)
        $writer = new Xlsx($spreadsheet);
        $filename = 'klaster_3_ptm_'.now()->format('Ymd_His').'.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
