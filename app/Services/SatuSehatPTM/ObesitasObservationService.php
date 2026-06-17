<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusObesitas;

class ObesitasObservationService
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

    private function findExisting(string $encounterId, string $loincCode): ?string
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

    private function sendBundle(array $payload): array
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(
                config('services.satusehat.fhir_url'),
                $payload
            );

        if (!$response->successful()) {
            throw new \Exception('Gagal mengirim Bundle Antropometri: ' . $response->body());
        }

        return $response->json();
    }

    private function buildObservation(
        string $loincCode,
        string $loincDisplay,
        float $value,
        string $unit,
        string $ucumCode,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $fullUrl,
    ): array {
        return [
            'fullUrl'  => $fullUrl,
            'resource' => [
                'resourceType' => 'Observation',
                'status'       => 'final',
                'category'     => [[
                    'coding' => [[
                        'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                        'code'    => 'vital-signs',
                        'display' => 'Vital Signs',
                    ]],
                ]],
                'code' => [
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
                'valueQuantity' => [
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

    public function sendAntropometri(string $idSkrining): array
    {
        $skrining  = SimpusSkriningPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $obesitas  = SimpusObesitas::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $effectiveAt = now()->toIso8601String();

        $existingId = $this->findExisting($encounterId, '39156-5');
        if ($existingId) {
            Log::info('Observation Antropometri sudah ada, skip', [
                'observation_id' => $existingId,
            ]);

            $obesitas->update(['sent_at' => now()]);

            return ['observation_id' => $existingId];
        }

        $entries = [
            $this->buildObservation(
                loincCode:    '29463-7',
                loincDisplay: 'Body weight',
                value:        (float) $obesitas->berat_badan,
                unit:         'kg',
                ucumCode:     'kg',
                patientId:    $patientId,
                encounterId:  $encounterId,
                effectiveAt:  $effectiveAt,
                fullUrl:      'urn:uuid:' . \Str::uuid(),
            ),
            $this->buildObservation(
                loincCode:    '8302-2',
                loincDisplay: 'Body height',
                value:        (float) $obesitas->tinggi_badan,
                unit:         'cm',
                ucumCode:     'cm',
                patientId:    $patientId,
                encounterId:  $encounterId,
                effectiveAt:  $effectiveAt,
                fullUrl:      'urn:uuid:' . \Str::uuid(),
            ),
            $this->buildObservation(
                loincCode:    '39156-5',
                loincDisplay: 'Body mass index (BMI) [Ratio]',
                value:        (float) $obesitas->imt,
                unit:         'kg/m2',
                ucumCode:     'kg/m2',
                patientId:    $patientId,
                encounterId:  $encounterId,
                effectiveAt:  $effectiveAt,
                fullUrl:      'urn:uuid:' . \Str::uuid(),
            ),
            $this->buildObservation(
                loincCode:    '56086-2',
                loincDisplay: 'Waist circumference',
                value:        (float) $obesitas->lingkar_pinggang,
                unit:         'cm',
                ucumCode:     'cm',
                patientId:    $patientId,
                encounterId:  $encounterId,
                effectiveAt:  $effectiveAt,
                fullUrl:      'urn:uuid:' . \Str::uuid(),
            ),
        ];

        $bundlePayload = [
            'resourceType' => 'Bundle',
            'type'         => 'transaction',
            'entry'        => $entries,
        ];

        try {
            $result = $this->sendBundle($bundlePayload);

            $observationId = $result['entry'][2]['response']['location'] ?? null;
            if ($observationId) {
                $observationId = last(explode('/', $observationId));
            }

            Log::info('Bundle Antropometri berhasil', [
                'observation_imt_id' => $observationId,
            ]);

            $obesitas->update([
                'observation_id' => $observationId,
                'sent_at'        => now(),
            ]);

            return ['observation_id' => $observationId];

        } catch (\Exception $e) {
            Log::error('Bundle Antropometri gagal', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}