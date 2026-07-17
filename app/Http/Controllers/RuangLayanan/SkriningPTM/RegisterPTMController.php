<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Services\SkriningPTM\LaporanService;
use App\Services\SkriningPTM\RegisterPasienPTMService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;


class RegisterPTMController extends Controller
{
    public function __construct(private RegisterPasienPTMService $registerPTMService) {}

    public function index(Request $request)
    {
        $dataRiwayat = $this->registerPTMService->getRegister($request->NIK);
        // dd($dataRiwayat);
        return Inertia::render('Ruang_Layanan/SkriningPTM/DataRegister', [
            'DataRiwayat' => $dataRiwayat,
        ]);
    }

    public function downloadRegisterPTM(Request $request)
    {
        $dataRiwayat = $this->registerPTMService->getRegister($request->NIK);

        return Pdf::loadView('Reports.DataRegister', [
            'riwayat' => $dataRiwayat,
        ])
            ->setPaper('a4', 'portrait')
            ->download('Lembar_PTM_' . date('YmdHis') . '.pdf');
    }
    //
}
