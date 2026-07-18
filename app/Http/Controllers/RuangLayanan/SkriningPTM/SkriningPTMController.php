<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsamUratRequest;
use Inertia\Inertia;
use App\Services\SkriningPTM\SkriningPTMService;
use App\Services\SkriningPTM\PelayananPTMService;
use App\Services\TindakanService;
use App\Http\Requests\SimpanTindakanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreKunjunganPTMRequest;
use App\Http\Requests\AssessmentPTMRequest;
use App\Http\Requests\DiabetesRequest;
use App\Http\Requests\FaktorRisikoRequest;
use App\Http\Requests\GangguanInderaRequest;
use App\Http\Requests\HipertensiRequest;
use App\Http\Requests\ObesitasRequest;
use App\Http\Requests\PemeriksaanEKGRequest;
use App\Http\Requests\PemeriksaanKankerIvaRequest;
use App\Http\Requests\PemeriksaanKolorektalRequest;
use App\Http\Requests\PemeriksaanParuRequest;
use App\Http\Requests\PemeriksaanPTMRequest;
use App\Http\Requests\ProfilLipidRequest;
use App\Http\Requests\StatusPasienRequest;
use App\Http\Requests\ThalasemiaRequest;
use App\Http\Requests\ResepObatRequest;
use App\Http\Requests\EdukasiRequest;

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

    public function pelayanan(string $id, string $idPoli, string $idPelayanan)
    {
        $DataPasien = $this->pelayananService->getDataPasien($id, $idPoli);
        $masterData = $this->pelayananService->getMasterData($idPelayanan);
        $DataTindakan = $this->tindakanService->getTindakanPelayanan($idPelayanan);

        // dd($DataTindakan);

        return Inertia::render('Ruang_Layanan/SkriningPTM/Pelayanan', [
            'DataPasien' => $DataPasien,
            'DataTindakan' => $DataTindakan,
            ...$masterData,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $this->pelayananService->updateStatusPelayanan(
            $request->idpelayanan,
            $request->status
        );

        // dd($request->idloket);

        return back();
    }
    public function akhirPelayanan(Request $request)
    {
        $this->pelayananService->endPelayanan(
            $request->idpelayanan,
            $request->status
        );

        // dd($request->idloket);

        return back();
    }


    public function simpanResepObat(ResepObatRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);

        $this->pelayananService->resepObat($validated);

        return back();
    }


    public function tambahKunjunganPTM(StoreKunjunganPTMRequest $request)
    {
        $validated = $request->validated();

        $this->pelayananService->addKunjunganPTM($validated);

        return back();
    }

    public function addFaktorRisiko(FaktorRisikoRequest $request)
    {

        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->addFaktorRisiko($validated);
        return back();
    }

    public function addPemeriksaanObesitas(ObesitasRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->saveObesitas($validated);
        return back();
    }
    public function addPemeriksaanHipertensi(HipertensiRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->saveHipertensi($validated);
        return back();
    }
    public function addPemeriksaanDiabetes(DiabetesRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->saveDiabetes($validated);
        return back();
    }
    public function addPemeriksaanLipid(ProfilLipidRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->saveProfilLipid($validated);
        return back();
    }
    public function addPemeriksaanAsamUrat(AsamUratRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->saveAsamUrat($validated);
        return back();
    }

    public function addPemeriksaanPTM(PemeriksaanPTMRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->savePemeriksaanMetabolik($validated);
        return back();
    }
    public function addPemeriksaanIndera(GangguanInderaRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveGangguanIndera($validated);
        return back();
    }

    public function addPemeriksaanThalasemia(ThalasemiaRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveThalasemia($validated);
        return back();
    }
    public function addPemeriksaanParu(PemeriksaanParuRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveKankerParu($validated);
        return back();
    }
    public function addPemeriksaanEKG(PemeriksaanEKGRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveEKG($validated);
        return back();
    }
    public function addPemeriksaanKolorektal(PemeriksaanKolorektalRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveKolorektal($validated);
        return back();
    }
    public function addPemeriksaanKanker(PemeriksaanKankerIvaRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveKankerServiks($validated);
        return back();
    }
    public function addStatusPasien(StatusPasienRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->saveStatusPasien($validated);
        return back();
    }


    public function addAssessmentPTM(AssessmentPTMRequest $request)
    {
        $validated = $request->validated();
        $this->pelayananService->addAssessmentPTM($validated);
        return back();
    }
    public function addEdukasi(EdukasiRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $this->pelayananService->saveEdukasi($validated);
        return back();
    }

    public function tindakanHapus(string $id)
    {
        $result = $this->tindakanService->hapusTindakan($id);

        if (!$result['success']) {
            return back()->withErrors(['message' => $result['message']]);
        }

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

        return back();
    }
    public function deleteResep(string $id)
    {
        $result = $this->pelayananService->hapusResep($id);

        if (!$result['success']) {
            return back()->withErrors(['message' => $result['message']]);
        }

        return back();
    }
}
