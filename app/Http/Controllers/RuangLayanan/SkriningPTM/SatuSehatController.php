<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use App\Services\SatuSehatPTM\KankerServiksObservationService;
use Illuminate\Http\Request;
use App\Services\SatuSehatPTM\EncounterService;
use App\Services\SatuSehatPTM\ObservationService;
use App\Services\SatuSehatPTM\QuestionnaireResponseService;
use App\Services\SatuSehatPTM\RiwayatPTMService;
use App\Services\SatuSehatPTM\HipertensiObservationService;
use App\Services\SatuSehatPTM\HipertensiConditionService;
use App\Services\SatuSehatPTM\ObesitasObservationService;
use App\Services\SatuSehatPTM\ObesitasConditionService;
use App\Services\SatuSehatPTM\DiabetesObservationService;
use App\Services\SatuSehatPTM\AsamUratObservationService;
use App\Services\SatuSehatPTM\EkgObservationService;
use App\Services\SatuSehatPTM\GangguanPendengaranObservationService;
use App\Services\SatuSehatPTM\GangguanPenglihatanObservationService;
use App\Services\SatuSehatPTM\KankerParuQuestionnaireService;
use App\Services\SatuSehatPTM\KolorektalQuestionnaireService;
use App\Services\SatuSehatPTM\ProfilLipidObservationService;

class SatuSehatController extends Controller
{
    public function __construct(
        private EncounterService $encounterService,
        private ObservationService $observationService,
        private QuestionnaireResponseService $questionnaireResponseService,
        private RiwayatPTMService $riwayatPTMService,
        private HipertensiObservationService $hipertensiObservationService,
        private HipertensiConditionService $hipertensiConditionService,
        private ObesitasObservationService $obesitasObservationService,
        private ObesitasConditionService $obesitasConditionService,
        private DiabetesObservationService $diabetesObservationService,
        private AsamUratObservationService $asamUratObservationService,
        private ProfilLipidObservationService $profilLipidObservationService,
        private GangguanPendengaranObservationService $gangguanPendengaranObservationService,
        private GangguanPenglihatanObservationService $gangguanPenglihatanObservationService,
        private EkgObservationService $ekgObservationService,
        private KankerParuQuestionnaireService $kankerParuQuestionnaireService,
        private KolorektalQuestionnaireService $kolorektalQuestionnaireService,
        private KankerServiksObservationService $kankerServiksObservationService
    ) {}

    public function testEncounter(string $idSkrining)
    {
        try {
            $encounterId = $this->encounterService->kirimEncounter($idSkrining);
            return redirect()->back()->with([
                'success'      => true,
                'encounter_id' => $encounterId,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function sendRiskFactor(string $idSkrining)
    {
        try {
            $observationId   = $this->observationService->sendSmokingStatus($idSkrining);
            $this->questionnaireResponseService->sendFaktorRisiko($idSkrining, $observationId);
            $conditionResult = $this->riwayatPTMService->sendRiwayat($idSkrining);

            return redirect()->back()->with([
                'message'        => 'Data Faktor Risiko berhasil dikirim',
                'condition_id'   => $conditionResult['condition_id'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function sendHipertensi(string $idSkrining)
    {
        try {
            $observationResult = $this->hipertensiObservationService->sendBloodPressure($idSkrining);
            $conditionResult   = $this->hipertensiConditionService->sendCondition($idSkrining);

            return redirect()->back()->with([
                'message'        => 'Data hipertensi berhasil dikirim',
                'observation_id' => $observationResult['observation_id'],
                'condition_id'   => $conditionResult['condition_id'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function sendObesitas(string $idSkrining)
    {
        try {
            $antropometri = $this->obesitasObservationService->sendAntropometri($idSkrining);
            $condition    = $this->obesitasConditionService->sendCondition($idSkrining);

            return redirect()->back()->with([
                'message'          => 'Data obesitas berhasil dikirim',
                'observation_id'   => $antropometri['observation_id'],
                'condition_imt_id' => $condition['condition_imt_id'],
                'condition_lp_id'  => $condition['condition_lp_id'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendDiabetes(string $idSkrining)
    {
        try {
            $diabetes = $this->diabetesObservationService->sendDiabetes($idSkrining);

            return redirect()->back()->with([
                'message'          => 'Data Diabetes berhasil dikirim',
                'condition_id' => $diabetes['condition_id'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendAsamUrat(string $idSkrining)
    {
        try {
            $asamUrat = $this->asamUratObservationService->sendAsamUrat($idSkrining);

            return redirect()->back()->with([
                'message'          => 'Data Asam Urat berhasil dikirim',
                'observation_id'   => $asamUrat['observation_id'],
                'condition_id' => $asamUrat['condition_id'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendProfilLipid(string $idSkrining)
    {
        try {
            $this->profilLipidObservationService->sendProfilLipid($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data Profil Lipid berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendGangguanPendengaran(string $idSkrining)
    {
        try {
            $this->gangguanPendengaranObservationService->sendGangguanPendengaran($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data Gangguan Pendengaran berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendGangguanPenglihatan(string $idSkrining)
    {
        try {
            $this->gangguanPenglihatanObservationService->sendGangguanPenglihatan($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data Gangguan Penglihatan berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendKankerParu(string $idSkrining)
    {
        try {
            $this->kankerParuQuestionnaireService->sendKankerParu($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data Kanker Paru berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendKolorektal(string $idSkrining)
    {
        try {
            $this->kolorektalQuestionnaireService->sendKolorektal($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data Kolorektal berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendEKG(string $idSkrining)
    {
        try {
            $this->ekgObservationService->sendEkg($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data EKG berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
    public function sendKankerServiks(string $idSkrining)
    {
        try {
            $this->kankerServiksObservationService->sendIvaServiks($idSkrining);

            return redirect()->back()->with([
                'message' => 'Data Kanker Serviks berhasil dikirim',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage()], 500);
        }
    }
}
