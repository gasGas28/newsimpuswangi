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
use App\Http\Requests\StoreKunjunganPTMRequest;
use App\Http\Requests\AssessmentPTMRequest;
use App\Http\Requests\FaktorRisikoRequest;
use App\Http\Requests\PemeriksaanPTMRequest;

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
            $request->idloket,
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


    public function tambahKunjunganPTM(StoreKunjunganPTMRequest $request)
    {
        $validated = $request->validated();

        $this->pelayananService->addKunjunganPTM($validated);

        return redirect()->back();
    }

    public function addFaktorRisiko(FaktorRisikoRequest $request)
    {

        $validated = $request->validated();
        $this->pelayananService->addFaktorRisiko($validated);
        return redirect()->back();
    }

    public function addPemeriksaanPTM(PemeriksaanPTMRequest $request)
    {
        $validated = $request->validated();

        // dd($validated);

        $this->pelayananService->savePemeriksaanMetabolik($validated);

        return redirect()->back();
    }


    public function addAssessmentPTM(AssessmentPTMRequest $request)
    {
        $validated = $request->validated();

        $this->pelayananService->addAssessmentPTM($validated);

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
