<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SatuSehatPTM\EncounterService;
use App\Services\SatuSehatPTM\ObservationService;
use App\Services\SatuSehatPTM\QuestionnaireResponseService;
use App\Services\SatuSehatPTM\RiwayatPTMService;

class SatuSehatController extends Controller
{
    public function __construct(
        private EncounterService $encounterService,
        private ObservationService $observationService,
        private QuestionnaireResponseService $questionnaireResponseService,
        private RiwayatPTMService $riwayatPTMService,
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
}