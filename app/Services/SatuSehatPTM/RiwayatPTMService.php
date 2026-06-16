<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\FaktorRisiko;

class RiwayatPTMService
{
    // ✅ Constructor di atas semua method
    public function __construct(
        private EncounterService $encounterService
    ) {}

    private array $riwayatPTM = [
        'r_pribadi_htn' => [
            'code'    => '38341003',
            'display' => 'Hypertensive disorder (disorder)',
        ],
        'r_pribadi_dm' => [
            'code'    => '44054006',
            'display' => 'Diabetes mellitus type 2 (disorder)',
        ],
        'r_pribadi_stroke' => [
            'code'    => '230690007',
            'display' => 'Cerebrovascular accident (disorder)',
        ],
        'r_pribadi_jantung' => [
            'code'    => '56265001',
            'display' => 'Heart disease (disorder)',
        ],
        'r_keluarga_htn' => [
            'code'    => '160303001',
            'display' => 'Family history of hypertension (situation)',
        ],
        'r_keluarga_dm' => [
            'code'    => '160302006',
            'display' => 'Family history of diabetes mellitus (situation)',
        ],
        'r_keluarga_stroke' => [
            'code'    => '312824007',
            'display' => 'Family history of stroke (situation)',
        ],
        'r_keluarga_jantung' => [
            'code'    => '266894000',
            'display' => 'Family history of heart disease (situation)',
        ],
    ];

    private ?string $cachedToken = null;

    private function getToken(): string
    {
        if (!$this->cachedToken) {
            $this->cachedToken = $this->encounterService->getAccessToken();
        }
        return $this->cachedToken;
    }

    // Ubah findExistingCondition() agar return array, bukan hanya id
    private function findExistingCondition(string $encounterId, string $snomedCode): ?array
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Condition', [
                'encounter' => $encounterId,
                'code'      => $snomedCode,
            ]);

        if (!$response->successful()) {
            return null;
        }

        $entries = $response->json('entry') ?? [];

        if (empty($entries)) {
            return null;
        }

        $resource = $entries[0]['resource'] ?? [];

        return [
            'condition_id'    => $resource['id'] ?? null,
            'clinical_status' => $resource['clinicalStatus']['coding'][0]['code'] ?? null,
        ];
    }

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(
                config('services.satusehat.fhir_url') . '/Condition',
                $payload
            );

        if (!$response->successful()) {
            throw new \Exception(
                'Gagal membuat Condition: ' . $response->body()
            );
        }

        return $response->json('id');
    }

    private function buildPayload(
        string $patientId,
        string $encounterId,
        string $code,
        string $display,
        string $clinicalStatus,
    ): array {
        return [
            'resourceType' => 'Condition',
            'clinicalStatus' => [
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                    'code'   => $clinicalStatus,
                ]],
            ],
            'category' => [[
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/condition-category',
                    'code'    => 'problem-list-item',
                    'display' => 'Problem List Item',
                ]],
            ]],
            'code' => [
                'coding' => [[
                    'system'  => 'http://snomed.info/sct',
                    'code'    => $code,
                    'display' => $display,
                ]],
                'text' => $display,
            ],
            'subject'  => ['reference' => "Patient/{$patientId}"],
            'encounter' => ['reference' => "Encounter/{$encounterId}"],
            'recorder' => ['reference' => "Practitioner/" . config('services.satusehat.practitioner_id')],
        ];
    }

    public function sendRiwayat(string $idSkrining): array
    {
        $skrining     = SimpusSkriningPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $faktorRisiko = FaktorRisiko::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        $results = ['riwayat_ptm' => []];

        foreach ($this->riwayatPTM as $field => $snomed) {

            if (is_null($faktorRisiko->$field)) {
                Log::info("Condition skipped (null): {$field}");
                continue;
            }

            $existing = $this->findExistingCondition($encounterId, $snomed['code']);
            if ($existing) {
                Log::info("Condition sudah ada, skip: {$field}", $existing);

                $results['riwayat_ptm'][$field] = [
                    'condition_id'    => $existing['condition_id'],
                    'status'          => 'already_exists',
                    'clinical_status' => $existing['clinical_status'], 
                    'error'           => null,
                ];
                continue;
            }

            $clinicalStatus = $faktorRisiko->$field ? 'active' : 'inactive';
            $payload        = $this->buildPayload(
                $patientId,
                $encounterId,
                $snomed['code'],
                $snomed['display'],
                $clinicalStatus,
            );

            try {
                $conditionId = $this->createCondition($payload);
                Log::info("Condition berhasil: {$field}", ['condition_id' => $conditionId]);

                // ✅ Struktur konsisten, payload tidak ikut response
                $results['riwayat_ptm'][$field] = [
                    'condition_id'    => $conditionId,
                    'status'          => 'berhasil',
                    'clinical_status' => $clinicalStatus,
                    'error'           => null,
                ];
            } catch (\Exception $e) {
                Log::error("Condition gagal: {$field}", [
                    'error'   => $e->getMessage(),
                    'payload' => $payload,  // payload hanya di log, tidak di response
                ]);

                $results['riwayat_ptm'][$field] = [
                    'condition_id'    => null,
                    'status'          => 'gagal',
                    'clinical_status' => $clinicalStatus,
                    'error'           => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
