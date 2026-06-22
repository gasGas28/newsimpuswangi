<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusKolorektal;

class KolorektalQuestionnaireService
{
    /**
     * Questionnaire ID resmi SATUSEHAT untuk Kanker Kolorektal
     */
    private const QUESTIONNAIRE_ID = 'https://fhir.kemkes.go.id/Questionnaire/Q0020';

    /**
     * Kode LOINC untuk masing-masing Observation
     */
    private const LOINC_COLOK_DUBUR  = '32457-3'; // Digital rectal exam
    private const LOINC_DARAH_SAMAR  = '14563-1'; // Hemoglobin [Presence] in Stool (FOBT)

    /**
     * Mapping hasil_kuesioner ke ICD-10 untuk Condition
     */
    private array $riskConditionMap = [
        'Low'      => ['code' => '723505004', 'display' => 'Encounter for screening for malignant neoplasm of colon'],
        'Moderate' => ['code' => '25594002',  'display' => 'Family history of malignant neoplasm of digestive organs'],
        'High'     => ['code' => '723509005','display' => 'Personal history of other malignant neoplasm of large intestine'],
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

    private function findExistingObservation(string $encounterId, string $loincCode): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Observation', [
                'encounter' => $encounterId,
                'code'      => $loincCode,
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
            throw new \Exception('Gagal membuat QuestionnaireResponse Kolorektal: ' . $response->body());
        }

        return $response->json('id');
    }

    private function createObservation(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Observation', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Observation Kolorektal: ' . $response->body());
        }

        return $response->json('id');
    }

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Condition', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition Kolorektal: ' . $response->body());
        }

        return $response->json('id');
    }

    /**
     * Observation colok dubur — valueCodeableConcept Normal/Curiga
     * Nilai DB: 'normal' | 'curiga'
     */
    private function buildColokDuburObservation(string $patientId, string $encounterId, string $nilai): array
    {
        $valueMap = [
            'normal' => ['code' => '300870000', 'display' => 'No mass present', 'system' => 'http://snomed.info/sct'],
            'curiga' => ['code' => '248523006', 'display' => 'Rectal mass', 'system' => 'http://snomed.info/sct'],
        ];

        $valueCoding = $valueMap[strtolower($nilai)] ?? $valueMap['curiga'];

        return [
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
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '410007005',
                    'display' => 'Digital rectal exam',
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
                    'system'  => $valueCoding['system'],
                    'code'    => $valueCoding['code'],
                    'display' => $valueCoding['display'],
                ]],
            ],
        ];
    }

    /**
     * Observation darah samar feses (FOBT) — valueCodeableConcept Positif/Negatif
     * Nilai DB: 'positif' | 'negatif'
     */
    private function buildDarahSamarObservation(string $patientId, string $encounterId, string $nilai): array
    {
        $valueMap = [
            'negatif' => ['code' => '167667006', 'display' => 'Occult blood not detected in feces', 'system' => 'http://snomed.info/sct'],
            'positif' => ['code' => '59614000',  'display' => 'Occult blood detected in feces', 'system' => 'http://snomed.info/sct'],
        ];

        $valueCoding = $valueMap[strtolower($nilai)] ?? $valueMap['negatif'];

        return [
            'resourceType'         => 'Observation',
            'status'               => 'final',
            'category'             => [[
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                    'code'    => 'laboratory',
                    'display' => 'Laboratory',
                ]],
            ]],
            'code'                 => [
                'coding' => [[
                    'system'  => 'http://loinc.org',
                    'code'    => self::LOINC_DARAH_SAMAR,
                    'display' => 'Hemoglobin [Presence] in Stool',
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
                    'system'  => $valueCoding['system'],
                    'code'    => $valueCoding['code'],
                    'display' => $valueCoding['display'],
                ]],
            ],
        ];
    }

    public function sendKolorektal(string $idSkrining): array
    {
        $skrining   = SimpusSkriningPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $kolorektal = SimpusKolorektal::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        // ─── QuestionnaireResponse ────────────────────────────────────
        $questionnaireResponseId = null;
        $existingQrId            = $this->findExistingQuestionnaireResponse($encounterId);

        if ($existingQrId) {
            Log::info('QuestionnaireResponse Kolorektal sudah ada, skip', ['qr_id' => $existingQrId]);
            $questionnaireResponseId = $existingQrId;
        } else {
            // Nilai kkr1/kkr2 dari form: 'true' | 'false' (string)
            $items = [
                [
                    'linkId' => 'kkr1',
                    'text'   => 'Riwayat keluarga generasi pertama kanker kolorektal?',
                    'answer' => [[
                        'valueBoolean' => filter_var($kolorektal->kuesioner1, FILTER_VALIDATE_BOOLEAN),
                    ]],
                ],
                [
                    'linkId' => 'kkr2',
                    'text'   => 'Apakah peserta merokok?',
                    'answer' => [[
                        'valueBoolean' => filter_var($kolorektal->kuesioner2, FILTER_VALIDATE_BOOLEAN),
                    ]],
                ],
                [
                    'linkId' => 'hasil',
                    'text'   => 'Hasil Skoring Kolorektal',
                    'answer' => [[
                        'valueString' => $kolorektal->hasil_kuesioner,
                    ]],
                ],
            ];

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

            Log::info('QuestionnaireResponse Kolorektal berhasil', ['qr_id' => $questionnaireResponseId]);
        }

        // ─── Observation Colok Dubur ──────────────────────────────────
        $colokDuburObsId = null;

        if (!empty($kolorektal->colok_dbr)) {
            $existingColokId = $this->findExistingObservation($encounterId, self::LOINC_COLOK_DUBUR);

            if ($existingColokId) {
                Log::info('Observation Colok Dubur sudah ada, skip', ['observation_id' => $existingColokId]);
                $colokDuburObsId = $existingColokId;
            } else {
                $colokDuburObsId = $this->createObservation(
                    $this->buildColokDuburObservation($patientId, $encounterId, $kolorektal->colok_dbr)
                );
                Log::info('Observation Colok Dubur berhasil', ['observation_id' => $colokDuburObsId]);
            }
        } else {
            Log::info('Observation Colok Dubur skip, tidak dilakukan');
        }

        // ─── Observation Darah Samar ──────────────────────────────────
        $darahSamarObsId = null;

        if (!empty($kolorektal->darah_samar)) {
            $existingDarahId = $this->findExistingObservation($encounterId, self::LOINC_DARAH_SAMAR);

            if ($existingDarahId) {
                Log::info('Observation Darah Samar sudah ada, skip', ['observation_id' => $existingDarahId]);
                $darahSamarObsId = $existingDarahId;
            } else {
                $darahSamarObsId = $this->createObservation(
                    $this->buildDarahSamarObservation($patientId, $encounterId, $kolorektal->darah_samar)
                );
                Log::info('Observation Darah Samar berhasil', ['observation_id' => $darahSamarObsId]);
            }
        } else {
            Log::info('Observation Darah Samar skip, tidak dilakukan');
        }

        // ─── Condition berdasarkan hasil kuesioner ───────────────────
        $conditionId    = null;
        $hasilKuesioner = $kolorektal->hasil_kuesioner ?? '';

        if (!empty($hasilKuesioner) && isset($this->riskConditionMap[$hasilKuesioner])) {
            $icd                 = $this->riskConditionMap[$hasilKuesioner];
            $existingConditionId = $this->findExistingCondition($encounterId, $icd['code']);

            if ($existingConditionId) {
                Log::info('Condition Kolorektal sudah ada, skip', ['condition_id' => $existingConditionId]);
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
                        'text' => "Hasil skrining kolorektal: {$hasilKuesioner}",
                    ]],
                ]);
                Log::info('Condition Kolorektal berhasil', ['condition_id' => $conditionId]);
            }
        } else {
            Log::info('Condition Kolorektal skip, hasil kuesioner kosong atau tidak dikenali', [
                'hasil_kuesioner' => $hasilKuesioner,
            ]);
        }

        $kolorektal->update([
            'questionnaire_response_id' => $questionnaireResponseId,
            'colok_dubur_observation_id' => $colokDuburObsId,
            'darah_samar_observation_id' => $darahSamarObsId,
            'condition_id'               => $conditionId,
            'sent_at'                    => now(),
        ]);

        return [
            'questionnaire_response_id'  => $questionnaireResponseId,
            'colok_dubur_observation_id' => $colokDuburObsId,
            'darah_samar_observation_id' => $darahSamarObsId,
            'condition_id'               => $conditionId,
        ];
    }
}
