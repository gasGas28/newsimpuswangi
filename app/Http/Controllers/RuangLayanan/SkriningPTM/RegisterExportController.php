<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Services\SkriningPTM\RegisterDMService;
use App\Services\SkriningPTM\RegisterHTService;
use App\Services\SkriningPTM\ExportDataRegisterService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegisterExportController extends Controller
{
    public function exportRegister(
        Request $request,
        ExportDataRegisterService $exportService,
        RegisterHTService $htService,
        RegisterDMService $dmService
    ): StreamedResponse {
        $request->validate([
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'puskesmas' => 'nullable|string',
            'status' => 'nullable|in:semua,tidak_normal',
        ]);

        $year = (int) ($request->query('tahun') ?? now()->year);
        $namaPuskesmas = $request->query('puskesmas', '……………………….');
        $status = $request->query('status', 'semua');

        $spreadsheet = new Spreadsheet();

        $htData = $htService->getData($year, $status);
        $exportService->buildSheet(
            $spreadsheet,
            0,
            'Hipertensi',
            "REGISTER PELAYANAN HIPERTENSI SESUAI STANDART DI PUSKESMAS {$namaPuskesmas} TAHUN {$year}",
            'TENSI',
            $htData
        );

        $dmData = $dmService->getData($year, $status);
        $exportService->buildSheet(
            $spreadsheet,
            1,
            'Diabetes Melitus',
            "REGISTER PELAYANAN DIABETES MELITUS SESUAI STANDART DI PUSKESMAS {$namaPuskesmas} TAHUN {$year}",
            'GDS/GDP',
            $dmData
        );

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = "kohort-ptm-{$year}-" . now()->format('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
