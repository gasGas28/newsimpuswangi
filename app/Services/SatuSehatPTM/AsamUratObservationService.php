<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusAsamUrat;

class AsamUratObservationService
{
    private array $categoryMap = [
        'normal'       => ['code' => 'Z03.8', 'display' => 'No diagnosis'],
        'hiperurisemia' => ['code' => 'M10.9', 'display' => 'Gout, unspecified'],
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

    private function findExistingObservation(string $encounterId): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Observation', [
                'encounter' => $encounterId,
                'code'      => '14933-6',
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

    private function createObservation(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Observation', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Observation Asam Urat: ' . $response->body());
        }

        return $response->json('id');
    }

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Condition', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition Asam Urat: ' . $response->body());
        }

        return $response->json('id');
    }

    private function resolveCategory(string $kategori): array
    {
        // ✅ Handle typo "hiperisemia" dari DB
        $normalized = strtolower(trim($kategori));
        $aliases    = ['hiperisemia' => 'hiperurisemia'];
        $normalized = $aliases[$normalized] ?? $normalized;

        if (!isset($this->categoryMap[$normalized])) {
            Log::warning('resolveCategory AsamUrat: tidak dikenali, fallback ke normal', [
                'kategori' => $kategori,
            ]);
            return $this->categoryMap['normal'];
        }

        return $this->categoryMap[$normalized];
    }

    public function sendAsamUrat(string $idSkrining): array
    {
        $skrining  = SimpusSkriningPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $asamUrat  = SimpusAsamUrat::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        // ─── Observation ─────────────────────────────────────────────
        $observationId  = null;
        $existingObsId  = $this->findExistingObservation($encounterId);

        if ($existingObsId) {
            Log::info('Observation Asam Urat sudah ada, skip', ['observation_id' => $existingObsId]);
            $observationId = $existingObsId;
        } else {
            $observationId = $this->createObservation([
                'resourceType'      => 'Observation',
                'status'            => 'final',
                'category'          => [[
                    'coding' => [[
                        'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                        'code'    => 'laboratory',
                        'display' => 'Laboratory',
                    ]],
                ]],
                'code'              => [
                    'coding' => [[
                        'system'  => 'http://loinc.org',
                        'code'    => '14933-6',
                        'display' => 'Urate [Moles/volume] in Serum or Plasma',
                    ]],
                ],
                'subject'           => ['reference' => "Patient/{$patientId}"],
                'encounter'         => ['reference' => "Encounter/{$encounterId}"],
                'effectiveDateTime' => now()->toIso8601String(),
                'performer'         => [[
                    'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
                ]], 
                'valueQuantity'     => [
                    'value'  => (float) $asamUrat->asam_urat,
                    'unit'   => 'mg/dL',
                    'system' => 'http://unitsofmeasure.org',
                    'code'   => 'mg/dL',
                ],
            ]);
            Log::info('Observation Asam Urat berhasil', ['observation_id' => $observationId]);
        }

        // ─── Condition ───────────────────────────────────────────────
        $conditionId = null;
        $normalized  = strtolower(trim($asamUrat->kategori_asam_urat));

        if ($normalized === 'normal') {
            Log::info('Condition Asam Urat skip, kategori normal');
        } else {
            $icd                 = $this->resolveCategory($asamUrat->kategori_asam_urat);
            $existingConditionId = $this->findExistingCondition($encounterId, $icd['code']);

            if ($existingConditionId) {
                Log::info('Condition Asam Urat sudah ada, skip', ['condition_id' => $existingConditionId]);
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
                            'system'  => 'http://hl7.org/fhir/sid/icd-10',
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
                ]);
                Log::info('Condition Asam Urat berhasil', ['condition_id' => $conditionId]);
            }
        }

        $asamUrat->update([
            'observation_id' => $observationId,
            'condition_id'   => $conditionId,
            'sent_at'        => now(),
        ]);

        return [
            'observation_id' => $observationId,
            'condition_id'   => $conditionId,
        ];
    }
}
