<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;

class HipertensiConditionService
{
    //  Map kategori_tekanan_darah -> ICD-10 + display
    private array $categoryMap = [
        'normal'             => ['code' => 'Z03.8', 'display' => 'Tekanan darah normal'],
        'elevated'           => ['code' => 'R03.0', 'display' => 'Elevated blood-pressure reading'],
        'hipertensi_grade_1' => ['code' => 'I10',   'display' => 'Essential (primary) hypertension'],
        'hipertensi_grade_2' => ['code' => 'I10',   'display' => 'Essential (primary) hypertension'],
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

    private function findExisting(string $encounterId, string $icdCode): ?string
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
            ->post(
                config('services.satusehat.fhir_url') . '/Condition',
                $payload
            );

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Condition: ' . $response->body());
        }

        return $response->json('id');
    }

    private function resolveCategory(string $kategori): array
    {
        $normalized = strtolower(trim($kategori));
        $normalized = preg_replace('/\s+/', '_', $normalized);

        if (!isset($this->categoryMap[$normalized])) {
            Log::warning('resolveCategory: kategori tidak dikenali, fallback ke normal', [
                'original'   => $kategori,
                'normalized' => $normalized,
            ]);
            return $this->categoryMap['normal'];
        }

        return $this->categoryMap[$normalized];
    }
    public function sendCondition(string $idSkrining): array
    {
        $skrining   = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $hipertensi = SimpusHipertensi::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;

        $icd = $this->resolveCategory($hipertensi->kategori_tekanan_darah);


        $existingId = $this->findExisting($encounterId, $icd['code']);
        if ($existingId) {
            Log::info('Condition Hipertensi sudah ada, skip', [
                'condition_id' => $existingId,
            ]);
            $hipertensi->update([
                'condition_id' => $existingId,
                'sent_at'      => now(),
            ]);
            return ['condition_id' => $existingId];
        }

        $icd = $this->resolveCategory($hipertensi->kategori_tekanan_darah);

        $payload = [
            'resourceType'      => 'Condition',
            'clinicalStatus'    => [
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
            'category' => [[
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/condition-category',
                    'code'    => 'encounter-diagnosis',
                    'display' => 'Encounter Diagnosis',
                ]],
            ]],
            'code' => [
                'coding' => [[
                    'system'  => 'http://hl7.org/fhir/sid/icd-10',
                    'code'    => $icd['code'],
                    'display' => $icd['display'],
                ]],
            ],
            'subject'           => ['reference' => "Patient/{$patientId}"],
            'encounter'         => ['reference' => "Encounter/{$encounterId}"],
            'onsetDateTime'     => now()->toIso8601String(),
            'recorder'          => [
                'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
            ],
        ];

        $id = $this->createCondition($payload);

        Log::info('Condition Hipertensi berhasil', ['condition_id' => $id]);

        $hipertensi->update([
            'condition_id' => $id,
            'sent_at'      => now(),
        ]);


        return ['condition_id' => $id];
    }
}
