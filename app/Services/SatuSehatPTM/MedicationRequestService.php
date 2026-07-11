<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SimpusResepObat;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;

class MedicationRequestService
{
    public function __construct(
        private EncounterService $encounterService,
        private MedicationService $medicationService,
    ) {}

    private ?string $cachedToken = null;

    private function getToken(): string
    {
        if (!$this->cachedToken) {
            $this->cachedToken = $this->encounterService->getAccessToken();
        }
        return $this->cachedToken;
    }

    private function createMedicationRequest(array $payload): string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url') . '/MedicationRequest', $payload);

        if (!$response->successful()) {
            throw new \Exception('Gagal membuat MedicationRequest: ' . $response->body());
        }

        return $response->json('id');
    }

    private function buildPayload(
        string $patientId,
        string $patientName,
        string $encounterId,
        string $medicationId,
        string $medicationDisplay,
        string $practitionerId,
        string $practitionerName,
        ?string $conditionId,
        ?string $conditionDisplay,
        int $jumlah,
        string $satuan,
        string $aturanPakai,
        int $frekuensi,
        string $authoredOn,
    ): array {
        $payload = [
            'resourceType' => 'MedicationRequest',
            'status'       => 'completed',
            'intent'       => 'order',
            'priority'     => 'routine',
            'authoredOn'   => $authoredOn,
            'category'     => [[
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/medicationrequest-category',
                    'code'    => 'community',
                    'display' => 'Community',
                ]],
            ]],
            'subject'   => [
                'reference' => "Patient/{$patientId}",
                'display'   => $patientName,
            ],
            'encounter' => ['reference' => "Encounter/{$encounterId}"],
            'requester' => [
                'reference' => "Practitioner/{$practitionerId}",
                'display'   => $practitionerName,
            ],
            'medicationReference' => [
                'reference' => "Medication/{$medicationId}",
                'display'   => $medicationDisplay,
            ],
            'dosageInstruction' => [[
                'sequence' => 1,
                'route'    => [
                    'coding' => [[
                        'system'  => 'http://www.whocc.no/atc',
                        'code'    => 'O',
                        'display' => 'Oral',
                    ]],
                ],
                'timing' => [
                    'repeat' => [
                        'frequency'  => $frekuensi,
                        'period'     => 1,
                        'periodUnit' => 'd',
                    ],
                ],
                'doseAndRate' => [[
                    'type' => [
                        'coding' => [[
                            'system'  => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                            'code'    => 'ordered',
                            'display' => 'Ordered',
                        ]],
                    ],
                    'doseQuantity' => [
                        'value'  => 1,
                        'unit'   => $satuan,
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                        'code'   => 'TAB',
                    ],
                ]],
                'patientInstruction' => $aturanPakai,
            ]],
            'dispenseRequest' => [
                'quantity' => [
                    'value'  => $jumlah,
                    'unit'   => $satuan,
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                    'code'   => 'TAB',
                ],
                'performer' => [
                    'reference' => 'Organization/' . config('services.satusehat.organization_id'),
                ],
            ],
        ];

        if ($conditionId) {
            $payload['reasonReference'] = [[
                'reference' => "Condition/{$conditionId}",
                'display'   => $conditionDisplay,
            ]];
        }

        return $payload;
    }

    /**
     * Kirim semua resep obat dari satu pelayanan ke SATUSEHAT.
     *
     * @param  string $idPelayanan
     * @return array
     */
    public function sendResepObat(string $idPelayanan): array
    {
        $skrining = KunjunganPTM::select(
            'simpus_kunjungan_ptm.*',
            'simpus_pasien.NAMA_LGKP',
            'master_dokter.nmDokter',
        )->join('simpus_pasien', 'simpus_kunjungan_ptm.nik_pasien', '=', 'simpus_pasien.NIK')
            ->join('master_dokter', 'simpus_kunjungan_ptm.id_petugas', '=', 'master_dokter.ihs_nakes')
            ->where('idPelayanan', $idPelayanan)->get();

        $resepList = SimpusResepObat::select(
            'simpus_resep_obat.*',
            'simpus_resep_detail.obat_id',
            'simpus_resep_detail.jumlah',
            'simpus_resep_detail.dosis_pakai',
            'simpus_resep_detail.tiapJam',
            'simpus_resep_detail.medicationrequest_id',
            'simpus_master_obat.NAMA',
            'simpus_master_obat.SATUAN',
            'simpus_master_obat.KODE_OBAT',
        )
            ->join('simpus_resep_detail', 'simpus_resep_obat.id_resep', '=', 'simpus_resep_detail.resep_id')
            ->join('simpus_master_obat', 'simpus_resep_detail.obat_id', '=', 'simpus_master_obat.OBAT_ID')
            ->where('pelayananId', $idPelayanan)
            ->get();

        if ($resepList->isEmpty()) {
            Log::info('Tidak ada resep obat untuk pelayananId', ['pelayananId' => $idPelayanan]);
            return [];
        }

        $results = [];

        foreach ($resepList as $resep) {
            // Skip jika sudah pernah dikirim (masih dicek by medicationrequest_id di DB)
            if (!empty($resep->medicationrequest_id)) {
                Log::info('MedicationRequest sudah ada di DB, skip', [
                    'obat_id'              => $resep->obat_id,
                    'medicationrequest_id' => $resep->medicationrequest_id,
                ]);
                $results[] = [
                    'obat_id'              => $resep->obat_id,
                    'medicationrequest_id' => $resep->medicationrequest_id,
                    'status'               => 'already_exists',
                    'message'              => 'Sudah pernah dikirim sebelumnya',
                ];
                continue;
            }

            if (empty($resep->KODE_OBAT)) {
                Log::warning('Resep skip: KODE_OBAT kosong', ['obat_id' => $resep->obat_id]);
                $results[] = [
                    'obat_id'              => $resep->obat_id,
                    'medicationrequest_id' => null,
                    'status'               => 'skip',
                    'message'              => 'KODE_OBAT kosong, Medication tidak bisa dibuat',
                ];
                continue;
            }

            try {
                // 1. Pastikan Medication sudah ada / buat baru (selalu cek live ke SATUSEHAT, tanpa cache DB)
                $medicationId = $this->medicationService->getOrCreateMedication(
                    $resep->KODE_OBAT,
                    $resep->NAMA,
                );

                // 2. Buat MedicationRequest
                $payload = $this->buildPayload(
                    patientId: $skrining->patient_id,
                    patientName: $skrining->NAMA_LGKP ?? '',
                    encounterId: $skrining->encounter_id,
                    medicationId: $medicationId,
                    medicationDisplay: $resep->NAMA,
                    practitionerId: $skrining->id_petugas,
                    practitionerName: $skrining->nmDokter,
                    conditionId: $resep->id_condition ?? null,
                    conditionDisplay: null,
                    jumlah: (int) $resep->jumlah,
                    satuan: $resep->SATUAN ?? 'Tablet',
                    aturanPakai: $resep->dosis_pakai ?? '',
                    frekuensi: (int) ($resep->tiapJam ?? 1),
                    authoredOn: now()->toIso8601String(),
                );

                $medicationRequestId = $this->createMedicationRequest($payload);

                \DB::table('simpus_resep_detail')
                    ->where('resep_id', $resep->id_resep)
                    ->where('obat_id', $resep->obat_id)
                    ->update(['medicationrequest_id' => $medicationRequestId]);

                Log::info('MedicationRequest berhasil', [
                    'obat_id'              => $resep->obat_id,
                    'medicationrequest_id' => $medicationRequestId,
                ]);

                $results[] = [
                    'obat_id'              => $resep->obat_id,
                    'medicationrequest_id' => $medicationRequestId,
                    'status'               => 'berhasil',
                    'message'              => null,
                ];
            } catch (\Exception $e) {
                Log::error('MedicationRequest gagal', [
                    'obat_id' => $resep->obat_id,
                    'error'   => $e->getMessage(),
                ]);

                $results[] = [
                    'obat_id'              => $resep->obat_id,
                    'medicationrequest_id' => null,
                    'status'               => 'gagal',
                    'message'              => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
