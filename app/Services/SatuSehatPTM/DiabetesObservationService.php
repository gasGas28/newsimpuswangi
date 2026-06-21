<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusDiabetes;

class DiabetesObservationService
{
    // ✅ Map kategori -> ICD-10
    private array $categoryMap = [
        'normal'     => ['code' => 'Z03.8', 'display' => 'No diagnosis'],
        'prediabetes' => ['code' => 'R73.0', 'display' => 'Prediabetes'],
        'diabetes'   => ['code' => 'E11.9', 'display' => 'Type 2 diabetes mellitus without complications'],
    ];

    // ✅ LOINC map per kolom
    private array $loincMap = [
        'gula_darah_puasa'     => ['code' => '76629-5', 'display' => 'Fasting glucose [Moles/volume] in Blood', 'unit' => 'mg/dL', 'ucum' => 'mg/dL'],
        'gula_darah_2_jam_pp'  => ['code' => '14743-9', 'display' => 'Glucose [Moles/volume] in Capillary blood --2 hours post meal', 'unit' => 'mg/dL', 'ucum' => 'mg/dL'],
        'gula_darah_sewaktu'   => ['code' => '2339-0',  'display' => 'Glucose [Mass/volume] in Blood', 'unit' => 'mg/dL', 'ucum' => 'mg/dL'],
        'hba1c'                => ['code' => '4548-4',  'display' => 'Hemoglobin A1c/Hemoglobin.total in Blood', 'unit' => '%', 'ucum' => '%'],
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

    private function sendBundle(array $entries): array
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url'), [
                'resourceType' => 'Bundle',
                'type'         => 'transaction',
                'entry'        => $entries,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Gagal mengirim Bundle Diabetes: ' . $response->body());
        }

        return $response->json();
    }

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Condition', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition Diabetes: ' . $response->body());
        }

        return $response->json('id');
    }

    private function resolveCategory(string $kategori): array
    {
        $normalized = strtolower(trim($kategori));

        if (!isset($this->categoryMap[$normalized])) {
            Log::warning('resolveCategory Diabetes: tidak dikenali, fallback ke normal', [
                'kategori' => $kategori,
            ]);
            return $this->categoryMap['normal'];
        }

        return $this->categoryMap[$normalized];
    }

    private function buildObservationEntry(
        string $loincCode,
        string $loincDisplay,
        float $value,
        string $unit,
        string $ucumCode,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
    ): array {
        return [
            'fullUrl'  => 'urn:uuid:' . \Str::uuid(),
            'resource' => [
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
                        'code'    => $loincCode,
                        'display' => $loincDisplay,
                    ]],
                ],
                'subject'           => ['reference' => "Patient/{$patientId}"],
                'encounter'         => ['reference' => "Encounter/{$encounterId}"],
                'effectiveDateTime' => $effectiveAt,
                'performer'         => [[
                    'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
                ]],
                'valueQuantity'     => [
                    'value'  => $value,
                    'unit'   => $unit,
                    'system' => 'http://unitsofmeasure.org',
                    'code'   => $ucumCode,
                ],
            ],
            'request' => [
                'method' => 'POST',
                'url'    => 'Observation',
            ],
        ];
    }

    private function buildConditionPayload(array $icd, string $patientId, string $encounterId): array
    {
        return [
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
        ];
    }

    public function sendDiabetes(string $idSkrining): array
    {
        $skrining  = SimpusSkriningPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $diabetes  = SimpusDiabetes::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $effectiveAt = now()->toIso8601String();

        // ─── Observation Bundle ──────────────────────────────────────
        $fields = [
            'gula_darah_puasa'    => $diabetes->gula_darah_puasa,
            'gula_darah_2_jam_pp' => $diabetes->gula_darah_2_jam_pp,
            'gula_darah_sewaktu'  => $diabetes->gula_darah_sewaktu,
            'hba1c'               => $diabetes->hba1c,
        ];

        $entries = [];
        foreach ($fields as $key => $value) {
            // ✅ Skip jika nilai null/kosong
            if (is_null($value)) continue;

            $loinc = $this->loincMap[$key];

            // ✅ Cek duplikat per LOINC
            $existingId = $this->findExistingObservation($encounterId, $loinc['code']);
            if ($existingId) {
                Log::info("Observation {$key} sudah ada, skip", ['observation_id' => $existingId]);
                continue;
            }

            $entries[] = $this->buildObservationEntry(
                loincCode:   $loinc['code'],
                loincDisplay: $loinc['display'],
                value:       (float) $value,
                unit:        $loinc['unit'],
                ucumCode:    $loinc['ucum'],
                patientId:   $patientId,
                encounterId: $encounterId,
                effectiveAt: $effectiveAt,
            );
        }

        if (!empty($entries)) {
            $this->sendBundle($entries);
            Log::info('Bundle Observation Diabetes berhasil', ['total' => count($entries)]);
        }

        // ─── Condition ───────────────────────────────────────────────
        // ✅ Ambil kategori paling berat sebagai Condition utama
        $kategoriUtama = $this->resolveKategoriUtama([
            $diabetes->kategori_gula_darah_puasa,
            $diabetes->kategori_gula_darah_2_jam_pp,
            $diabetes->kategori_gula_darah_sewaktu,
            $diabetes->kategori_hba1c,
        ]);

        $conditionId = null;

        if ($kategoriUtama !== 'normal') {
            $icd = $this->resolveCategory($kategoriUtama);
            $existingConditionId = $this->findExistingCondition($encounterId, $icd['code']);

            if ($existingConditionId) {
                Log::info('Condition Diabetes sudah ada, skip', ['condition_id' => $existingConditionId]);
                $conditionId = $existingConditionId;
            } else {
                $conditionId = $this->createCondition(
                    $this->buildConditionPayload($icd, $patientId, $encounterId)
                );
                Log::info('Condition Diabetes berhasil', ['condition_id' => $conditionId]);
            }
        } else {
            Log::info('Condition Diabetes skip, semua kategori normal');
        }

        $diabetes->update([
            'condition_id' => $conditionId,
            'sent_at'      => now(),
        ]);

        return ['condition_id' => $conditionId];
    }

    // ✅ Prioritas: diabetes > prediabetes > normal
    private function resolveKategoriUtama(array $kategoriList): string
    {
        $priority = ['diabetes' => 2, 'prediabetes' => 1, 'normal' => 0];
        $highest  = 'normal';

        foreach ($kategoriList as $kategori) {
            if (is_null($kategori)) continue;
            $normalized = strtolower(trim($kategori));
            if (($priority[$normalized] ?? 0) > ($priority[$highest] ?? 0)) {
                $highest = $normalized;
            }
        }

        return $highest;
    }
}