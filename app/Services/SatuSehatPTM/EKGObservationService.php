<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusEkg;

class EkgObservationService
{
    /**
     * Mapping kesimpulan_ekg ke SNOMED CT
     * Sesuai terminologi SATUSEHAT: hanya Normal atau Abnormal
     */
    private array $valueCodeMap = [
        'normal'   => ['code' => '164854000', 'display' => 'Electrocardiogram normal'],
        'abnormal' => ['code' => '102594003', 'display' => 'Electrocardiogram abnormal'],
    ];

    /**
     * Mapping irama jantung ke ICD-10 untuk Condition
     */
    private array $iramaConditionMap = [
        'sinus_normal'     => ['code' => 'Z03.5',  'display' => 'Observation for suspected cardiovascular disease'],
        'sinus_bradikardi' => ['code' => 'R00.1',  'display' => 'Bradycardia, unspecified'],
        'sinus_takikardi'  => ['code' => 'R00.0',  'display' => 'Tachycardia, unspecified'],
        'atrial_fibrilasi' => ['code' => 'I48.91', 'display' => 'Unspecified atrial fibrillation'],
        'blok_av'          => ['code' => 'I44.30', 'display' => 'Other and unspecified atrioventricular block'],
        'lbbb'             => ['code' => 'I44.7',  'display' => 'Left bundle-branch block, unspecified'],
        'rbbb'             => ['code' => 'I45.10', 'display' => 'Unspecified right bundle-branch block'],
        'lvh'              => ['code' => 'I51.7',  'display' => 'Cardiomegaly'],
    ];

    /**
     * Mapping segmen_st ke ICD-10 (override irama jika segmen ST lebih spesifik)
     */
    private array $segmenStConditionMap = [
        'elevasi'   => ['code' => 'I21.9', 'display' => 'Acute myocardial infarction, unspecified'],
        'st elevasi'=> ['code' => 'I21.9', 'display' => 'Acute myocardial infarction, unspecified'],
        'stemi'     => ['code' => 'I21.9', 'display' => 'Acute myocardial infarction, unspecified'],
        'depresi'   => ['code' => 'I25.9', 'display' => 'Chronic ischaemic heart disease, unspecified'],
        'st depresi'=> ['code' => 'I25.9', 'display' => 'Chronic ischaemic heart disease, unspecified'],
        'nstemi'    => ['code' => 'I25.9', 'display' => 'Chronic ischaemic heart disease, unspecified'],
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
                'code'      => '34534-8',
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
            throw new \Exception('Gagal membuat Observation EKG: ' . $response->body());
        }

        return $response->json('id');
    }

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Condition', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition EKG: ' . $response->body());
        }

        return $response->json('id');
    }

    /**
     * Tentukan valueCodeableConcept SNOMED dari kolom kesimpulan_ekg
     * Nilai dari form Vue:
     *   'Electrocardiogram normal'   → SNOMED 164854000
     *   'Electrocardiogram abnormal' → SNOMED 102594003
     */
    private function resolveValueCode(string $kesimpulan): array
    {
        return match($kesimpulan) {
            'Electrocardiogram normal' => $this->valueCodeMap['normal'],
            default                    => $this->valueCodeMap['abnormal'],
        };
    }

    /**
     * Tentukan ICD-10 untuk Condition.
     *
     * Nilai dari form:
     *   irama    : 'sinus' | 'aritmia'
     *   segmen_st: 'normal' | 'elevasi' | 'depresi'
     *
     * Prioritas: segmen_st elevasi/depresi (lebih spesifik) → irama → fallback
     */
    private function resolveCondition(?string $irama, ?string $segmenSt): array
    {
        // Segmen ST elevasi/depresi lebih spesifik secara klinis
        if (!empty($segmenSt) && $segmenSt !== 'normal') {
            $stKey = strtolower(trim($segmenSt));
            if (isset($this->segmenStConditionMap[$stKey])) {
                return $this->segmenStConditionMap[$stKey];
            }
        }

        // Irama: 'sinus' → Z03.5 (observasi), 'aritmia' → R00.8
        if (!empty($irama)) {
            return match(strtolower(trim($irama))) {
                'sinus'   => $this->iramaConditionMap['sinus_normal'],
                'aritmia' => ['code' => 'R00.8', 'display' => 'Other abnormalities of heart beat'],
                default   => $this->iramaConditionMap['sinus_normal'],
            };
        }

        return $this->iramaConditionMap['sinus_normal'];
    }

    public function sendEkg(string $idSkrining): array
    {
        $skrining = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $ekg      = SimpusEkg::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        // ─── Observation ─────────────────────────────────────────────
        $observationId = null;
        $existingObsId = $this->findExistingObservation($encounterId);

        if ($existingObsId) {
            Log::info('Observation EKG sudah ada, skip', ['observation_id' => $existingObsId]);
            $observationId = $existingObsId;
        } else {
            // Resolve SNOMED CT dari kesimpulan_ekg
            // Nilai DB: 'Electrocardiogram normal' atau 'Electrocardiogram abnormal'
            $valueCode = $this->resolveValueCode($ekg->kesimpulan_ekg ?? 'Electrocardiogram abnormal');

            $observationPayload = [
                'resourceType'         => 'Observation',
                'status'               => 'final',
                'category'             => [[
                    'coding' => [[
                        'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                        'code'    => 'procedure',
                        'display' => 'Procedure',
                    ]],
                ]],
                'code'                 => [
                    'coding' => [[
                        'system'  => 'http://loinc.org',
                        'code'    => '34534-8',
                        'display' => '12 lead EKG panel',
                    ]],
                ],
                'subject'              => ['reference' => "Patient/{$patientId}"],
                'encounter'            => ['reference' => "Encounter/{$encounterId}"],
                'effectiveDateTime'    => now()->toIso8601String(),
                'performer'            => [[
                    'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
                ]],
                'valueCodeableConcept' => [
                    'coding' => [[
                        'system'  => 'http://snomed.info/sct',
                        'code'    => $valueCode['code'],
                        'display' => $valueCode['display'],
                    ]],
                ],
            ];

            // Tambahkan component heart rate jika ada
            if (!is_null($ekg->hr)) {
                $observationPayload['component'] = [[
                    'code' => [
                        'coding' => [[
                            'system'  => 'http://loinc.org',
                            'code'    => '8867-4',
                            'display' => 'Heart rate',
                        ]],
                    ],
                    'valueQuantity' => [
                        'value'  => (float) $ekg->hr,
                        'unit'   => 'beats/minute',
                        'system' => 'http://unitsofmeasure.org',
                        'code'   => '/min',
                    ],
                ]];
            }

            $observationId = $this->createObservation($observationPayload);
            Log::info('Observation EKG berhasil', ['observation_id' => $observationId]);
        }

        // ─── Condition ───────────────────────────────────────────────
        $conditionId = null;
        $kesimpulan  = strtolower(trim($ekg->kesimpulan_ekg ?? ''));

        if ($kesimpulan === 'Electrocardiogram normal') {
            Log::info('Condition EKG skip, kesimpulan EKG normal');
        } else {
            // ICD-10 ditentukan dari segmen_st (prioritas) atau irama
            $icd                 = $this->resolveCondition($ekg->irama, $ekg->segmen_st);
            $existingConditionId = $this->findExistingCondition($encounterId, $icd['code']);

            if ($existingConditionId) {
                Log::info('Condition EKG sudah ada, skip', ['condition_id' => $existingConditionId]);
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
                Log::info('Condition EKG berhasil', ['condition_id' => $conditionId]);
            }
        }

        $ekg->update([
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
