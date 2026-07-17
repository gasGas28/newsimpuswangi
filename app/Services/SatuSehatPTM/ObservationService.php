<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\FaktorRisiko;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use Illuminate\Support\Facades\Log;

class ObservationService
{
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


    /**
     * Kirim status merokok sebagai Observation (LOINC 72166-2)
     * Return observation ID untuk dipakai di QuestionnaireResponse (linkId 1.4)
     */
    private function createObservation(array $payload): string
    {
        $token = $this->encounterService->getAccessToken();

        $response = Http::withToken($token)
            ->acceptJson()
            ->post(
                config('services.satusehat.fhir_url') . '/Observation',
                $payload
            );

        if (!$response->successful()) {
            throw new \Exception(
                'Gagal membuat Observation: ' . $response->body()
            );
        }

        return $response->json('id');
    }
    public function sendSmokingStatus(string $idSkrining): string
    {
        $skrining     = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $faktorRisiko = FaktorRisiko::where('skriningID', $idSkrining)->firstOrFail();

        $patientId      = $skrining->patient_id;
        $encounterId    = $skrining->encounter_id;

        $existingId = $this->findExistingObservation($encounterId);
        if ($existingId) {
            Log::info('Observation sudah ada, skip kirim', [
                'observation_id' => $existingId,
            ]);
            return $existingId;
        }

        $smokingStatusMap = [
            'perokok_aktif'  => ['code' => '77176002', 'display' => 'Smoker'],
            'mantan_perokok' => ['code' => '8517006',  'display' => 'Ex-smoker'],
            'tidak_pernah'   => ['code' => '8392000',  'display' => 'Non-smoker'],
        ];

        $statusValue = strtolower($faktorRisiko->status_merokok ?? '');

        if (!isset($smokingStatusMap[$statusValue])) {
            Log::warning('status_merokok tidak dikenali, fallback ke Non-smoker', [
                'status_merokok' => $statusValue,
                'idSkrining'     => $idSkrining,
            ]);
        }

        $snomed = $smokingStatusMap[$statusValue] ?? ['code' => '8392000', 'display' => 'Non-smoker'];

        $payload = [
            'resourceType' => 'Observation',
            'status'       => 'final',

            'category' => [
                [
                    'coding' => [
                        [
                            'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                            'code'    => 'social-history',
                            'display' => 'Social History',
                        ],
                    ],
                ],
            ],

            'code' => [
                'coding' => [
                    [
                        'system'  => 'http://loinc.org',
                        'code'    => '72166-2',
                        'display' => 'Tobacco smoking status',
                    ],
                ],
                'text' => 'Tobacco smoking status',
            ],

            'subject'   => ['reference' => "Patient/{$patientId}"],
            'encounter' => ['reference' => "Encounter/{$encounterId}"],
            'performer' => [['reference' => "Practitioner/" . config('services.satusehat.practitioner_id')]],

            'valueCodeableConcept' => [
                'coding' => [
                    [
                        'system'  => 'http://snomed.info/sct',
                        'code'    => $snomed['code'],
                        'display' => $snomed['display'],
                    ],
                ],
            ],
        ];

        try {
            $observationId = $this->createObservation($payload);
            Log::info('Observation status merokok berhasil', [
                'observation_id' => $observationId,
            ]);
            return $observationId;
        } catch (\Exception $e) {
            Log::error('Observation status merokok gagal', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function findExistingObservation(string $encounterId): ?string
    {

        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Observation', [
                'encounter' => $encounterId,
                'code'      => '72166-2', // LOINC status merokok
            ]);

        if (!$response->successful()) {
            return null;
        }

        $entries = $response->json('entry') ?? [];

        if (!empty($entries)) {
            return $entries[0]['resource']['id'] ?? null;
        }

        return null;
    }
}
