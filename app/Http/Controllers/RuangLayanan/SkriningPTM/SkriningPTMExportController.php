<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Services\SkriningPTM\SkriningPTMExportService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SkriningPTMExportController extends Controller
{
    protected SkriningPTMExportService $exportService;

    public function __construct(SkriningPTMExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'nik' => 'required|string',
        ]);

        $spreadsheet = $this->exportService->export($request->query('nik'));
        $writer = new Xlsx($spreadsheet);

        $filename = 'skrining-ptm-' . $request->query('nik') . '-' . now()->format('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}