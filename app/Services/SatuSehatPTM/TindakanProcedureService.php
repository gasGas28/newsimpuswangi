<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;

class TindakanProcedureService
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
     * Cek apakah Procedure tindakan dengan kode ICD-9-CM tertentu
     * sudah ada di SATUSEHAT untuk encounter ini.
     */
    private function findExistingProcedure(string $encounterId, string $icd9Code): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Procedure', [
                'encounter' => $encounterId,
                'code'      => "http://hl7.org/fhir/sid/icd-9-cm|{$icd9Code}",
            ]);

        if (!$response->successful()) return null;

        $entries = $response->json('entry') ?? [];
        return !empty($entries) ? ($entries[0]['resource']['id'] ?? null) : null;
    }

    private function createProcedure(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Procedure', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Procedure Tindakan: ' . $response->body());
        }

        return $response->json('id');
    }

    private function buildPayload(
        string $patientId,
        string $encounterId,
        string $icd9Code,
        string $icd9Display,
        string $performedDate,
        string $ihsnakes,
    ): array {
        return [
            'resourceType'      => 'Procedure',
            'status'            => 'completed',
            'category'          => [
                'coding' => [[
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '277132007',
                    'display' => 'Therapeutic procedure',
                ]],
            ],
            'code'              => [
                'coding' => [[
                    'system'  => 'http://hl7.org/fhir/sid/icd-9-cm',
                    'code'    => $icd9Code,
                    'display' => $icd9Display,
                ]],
                'text'   => $icd9Display,
            ],
            'subject'           => ['reference' => "Patient/{$patientId}"],
            'encounter'         => ['reference' => "Encounter/{$encounterId}"],
            'performedDateTime' => $performedDate,
            'performer'         => [[
                'actor' => [
                    'reference' => 'Practitioner/' . $ihsnakes,
                ],
            ]],
            'location'          => [
                'reference' => 'Location/' . config('services.satusehat.location_id'),
            ],
        ];
    }

    /**
     * Kirim semua data tindakan dari satu pelayanan ke SATUSEHAT
     * Satu pelayanan bisa punya lebih dari satu tindakan
     *
     * @param  string $pelayananId → sama dengan idPelayanan di simpus_skrining_ptm
     * @return array
     */
    public function sendTindakan(string $pelayananId): array
    {
        $skrining = KunjunganPTM::where('idPelayanan', $pelayananId)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $ihsnakes = $skrining->id_petugas;

        $tindakanList = SimpusTindakan::where('idPelayanan', $pelayananId)->get();

        if ($tindakanList->isEmpty()) {
            Log::info('Tidak ada data tindakan untuk pelayanan', ['idPelayanan' => $pelayananId]);
            return [];
        }

        $results = [];

        foreach ($tindakanList as $tindakan) {
            $icd9Code    = $tindakan->kdTindakan;
            $icd9Display = $tindakan->nmTindakan ?? $icd9Code;
            $performedDate = now()->toIso8601String();

            // Skip jika kode ICD-9-CM kosong
            if (empty($icd9Code)) {
                Log::warning('Tindakan skip: kdTindakan kosong', [
                    'pelayanan' => $tindakan->idPelayanan,
                ]);
                $results[] = [
                    'id'          => $tindakan->KdTindakan,
                    'kdTindakan'  => $icd9Code,
                    'procedureId' => null,
                    'status'      => 'skip',
                    'message'     => 'kdTindakan kosong',
                ];
                continue;
            }

            // Cek apakah sudah pernah dikirim (by procedureId di DB)
            if (!empty($tindakan->procedureId)) {
                Log::info('Procedure tindakan sudah ada di DB, skip', [
                    'id'          => $tindakan->kdTindakan,
                    'procedureId' => $tindakan->procedureId,
                ]);
                $results[] = [
                    'id'          => $tindakan->KdTindakan,
                    'kdTindakan'  => $icd9Code,
                    'procedureId' => $tindakan->procedureId,
                    'status'      => 'already_exists',
                    'message'     => 'Sudah pernah dikirim sebelumnya',
                ];
                continue;
            }

            // Cek apakah sudah ada di SATUSEHAT by encounter + kode ICD-9-CM
            $existingId = $this->findExistingProcedure($encounterId, $icd9Code);

            if ($existingId) {
                Log::info('Procedure tindakan sudah ada di SATUSEHAT, simpan ID', [
                    'id'          => $tindakan->kdTindakan,
                    'procedureId' => $existingId,
                ]);

                $tindakan->update(['procedureId' => $existingId]);

                $results[] = [
                    'id'          => $tindakan->kdTindakan,
                    'kdTindakan'  => $icd9Code,
                    'procedureId' => $existingId,
                    'status'      => 'already_exists',
                    'message'     => 'Ditemukan di SATUSEHAT, ID disimpan',
                ];
                continue;
            }

            // Kirim ke SATUSEHAT
            try {
                $payload     = $this->buildPayload(
                    $patientId,
                    $encounterId,
                    $icd9Code,
                    $icd9Display,
                    $performedDate,
                    $ihsnakes
                );
                $procedureId = $this->createProcedure($payload);

                $tindakan->update(['procedureId' => $procedureId]);

                Log::info('Procedure Tindakan berhasil', [
                    'id'          => $tindakan->KdTindakan,
                    'kdTindakan'  => $icd9Code,
                    'procedureId' => $procedureId,
                ]);

                $results[] = [
                    'id'          => $tindakan->id,
                    'kdTindakan'  => $icd9Code,
                    'procedureId' => $procedureId,
                    'status'      => 'berhasil',
                    'message'     => null,
                ];
            } catch (\Exception $e) {
                Log::error('Procedure Tindakan gagal', [
                    'id'         => $tindakan->id,
                    'kdTindakan' => $icd9Code,
                    'error'      => $e->getMessage(),
                ]);

                $results[] = [
                    'id'          => $tindakan->id,
                    'kdTindakan'  => $icd9Code,
                    'procedureId' => null,
                    'status'      => 'gagal',
                    'message'     => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}