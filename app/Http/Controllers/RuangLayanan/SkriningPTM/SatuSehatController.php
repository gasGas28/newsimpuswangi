<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
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

            $formatCondition = fn(array $items) => collect($items)
                ->map(fn($v, $k) => [
                    'field'        => $k,
                    'condition_id' => $v['condition_id'] ?? null,
                    'status'       => isset($v['error'])
                        ? 'gagal'
                        : ($v['status'] === 'already_exists' ? 'sudah_ada' : 'berhasil'),
                    'error'        => $v['error'] ?? null,
                    'clinical_sts' => $v['clinical_status'] ?? null,
                ])
                ->values();

            return redirect()->back()->with([
                'success' => true,
                'message' => 'Faktor risiko berhasil dikirim',
                'data'    => [
                    'riwayat_ptm' => $formatCondition($conditionResult['riwayat_ptm'] ?? [])->toArray(),
                ],
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

            return response()->json([
                'message'        => 'Data hipertensi berhasil dikirim',
                'observation_id' => $observationResult['observation_id'],
                'condition_id'   => $conditionResult['condition_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function sendObesitas(string $idSkrining)
    {
        try {
            $antropometri = $this->obesitasObservationService->sendAntropometri($idSkrining);
            $condition    = $this->obesitasConditionService->sendCondition($idSkrining);

            return response()->json([
                'message'          => 'Data obesitas berhasil dikirim',
                'observation_id'   => $antropometri['observation_id'],
                'condition_imt_id' => $condition['condition_imt_id'],
                'condition_lp_id'  => $condition['condition_lp_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function sendDiabetes(string $idSkrining)
    {
        try {
            $diabetes = $this->diabetesObservationService->sendDiabetes($idSkrining);

            return response()->json([
                'message'          => 'Data Diabetes berhasil dikirim',
                'condition_id' => $diabetes['condition_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function sendAsamUrat(string $idSkrining)
    {
        try {
            $asamUrat = $this->asamUratObservationService->sendAsamUrat($idSkrining);

            return response()->json([
                'message'          => 'Data obesitas berhasil dikirim',
                'observation_id'   => $asamUrat['observation_id'],
                'condition_id' => $asamUrat['condition_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
