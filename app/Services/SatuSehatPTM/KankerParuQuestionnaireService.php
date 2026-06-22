<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusKankerParu;

class KankerParuQuestionnaireService
{
    /**
     * Questionnaire ID resmi SATUSEHAT untuk Kanker Paru
     */
    private const QUESTIONNAIRE_ID = 'https://fhir.kemkes.go.id/Questionnaire/Q0019';

    /**
     * Mapping hasil_kuesioner ke ICD-10 untuk Condition
     */
    private array $riskConditionMap = [
        'Low Risk'      => ['code' => '723505004',  'display' => 'Encounter for screening for cardiovascular disorders'],
        'Moderate Risk Of' => ['code' => '25594002', 'display' => 'Family history of trachea, bronchus and lung cancer'],
        'High Risk'     => ['code' => '723509005', 'display' => 'Personal history of other malignant neoplasm of bronchus and lung'],
    ];

    public function __construct(
        private EncounterService $encounterService,
    ) {}

    private ?string $cachedToken = null;

    private function getToken(): string
    {
        if (!$this->cachedToken) {
            $this->cachedToken = $this->encounterService->getAccessToken();
        }
        return $this->cachedToken;
    }

    private function findExistingQuestionnaireResponse(string $encounterId): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/QuestionnaireResponse', [
                'encounter'     => $encounterId,
                'questionnaire' => self::QUESTIONNAIRE_ID,
            ]);

        if (!$response->successful()) return null;

        $entries = $response->json('entry') ?? [];
        return !empty($entries) ? ($entries[0]['resource']['id'] ?? null) : null;
    }

    private function findExistingCondition(string $encounterId, string $icdCode): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Condition', [
                'encounter' => $encounterId,
                'code'      => "http://hl7.org/fhir/sid/icd-10|{$icdCode}",
            ]);

        if (!$response->successful()) return null;

        $entries = $response->json('entry') ?? [];
        return !empty($entries) ? ($entries[0]['resource']['id'] ?? null) : null;
    }

    private function createQuestionnaireResponse(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/QuestionnaireResponse', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat QuestionnaireResponse Kanker Paru: ' . $response->body());
        }

        return $response->json('id');
    }

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Condition', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition Kanker Paru: ' . $response->body());
        }

        return $response->json('id');
    }

    /**
     * Bangun item QuestionnaireResponse dari satu pasangan linkId + jawaban
     * Skip jika nilai kosong (tidak dilakukan)
     */
    private function buildItem(string $linkId, string $text, ?string $value): ?array
    {
        if (empty($value)) return null;

        return [
            'linkId' => $linkId,
            'text'   => $text,
            'answer' => [[
                'valueString' => $value,
            ]],
        ];
    }

    public function sendKankerParu(string $idSkrining): array
    {
        $skrining = SimpusSkriningPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $paru     = SimpusKankerParu::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        // ─── QuestionnaireResponse ────────────────────────────────────
        $questionnaireResponseId = null;
        $existingQrId            = $this->findExistingQuestionnaireResponse($encounterId);

        if ($existingQrId) {
            Log::info('QuestionnaireResponse Kanker Paru sudah ada, skip', ['qr_id' => $existingQrId]);
            $questionnaireResponseId = $existingQrId;
        } else {
            // Bangun items — skip item yang tidak diisi (nilai kosong)
            $itemDefinitions = [
                ['linkId' => 'kp1', 'text' => 'Pernah didiagnosis/menderita kanker?',         'value' => $paru->kuesioner1],
                ['linkId' => 'kp2', 'text' => 'Ada keluarga yang didiagnosis kanker?',         'value' => $paru->kuesioner2],
                ['linkId' => 'kp3', 'text' => 'Riwayat merokok/paparan asap rokok?',           'value' => $paru->kuesioner3],
                ['linkId' => 'kp4', 'text' => 'Tempat kerja mengandung zat karsinogenik?',     'value' => $paru->kuesioner4],
                ['linkId' => 'kp5', 'text' => 'Lingkungan dekat pabrik/pertambangan?',         'value' => $paru->kuesioner5],
                ['linkId' => 'kp6', 'text' => 'Lingkungan dalam rumah tidak sehat?',           'value' => $paru->kuesioner6],
                ['linkId' => 'kp7', 'text' => 'Pernah didiagnosis penyakit paru kronik?',      'value' => $paru->kuesioner7],
                ['linkId' => 'hasil', 'text' => 'Hasil Kuesioner Kanker Paru',                  'value' => $paru->hasil_kuesioner],
            ];

            $items = array_values(array_filter(
                array_map(fn($d) => $this->buildItem($d['linkId'], $d['text'], $d['value']), $itemDefinitions)
            ));

            $questionnaireResponseId = $this->createQuestionnaireResponse([
                'resourceType'  => 'QuestionnaireResponse',
                'questionnaire' => self::QUESTIONNAIRE_ID,
                'status'        => 'completed',
                'subject'       => ['reference' => "Patient/{$patientId}"],
                'encounter'     => ['reference' => "Encounter/{$encounterId}"],
                'authored'      => now()->toIso8601String(),
                'author'        => [
                    'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
                ],
                'source'        => [
                    'reference' => "Patient/{$patientId}",
                ],
                'item'          => $items,
            ]);

            Log::info('QuestionnaireResponse Kanker Paru berhasil', ['qr_id' => $questionnaireResponseId]);
        }

        // ─── Condition berdasarkan hasil kuesioner ───────────────────
        $conditionId  = null;
        $hasilKuesioner = $paru->hasil_kuesioner ?? '';

        if (!empty($hasilKuesioner) && isset($this->riskConditionMap[$hasilKuesioner])) {
            $icd                 = $this->riskConditionMap[$hasilKuesioner];
            $existingConditionId = $this->findExistingCondition($encounterId, $icd['code']);

            if ($existingConditionId) {
                Log::info('Condition Kanker Paru sudah ada, skip', ['condition_id' => $existingConditionId]);
                $conditionId = $existingConditionId;
            } else {
                $conditionId = $this->createCondition([
                    'resourceType'       => 'Condition',
                    'clinicalStatus'     => [
                        'coding' => [[
                            'system'  => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                            'code'    => 'active',
                            'display' => 'Active',
                        ]],
                    ],
                    'verificationStatus' => [
                        'coding' => [[
                            'system'  => 'http://terminology.hl7.org/CodeSystem/condition-ver-status',
                            'code'    => 'confirmed',
                            'display' => 'Confirmed',
                        ]],
                    ],
                    'category'           => [[
                        'coding' => [[
                            'system'  => 'http://terminology.hl7.org/CodeSystem/condition-category',
                            'code'    => 'encounter-diagnosis',
                            'display' => 'Encounter Diagnosis',
                        ]],
                    ]],
                    'code'               => [
                        'coding' => [[
                            'system'  => 'http://snomed.info/sct',
                            'code'    => $icd['code'],
                            'display' => $icd['display'],
                        ]],
                    ],
                    'subject'            => ['reference' => "Patient/{$patientId}"],
                    'encounter'          => ['reference' => "Encounter/{$encounterId}"],
                    'onsetDateTime'      => now()->toIso8601String(),
                    'recorder'           => [
                        'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
                    ],
                    'note'               => [[
                        'text' => "Hasil skrining kanker paru: {$hasilKuesioner}",
                    ]],
                ]);
                Log::info('Condition Kanker Paru berhasil', ['condition_id' => $conditionId]);
            }
        } else {
            Log::info('Condition Kanker Paru skip, hasil kuesioner kosong atau tidak dikenali', [
                'hasil_kuesioner' => $hasilKuesioner,
            ]);
        }

        $paru->update([
            'questionnaire_response_id' => $questionnaireResponseId,
            'condition_id'              => $conditionId,
            'sent_at'                   => now(),
        ]);

        return [
            'questionnaire_response_id' => $questionnaireResponseId,
            'condition_id'              => $conditionId,
        ];
    }
}
