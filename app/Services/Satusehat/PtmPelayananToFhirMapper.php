<?php

namespace App\Services\Satusehat;

use Illuminate\Support\Str;

class PtmPelayananToFhirMapper
{
    /** Map payload form PTM -> FHIR resources.
     *
     * Input: $formData from UI (subjektif/objektif/assessment/status_pasien/planning + resume).
     */
    public function mapEncounterAndObservations(array $dataPasien, array $formData, array $tindakan = [], array $dataTindakan = []): array
    {
        // Kontrol: minimal fields yang dipakai di UI resume.
        $sub = $formData['subjektif'] ?? [];
        $obj = $formData['objektif'] ?? [];
        $ass = $formData['assessment'] ?? [];
        $status = $formData['status_pasien'] ?? [];

        $encounterId = 'enc-' . ($dataPasien['idpelayanan'] ?? Str::uuid());
        $subjectDisplay = $dataPasien['NAMA_LGKP'] ?? $dataPasien['nama'] ?? $dataPasien['nmPasien'] ?? '';
        $patientNik = $dataPasien['NIK'] ?? $dataPasien['nik'] ?? '';

        $dateEncounter = $sub['tanggal_skrining'] ?? $dataPasien['tglKunjungan'] ?? ($obj['tanggal'] ?? date('Y-m-d'));

        $encounter = [
            'resourceType' => 'Encounter',
            'id' => $encounterId,
            'status' => 'finished',
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
                'display' => 'ambulatory',
            ],
            'subject' => [
                // Satusehat umumnya menerima Patient referensi/identifier; di sini pakai identifier.
                'identifier' => [
                    [
                        'system' => 'urn:oid:1.2.643.3.131.1.1',
                        'value' => $patientNik,
                    ],
                ],
                'display' => $subjectDisplay,
            ],
            'period' => [
                'start' => $dateEncounter,
                'end' => $dateEncounter,
            ],
            'serviceType' => [
                'coding' => [
                    [
                        'system' => 'http://loinc.org',
                        'code' => '74213-0',
                        'display' => 'Skrining PTM',
                    ],
                ],
            ],
        ];

        $observations = [];

        // IMT kategori / skor risiko sebagai Observation (minimal)
        $observations[] = $this->makeNumericObservation(
            id: 'obs-imt-kategori-' . $encounterId,
            code: 'imtrisk',
            display: 'Kategori Risiko PTM',
            system: 'http://example.local/fhir/satusehat/observation-codes',
            value: $status['kategori_risiko_ptm'] ?? $obj['kat_risiko'] ?? null,
            unit: null
        );

        // Tekanan darah (contoh: td_s/td_d)
        $tdS = $obj['td_s'] ?? null;
        $tdD = $obj['td_d'] ?? null;
        if (!empty($tdS) && !empty($tdD)) {
            $observations[] = $this->makeStringObservation(
                id: 'obs-td-' . $encounterId,
                code: 'blood-pressure',
                display: 'Tekanan Darah',
                system: 'http://example.local/fhir/satusehat/observation-codes',
                value: ($tdS . '/' . $tdD)
            );
        }

        // GDS / Gula darah sewaktu
        $gds = $obj['gd_sewaktu'] ?? ($obj['gds'] ?? null);
        if (!empty($gds)) {
            $observations[] = $this->makeNumericObservation(
                id: 'obs-gds-' . $encounterId,
                code: 'gds',
                display: 'Gula Darah Sewaktu',
                system: 'http://example.local/fhir/satusehat/observation-codes',
                value: $gds,
                unit: 'mg/dL'
            );
        }

        // Kolesterol total
        $kol = $obj['koltot'] ?? null;
        if (!empty($kol)) {
            $observations[] = $this->makeNumericObservation(
                id: 'obs-koltot-' . $encounterId,
                code: 'cholesterol-total',
                display: 'Kolesterol Total',
                system: 'http://example.local/fhir/satusehat/observation-codes',
                value: $kol,
                unit: 'mg/dL'
            );
        }

        // Diagnosa utama / status kasus -> Observation string
        $diagnosis = $ass['diagnosis_utama'] ?? null;
        if (!empty($diagnosis)) {
            $observations[] = $this->makeStringObservation(
                id: 'obs-diagnosis-utama-' . $encounterId,
                code: 'diagnosis-utama',
                display: 'Diagnosis Utama',
                system: 'http://example.local/fhir/satusehat/observation-codes',
                value: $diagnosis
            );
        }

        $serviceRequests = [];

        return [
            'encounter' => $encounter,
            'observations' => array_values(array_filter($observations)),
            'service_requests' => $serviceRequests,
        ];
    }

    private function makeNumericObservation(string $id, string $code, string $display, string $system, $value, ?string $unit = null): ?array
    {
        if ($value === null || $value === '') {
            return null;
        }

        $valueNumber = is_numeric($value) ? (float) $value : null;
        if ($valueNumber === null) {
            return null;
        }

        $obs = [
            'resourceType' => 'Observation',
            'id' => $id,
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => $system,
                        'code' => $code,
                        'display' => $display,
                    ],
                ],
            ],
            'valueQuantity' => [
                'value' => $valueNumber,
                'unit' => $unit ?? '',
                'system' => $unit ? 'http://unitsofmeasure.org' : null,
                'code' => $unit ?? null,
            ],
        ];

        // buang null
        if (empty($unit)) {
            unset($obs['valueQuantity']['system'], $obs['valueQuantity']['code']);
        }

        return $obs;
    }

    private function makeStringObservation(string $id, string $code, string $display, string $system, $value): array
    {
        $val = $value;
        if ($val === null || $val === '') {
            return [
                'resourceType' => 'Observation',
                'id' => $id,
                'status' => 'final',
                'code' => [
                    'coding' => [
                        [
                            'system' => $system,
                            'code' => $code,
                            'display' => $display,
                        ],
                    ],
                ],
                'valueString' => '-',
            ];
        }

        return [
            'resourceType' => 'Observation',
            'id' => $id,
            'status' => 'final',
            'code' => [
                'coding' => [
                    [
                        'system' => $system,
                        'code' => $code,
                        'display' => $display,
                    ],
                ],
            ],
            'valueString' => (string) $val,
        ];
    }
}

