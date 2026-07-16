<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;

class HipertensiObservationService
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

    private function createObservation(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(
                config('services.satusehat.fhir_url') . '/Observation',
                $payload
            );

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Observation: ' . $response->body());
        }

        return $response->json('id');
    }

    public function sendBloodPressure(string $idSkrining): array
    {
        $skrining    = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $hipertensi  = SimpusHipertensi::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $effectiveAt = now()->toIso8601String();

        // Cek duplikat — pakai LOINC panel tekanan darah
        $existingId = $this->findExisting($encounterId, '55284-4');
        if ($existingId) {
            Log::info('Observation BloodPressure sudah ada, skip', [
                'observation_id' => $existingId,
            ]);
            return ['observation_id' => $existingId];
        }

        $payload = [
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
                    'code'    => '55284-4',
                    'display' => 'Blood pressure systolic and diastolic',
                ]],
            ],
            'subject'        => ['reference' => "Patient/{$patientId}"],
            'encounter'      => ['reference' => "Encounter/{$encounterId}"],
            'effectiveDateTime' => $effectiveAt,
            'performer'      => [[
                'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
            ]],
            'component' => [
                [
                    'code' => [
                        'coding' => [[
                            'system'  => 'http://loinc.org',
                            'code'    => '8480-6',
                            'display' => 'Systolic blood pressure',
                        ]],
                    ],
                    'valueQuantity' => [
                        'value'  => (int) $hipertensi->sistolik,
                        'unit'   => 'mmHg',
                        'system' => 'http://unitsofmeasure.org',
                        'code'   => 'mm[Hg]',
                    ],
                ],
                [
                    'code' => [
                        'coding' => [[
                            'system'  => 'http://loinc.org',
                            'code'    => '8462-4',
                            'display' => 'Diastolic blood pressure',
                        ]],
                    ],
                    'valueQuantity' => [
                        'value'  => (int) $hipertensi->tekanan_diastolik,
                        'unit'   => 'mmHg',
                        'system' => 'http://unitsofmeasure.org',
                        'code'   => 'mm[Hg]',
                    ],
                ],
            ],
        ];

        $id = $this->createObservation($payload);

        Log::info('Observation BloodPressure berhasil', ['observation_id' => $id]);

        return ['observation_id' => $id];
    }
}
