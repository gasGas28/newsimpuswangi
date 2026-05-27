<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Services\SkriningPTMService;
use App\Services\PelayananPTMService;
use App\Services\TindakanService;
use App\Http\Requests\SimpanTindakanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class SkriningPTMController extends Controller
{
    protected $skriningService;
    protected $pelayananService;
    protected $tindakanService;

    public function __construct(
        SkriningPTMService $skriningService,
        PelayananPTMService $pelayananService,
        TindakanService $tindakanService
    ) {
        $this->skriningService = $skriningService;
        $this->pelayananService = $pelayananService;
        $this->tindakanService = $tindakanService;
    }

    public function index(Request $request)
    {
        $dataPasien = $this->skriningService->getAllData($request);
        // dd($data);
        return Inertia::render('Ruang_Layanan/SkriningPTM/Index', $dataPasien);
    }

    public function pelayanan($id, $idPoli, $idPelayanan)
    {
        $DataPasien = $this->pelayananService->getData($id, $idPoli);
        $masterData = $this->pelayananService->getMasterData();
        $DataTindakan = $this->tindakanService->getTindakanPelayanan($idPelayanan);

        // dd($DataTindakan);

        return Inertia::render('Ruang_Layanan/SkriningPTM/Pelayanan', [
            'DataPasien' => $DataPasien,
            'DataTindakan' => $DataTindakan,
            'idPelayanan' => $idPelayanan,
            'idPoli' => $idPoli,
            ...$masterData,
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
    public function simpanTindakan(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'idpelayanan' => 'required',
            'kode_tindakan' => 'required',
            'nama_tindakan' => 'required',
            'loketId' => 'required',
            'nama_tindakan_ind' => 'nullable',
            'keterangan' => 'nullable',
            'kdPoli' => 'required',
        ])->validate();

        $this->tindakanService->svTindakan($validated);

        return redirect()->back();
    }

    public function tindakanHapus($id)
    {
        $result = $this->tindakanService->hapusTindakan($id);

        if (!$result['success']) {
            return back()->withErrors(['message' => $result['message']]);
        }

        return back();
    }
}
