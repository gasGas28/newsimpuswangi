<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;

class DiagnosisConditionService
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

    private function createCondition(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Condition', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition Diagnosis: ' . $response->body());
        }

        return $response->json('id');
    }

    private function buildPayload(
        string $patientId,
        string $encounterId,
        string $icdCode,
        string $icdDisplay,
        string $recordedDate,
    ): array {
        return [
            'resourceType'   => 'Condition',
            'clinicalStatus' => [
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                    'code'    => 'active',
                    'display' => 'Active',
                ]],
            ],
            'category'       => [[
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/condition-category',
                    'code'    => 'encounter-diagnosis',
                    'display' => 'Encounter Diagnosis',
                ]],
            ]],
            'code'           => [
                'coding' => [[
                    'system'  => 'http://hl7.org/fhir/sid/icd-10',
                    'code'    => $icdCode,
                    'display' => $icdDisplay,
                ]],
                'text'   => $icdDisplay,
            ],
            'subject'        => ['reference' => "Patient/{$patientId}"],
            'encounter'      => ['reference' => "Encounter/{$encounterId}"],
            'recordedDate'   => $recordedDate,
            'recorder'       => [
                'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
            ],
        ];
    }

    /**
     * Kirim semua diagnosis dari satu pelayanan ke SATUSEHAT
     * Satu pelayanan bisa punya banyak diagnosis (utama + sekunder)
     *
     * @param  string $pelayananId  → sama dengan idSkrining di simpus_skrining_ptm
     * @param  \Illuminate\Support\Collection $diagnosas  → koleksi model diagnosis
     * @return array
     */
    public function sendDiagnosis(string $pelayananId, $diagnosas): array
    {
        $skrining = KunjunganPTM::where('idPelayanan', $pelayananId)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        $results = [];

        foreach ($diagnosas as $diagnosis) {
            $icdCode    = $diagnosis->kdDiagnosa;
            $icdDisplay = $diagnosis->nmDiagnosa ?? $icdCode;
            $recordedDate = $diagnosis->tglDiagnosa
                ? \Carbon\Carbon::parse($diagnosis->tglDiagnosa)->toIso8601String()
                : now()->toIso8601String();

            // Skip jika kode ICD kosong
            if (empty($icdCode)) {
                Log::warning('Diagnosis skip: kdDiagnosa kosong', [
                    'idDiagnosa' => $diagnosis->idDiagnosa,
                ]);
                $results[] = [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'kdDiagnosa'   => $icdCode,
                    'condition_id' => null,
                    'status'       => 'skip',
                    'message'      => 'kdDiagnosa kosong',
                ];
                continue;
            }

            // Cek apakah sudah pernah dikirim (by id_condition di DB)
            if (!empty($diagnosis->id_condition)) {
                Log::info('Condition sudah ada di DB, skip', [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'condition_id' => $diagnosis->id_condition,
                ]);
                $results[] = [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'kdDiagnosa'   => $icdCode,
                    'condition_id' => $diagnosis->id_condition,
                    'status'       => 'already_exists',
                    'message'      => 'Sudah pernah dikirim sebelumnya',
                ];
                continue;
            }

            // Cek apakah sudah ada di SATUSEHAT by encounter + kode ICD
            $existingId = $this->findExistingCondition($encounterId, $icdCode);

            if ($existingId) {
                Log::info('Condition sudah ada di SATUSEHAT, simpan ID', [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'condition_id' => $existingId,
                ]);

                $diagnosis->update(['id_condition' => $existingId]);

                $results[] = [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'kdDiagnosa'   => $icdCode,
                    'condition_id' => $existingId,
                    'status'       => 'already_exists',
                    'message'      => 'Ditemukan di SATUSEHAT, ID disimpan',
                ];
                continue;
            }

            // Kirim ke SATUSEHAT
            try {
                $payload     = $this->buildPayload(
                    $patientId,
                    $encounterId,
                    $icdCode,
                    $icdDisplay,
                    $recordedDate,
                );
                $conditionId = $this->createCondition($payload);

                $diagnosis->update(['id_condition' => $conditionId]);

                Log::info('Condition Diagnosis berhasil', [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'kdDiagnosa'   => $icdCode,
                    'condition_id' => $conditionId,
                ]);

                $results[] = [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'kdDiagnosa'   => $icdCode,
                    'condition_id' => $conditionId,
                    'status'       => 'berhasil',
                    'message'      => null,
                ];
            } catch (\Exception $e) {
                Log::error('Condition Diagnosis gagal', [
                    'idDiagnosa' => $diagnosis->idDiagnosa,
                    'kdDiagnosa' => $icdCode,
                    'error'      => $e->getMessage(),
                ]);

                $results[] = [
                    'idDiagnosa'   => $diagnosis->idDiagnosa,
                    'kdDiagnosa'   => $icdCode,
                    'condition_id' => null,
                    'status'       => 'gagal',
                    'message'      => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
