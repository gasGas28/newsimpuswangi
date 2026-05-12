<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Services\SkriningPtmService;
use App\Services\PelayananPTMService;
use Illuminate\Http\Request;


class SkriningPTMController extends Controller
{
    protected $skriningService;
    protected $pelayananService;

    public function __construct(
        SkriningPtmService $skriningService,
        PelayananPTMService $pelayananService
    ) {
        $this->skriningService = $skriningService;
        $this->pelayananService = $pelayananService;
    }

    public function index(Request $request)
    {
        $data = $this->skriningService->getAllData($request);
        // dd($data);
        return Inertia::render('Ruang_Layanan/SkriningPTM/Index', $data);
    }

    public function pelayanan($id, $idPoli, $idPelayanan)
    {
        $DataPasien = $this->pelayananService->getData($id, $idPoli);

        return Inertia::render('Ruang_Layanan/SkriningPTM/Pelayanan', [
            'DataPasien' => $DataPasien,
            'idPelayanan' => $idPelayanan,
            'idPoli' => $idPoli,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $this->pelayananService->updateStatusPelayanan(
            $request->idpelayanan,
            $request->status
        );

        return back();
    }
}
