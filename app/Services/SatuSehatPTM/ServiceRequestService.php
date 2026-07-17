<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ServiceRequestService
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

    private function createServiceRequest(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/ServiceRequest', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat ServiceRequest: ' . $response->body());
        }

        return $response->json('id');
    }

    /**
     * Map value form 'rujukan' ke deskripsi kode/kategori ServiceRequest.
     */
    private function mapRencanaRujuk(string $rujukan): array
    {
        return match ($rujukan) {
            'internal' => [
                'display' => 'Konsultasi internal puskesmas',
            ],
            'fkrtl' => [
                'display' => 'Rujuk ke FKRTL',
            ],
            'igd' => [
                'display' => 'Rujuk segera / IGD',
            ],
            default => [
                'display' => 'Tindak lanjut lainnya',
            ],
        };
    }

    /**
     * Kirim rencana tindak lanjut (rujukan + jadwal kontrol + transportasi) sebagai ServiceRequest.
     *
     * @param  string      $patientId
     * @param  string      $patientName
     * @param  string      $encounterId
     * @param  string      $practitionerId
     * @param  string      $rencanaRujuk    tidak | internal | fkrtl | igd
     * @param  string|null $jadwalKontrol   format Y-m-d
     * @param  string      $transport       tidak_berlaku | ambulan | kendaraan_pribadi | ojek
     * @param  string      $authoredOn      ISO8601 datetime
     * @return string|null  ID ServiceRequest, null jika rencanaRujuk = 'tidak' dan tidak ada jadwal kontrol
     */
    public function sendRencanaTindakLanjut(
        string $patientId,
        string $patientName,
        string $encounterId,
        string $practitionerId,
        string $rencanaRujuk,
        ?string $jadwalKontrol,
        string $transport,
        string $authoredOn,
    ): ?string {
        // Kalau tidak ada rencana rujuk & tidak ada jadwal kontrol, tidak perlu kirim apa pun
        if ($rencanaRujuk === 'tidak' && empty($jadwalKontrol)) {
            Log::info('ServiceRequest skip: tidak ada rencana rujuk maupun jadwal kontrol');
            return null;
        }

        $mapped = $this->mapRencanaRujuk($rencanaRujuk);

        $transportDisplay = match ($transport) {
            'ambulance'           => 'Ambulans',
            'kendaraan_pribadi' => 'Kendaraan pribadi',
            'ojek'              => 'Ojek/taksi',
            default             => 'Tidak berlaku',
        };

        $payload = [
            'resourceType' => 'ServiceRequest',
            'status'       => 'active',
            'intent'       => 'plan',
            'category'     => [[
                'coding' => [[
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '306206005',
                    'display' => 'Referral for treatment',
                ]],
            ]],
            'code' => [
                'coding' => [[
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '306206005',
                    'display' => 'Referral for treatment',
                ]],
                'text' => $mapped['display'],
            ],
            'subject' => [
                'reference' => "Patient/{$patientId}",
                'display'   => $patientName,
            ],
            'encounter' => [
                'reference' => "Encounter/{$encounterId}",
            ],
            'requester' => [
                'reference' => "Practitioner/{$practitionerId}",
            ],
            'performer' => [[
                'reference' => 'Organization/' . config('services.satusehat.organization_id'),
            ]],
            'authoredOn' => $authoredOn,
            'note' => [[
                'text' => 'Transportasi rujuk: ' . $transportDisplay,
            ]],
        ];

        if (!empty($jadwalKontrol)) {
            $payload['occurrenceDateTime'] = \Carbon\Carbon::parse($jadwalKontrol)->toIso8601String();
        }

        $serviceRequestId = $this->createServiceRequest($payload);

        Log::info('ServiceRequest rencana tindak lanjut berhasil', [
            'serviceRequestId' => $serviceRequestId,
            'rencanaRujuk'     => $rencanaRujuk,
        ]);

        return $serviceRequestId;
    }
}
