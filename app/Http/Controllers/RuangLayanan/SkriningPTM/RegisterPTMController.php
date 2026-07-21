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

    public function index()
    {
        $dataRiwayat = $this->registerPTMService->getRegister();
        // dd($dataRiwayat);
        return Inertia::render('Ruang_Layanan/SkriningPTM/DataRegister', [
            'DataRiwayat' => $dataRiwayat,
        ]);
    }
}
