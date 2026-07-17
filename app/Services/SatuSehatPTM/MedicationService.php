<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MedicationService
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
     * Cari Medication di SATUSEHAT berdasarkan kode KFA.
     */
    private function findExistingMedication(string $kodeObat): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Medication', [
                'code' => "http://sys-ids.kemkes.go.id/kfa|{$kodeObat}",
            ]);

        if (!$response->successful()) return null;

        $entries = $response->json('entry') ?? [];
        return !empty($entries) ? ($entries[0]['resource']['id'] ?? null) : null;
    }

    private function createMedication(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/Medication', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat Medication: ' . $response->body());
        }

        return $response->json('id');
    }

    private function buildPayload(string $kodeObat, string $namaObat): array
    {
        return [
            'resourceType' => 'Medication',
            'status'       => 'active',
            'code'         => [
                'coding' => [[
                    'system'  => 'http://sys-ids.kemkes.go.id/kfa',
                    'code'    => $kodeObat,
                    'display' => $namaObat,
                ]],
                'text'   => $namaObat,
            ],
            'extension' => [[
                'url'                  => 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
                'valueCodeableConcept' => [
                    'coding' => [[
                        'system'  => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
                        'code'    => 'NC',
                        'display' => 'Non Compound',
                    ]],
                ],
            ]],
        ];
    }

    /**
     * Ambil ID Medication dari SATUSEHAT: cek ke SATUSEHAT dulu (by kode KFA),
     * kalau belum ada baru buat baru. Tidak ada cache di DB — selalu cek live ke SATUSEHAT.
     *
     * @param  string $kodeObat  → kode KFA (KODE_OBAT di simpus_master_obat)
     * @param  string $namaObat
     * @return string
     */
    public function getOrCreateMedication(string $kodeObat, string $namaObat): string
    {
        // Cek dulu apakah Medication dengan kode ini sudah ada di SATUSEHAT
        $existingId = $this->findExistingMedication($kodeObat);

        if ($existingId) {
            Log::info('Medication sudah ada di SATUSEHAT', [
                'kodeObat'     => $kodeObat,
                'medicationId' => $existingId,
            ]);
            return $existingId;
        }

        // Kalau belum ada, buat baru
        $payload = $this->buildPayload($kodeObat, $namaObat);
        $medicationId = $this->createMedication($payload);

        Log::info('Medication berhasil dibuat', [
            'kodeObat'     => $kodeObat,
            'medicationId' => $medicationId,
        ]);

        return $medicationId;
    }
}