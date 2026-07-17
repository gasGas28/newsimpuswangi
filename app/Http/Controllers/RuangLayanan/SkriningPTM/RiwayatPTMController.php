<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Services\SkriningPTM\LaporanPTMService;
use App\Services\SkriningPTM\RiwayatPasien;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatPTMController extends Controller
{
    public function __construct(private RiwayatPasien $riwayatPasienService, private LaporanPTMService $laporanPTM) {}
    public function index(Request $request)
    {
        $dataRiwayat = $this->riwayatPasienService->getDataPasien($request->idSkrining);

        return Inertia::render('Ruang_Layanan/SkriningPTM/RiwayatPasien/RiwayatPasien', [
            'DataRiwayat' => $dataRiwayat,
        ]);
    }
    public function laporanPTM(Request $request)
    {
        $dataRiwayat = $this->riwayatPasienService->getDataPasien($request->kdPoli);

        return Inertia::render('Ruang_Layanan/SkriningPTM/RiwayatPasien/RiwayatPasien', [
            'DataRiwayat' => $dataRiwayat,
        ]);
    }

    public function downloadPDF(Request $request)
    {
        $dataRiwayat = $this->riwayatPasienService->getDataPasien($request->idSkrining);

        $pdf = Pdf::loadView('Reports.RiwayatPasienPDF', [
            'riwayat' => $dataRiwayat,
        ]);

        $namaPasien = $dataRiwayat[0]->NAMA_LGKP ?? 'Pasien';
        $filename = 'Riwayat_' . str_replace(' ', '_', $namaPasien) . '_' . date('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadLembarPTM(Request $request)
    {
        $dataRiwayat = $this->riwayatPasienService->getDataPasien($request->idSkrining);

        return Pdf::loadView('Reports.LembarPencatatan', [
            'riwayat' => $dataRiwayat,
        ])->download('Lembar_PTM_' . date('YmdHis') . '.pdf');
    }
}
