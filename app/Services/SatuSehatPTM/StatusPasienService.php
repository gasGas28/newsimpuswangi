<?php

namespace App\Services\SatuSehatPTM;

use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\SimpusStatusPTM;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;

class StatusPasienService
{
    public function __construct(
        private EncounterService $encounterService,
        private ServiceRequestService $serviceRequestService,
    ) {}

    /**
     * Kumpulkan semua Condition ID yang sudah terkirim untuk skrining ini,
     * dari berbagai tabel penyakit (hipertensi, dll).
     *
     * @param  string $idSkrining
     * @return array<int, array{id: string, display: string|null}>
     */
    private function collectConditionIds(string $idSkrining): array
    {
        $conditions = [];

        // Hipertensi
        $hipertensi = SimpusHipertensi::where('skriningID', $idSkrining)
            ->whereNotNull('condition_id')
            ->first();

        if ($hipertensi) {
            $conditions[] = [
                'id'      => $hipertensi->condition_id,
                'display' => 'Hipertensi',
            ];
        }

        // $diabetes = SimpusDiabetes::where('skriningID', $idSkrining)->whereNotNull('condition_id')->first();
        // if ($diabetes) {
        //     $conditions[] = ['id' => $diabetes->condition_id, 'display' => 'Diabetes Mellitus'];
        // }

        return $conditions;
    }

    /**
     * Kirim status keluar pasien (discharge) ke SATUSEHAT:
     * - Update Encounter (cara_keluar -> dischargeDisposition + diagnosis)
     * - Buat ServiceRequest (rujukan, jadwal_kontrol, transportasi)
     * - Update status kunjungan jadi 'finished' (queryable by idSkrining)
     *
     * @param  string $idSkrining
     * @return array
     */
    public function sendStatusPasien(string $idSkrining): array
    {
        $kunjungan = KunjunganPTM::select(
            'simpus_kunjungan_ptm.*',
            'simpus_pasien.NAMA_LGKP',
        )
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->where('simpus_kunjungan_ptm.idSkrining', $idSkrining)
            ->firstOrFail();

        $status = SimpusStatusPTM::where('skriningID', $idSkrining)->firstOrFail();

        if (empty($kunjungan->encounter_id) || empty($kunjungan->patient_id)) {
            throw new \Exception('encounter_id atau patient_id kosong untuk idSkrining: ' . $idSkrining);
        }

        $patientId      = $kunjungan->patient_id;
        $patientName    = $kunjungan->NAMA_LGKP;
        $encounterId    = $kunjungan->encounter_id;
        $practitionerId = $kunjungan->id_petugas;

        $now = now()->toIso8601String();

        $conditionIds = $this->collectConditionIds($idSkrining);

        if (empty($conditionIds)) {
            Log::warning('Tidak ada Condition ID ditemukan, Encounter.diagnosis akan kosong', [
                'idSkrining' => $idSkrining,
            ]);
        }

        $result = [
            'encounter_update' => null,
            'service_request'  => null,
        ];

        // 1. Update Encounter (discharge disposition + diagnosis)
        try {
            $this->encounterService->updateDischargeStatus(
                encounterId: $encounterId,
                caraKeluar: $status->cara_keluar,
                periodEnd: $now,
                idPelayanan: $kunjungan->idPelayanan,
                conditionIds: $conditionIds,
            );

            $result['encounter_update'] = [
                'status'  => 'berhasil',
                'message' => null,
            ];
        } catch (\Exception $e) {
            Log::error('Gagal update discharge status', [
                'idSkrining' => $idSkrining,
                'error'      => $e->getMessage(),
            ]);

            $result['encounter_update'] = [
                'status'  => 'gagal',
                'message' => $e->getMessage(),
            ];
        }

        // 2. Kirim ServiceRequest (rencana tindak lanjut)
        try {
            $serviceRequestId = $this->serviceRequestService->sendRencanaTindakLanjut(
                patientId: $patientId,
                patientName: $patientName,
                encounterId: $encounterId,
                practitionerId: $practitionerId,
                rencanaRujuk: $status->rujukan,
                jadwalKontrol: $status->jadwal_kontrol,
                transport: $status->transportasi,
                authoredOn: $now,
            );

            if ($serviceRequestId) {
                $status->update(['service_request_id' => $serviceRequestId]);
            }

            $result['service_request'] = [
                'status'             => $serviceRequestId ? 'berhasil' : 'skip',
                'service_request_id' => $serviceRequestId,
                'message'            => $serviceRequestId ? null : 'Tidak ada rencana rujuk/jadwal kontrol',
            ];
        } catch (\Exception $e) {
            Log::error('Gagal kirim ServiceRequest rencana tindak lanjut', [
                'idSkrining' => $idSkrining,
                'error'      => $e->getMessage(),
            ]);

            $result['service_request'] = [
                'status'  => 'gagal',
                'message' => $e->getMessage(),
            ];
        }

        // 3. Update status kunjungan di simpus_skrining_ptm
        $adaGagal = ($result['encounter_update']['status'] === 'gagal')
            || ($result['service_request']['status'] === 'gagal');

        SimpusSkriningPTM::where('idSkrining', $idSkrining)->update([
            'status'        => $adaGagal ? 'in-progress' : 'finished',
            'sync_status'   => $adaGagal ? 'failed' : 'success',
            'sync_time'     => now(),
            'error_message' => $adaGagal
                ? collect([
                    $result['encounter_update']['message'] ?? null,
                    $result['service_request']['message'] ?? null,
                ])->filter()->implode(' | ')
                : null,
        ]);

        return $result;
    }
}