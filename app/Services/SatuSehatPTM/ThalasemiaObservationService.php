<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusThalasemia;

class ThalasemiaObservationService
{
    /**
     * Definisi 5 parameter Observation sesuai terminologi resmi SATUSEHAT
     * key        → nama kolom di tabel simpus_thalasemia
     * loinc      → kode LOINC
     * display    → display LOINC
     * unit       → satuan UCUM
     * result_key → nama kolom ID hasil di DB
     */
    private array $parameters = [
        [
            'key'        => 'hemoglobin',
            'loinc'      => '718-7',
            'display'    => 'Hemoglobin [Mass/volume] in Blood',
            'unit'       => 'g/dL',
            'result_key' => 'observation_hb_id',
        ],
        [
            'key'        => 'mcv',
            'loinc'      => '30428-7',
            'display'    => 'MCV [Entitic volume]',
            'unit'       => 'fL',
            'result_key' => 'observation_mcv_id',
        ],
        [
            'key'        => 'mch',
            'loinc'      => '28539-5',
            'display'    => 'MCH [Entitic volume]',
            'unit'       => 'pg',
            'result_key' => 'observation_mch_id',
        ],
        [
            'key'        => 'eritrosit',
            'loinc'      => '789-8',
            'display'    => 'Erythrocytes [#/volume] in Blood by Automated count',
            'unit'       => '10*6/mL',
            'result_key' => 'observation_rbc_id',
        ],
        [
            'key'        => 'rdw',
            'loinc'      => '788-0',
            'display'    => 'Erythrocyte distribution width [Ratio] by Automated count',
            'unit'       => '%',
            'result_key' => 'observation_rdw_id',
        ],
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

    private function createObservation(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Observation', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Observation Thalasemia: ' . $response->body());
        }

        return $response->json('id');
    }

    private function buildObservationPayload(
        string $patientId,
        string $encounterId,
        string $loincCode,
        string $loincDisplay,
        float  $value,
        string $unit
    ): array {
        return [
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
            'effectiveDateTime' => now()->toIso8601String(),
            'performer'         => [[
                'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
            ]],
            'valueQuantity'     => [
                'value'  => $value,
                'unit'   => $unit,
                'system' => 'http://unitsofmeasure.org',
                'code'   => $unit,
            ],
        ];
    }

    public function sendThalasemia(string $idSkrining): array
    {
        $skrining   = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $thalasemia = SimpusThalasemia::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $result      = [];

        foreach ($this->parameters as $param) {
            $nilai = $thalasemia->{$param['key']};

            // Skip jika nilai kosong/null
            if (is_null($nilai)) {
                Log::info("Observation Thalasemia {$param['key']} skip, nilai null");
                continue;
            }

            $existingId = $this->findExistingObservation($encounterId, $param['loinc']);

            if ($existingId) {
                Log::info("Observation Thalasemia {$param['key']} sudah ada, skip", [
                    'observation_id' => $existingId,
                ]);
                $result[$param['result_key']] = $existingId;
                continue;
            }

            $observationId = $this->createObservation(
                $this->buildObservationPayload(
                    $patientId,
                    $encounterId,
                    $param['loinc'],
                    $param['display'],
                    (float) $nilai,
                    $param['unit']
                )
            );

            Log::info("Observation Thalasemia {$param['key']} berhasil", [
                'observation_id' => $observationId,
            ]);

            $result[$param['result_key']] = $observationId;
        }

        $thalasemia->update(array_merge($result, ['sent_at' => now()]));

        return $result;
    }
}
