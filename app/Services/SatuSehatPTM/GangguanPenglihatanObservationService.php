<?php

namespace App\Services\SatuSehatPTM;

use App\Models\RuangLayanan\SkriningPTM\GangguanPenglihatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SatuSehatLog;

class GangguanPenglihatanObservationService
{
    // ✅ bodySite SNOMED per sisi
    private array $bodySite = [
        'od' => [
            'eye'      => ['code' => '18944008',  'display' => 'Right eye structure'],
            'anterior' => ['code' => '722049003',  'display' => 'Structure of anterior eyeball segment of right eye'],
            'fundus'   => ['code' => '723298005',  'display' => 'Structure of fundus of right eye'],
            'pupil'    => ['code' => '52378001',   'display' => 'Structure of pupil of right eye'],
        ],
        'os' => [
            'eye'      => ['code' => '8966001',  'display' => 'Left eye structure'],
            'anterior' => ['code' => '40137007',   'display' => 'Structure of anterior eyeball segment of left eye'],
            'fundus'   => ['code' => '723299002',   'display' => 'Structure of fundus of left eye'],
            'pupil'    => ['code' => '57368009',    'display' => 'Structure of pupil of left eye'],
        ],
    ];

    //  valueCodeableConcept map untuk anterior
    private array $anteriorMap = [
        'normal'       => ['code' => '17621005',        'display' => 'Normal',             'system' => 'http://snomed.info/sct'],
        'tidak_normal' => ['code' => '1111451000000106', 'display' => 'Acute angle closure', 'system' => 'http://snomed.info/sct'],
    ];

    //  valueCodeableConcept map untuk refleks (shadow test)
    private array $shadowMap = [
        'negatif' => ['code' => '260385009', 'display' => 'Negative', 'system' => 'http://snomed.info/sct'],
        'positif' => ['code' => '10828004',  'display' => 'Positive', 'system' => 'http://snomed.info/sct'],
    ];

    //  valueCodeableConcept map untuk refleks fundus
    private array $refleksMap = [
        'matur'   => ['code' => '34071009', 'display' => 'Mature',   'system' => 'http://snomed.info/sct'],
        'tidak_normal'  => ['code' => '34071009', 'display' => 'Immature', 'system' => 'http://snomed.info/sct'], // ⚠️ sesuaikan
        'normal'  => ['code' => '17621005', 'display' => 'Normal',   'system' => 'http://snomed.info/sct'],
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

    private function findExisting(string $encounterId, string $code, string $bodySiteCode): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Observation', [
                'encounter'  => $encounterId,
                'code'       => $code,
                'body-site'  => $bodySiteCode,
            ]);

        if (!$response->successful()) return null;

        $entries = $response->json('entry') ?? [];
        return !empty($entries) ? ($entries[0]['resource']['id'] ?? null) : null;
    }

    private function sendBundle(array $entries, ?string $idPelayanan): void
    {
        $payload = [
            'resourceType' => 'Bundle',
            'type'         => 'transaction',
            'entry'        => $entries,
        ];

        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->post(config('services.satusehat.fhir_url'), $payload);

        $terima = $response->json() ?? $response->body();

        $this->simpanLog(
            idPelayanan: $idPelayanan,
            resource: 'Observation-Penglihatan',
            idResponse: null,
            method: 'POST',
            kirim: $payload,
            terima: $terima,
        );

        if (!$response->successful()) {
            Log::error('SatuSehat: gagal mengirim Bundle Gangguan Penglihatan', [
                'idPelayanan' => $idPelayanan,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
            throw new \Exception('Gagal mengirim Bundle Gangguan Penglihatan: ' . $response->body());
        }
    }

    protected function simpanLog(
        ?string $idPelayanan,
        string $resource,
        ?string $idResponse,
        string $method,
        mixed $kirim,
        mixed $terima,
    ): void {
        $data = [
            'idPelayanan' => $idPelayanan,
            'tanggal'     => now(),
            'puskId'      => '3',
            'resource'    => $resource,
            'idResponse'  => $idResponse,
            'method'      => $method,
            'kirim'       => json_encode($kirim),
            'terima'      => json_encode($terima),
            'userId'      => Auth::id(),
        ];

        try {
            $log = SatuSehatLog::create($data);

            Log::info('SatuSehat: log tersimpan ke satu_sehat_log', [
                'id'          => $log->id ?? null,
                'idPelayanan' => $idPelayanan,
                'resource'    => $resource,
            ]);
        } catch (\Throwable $e) {
            Log::error('SatuSehat: GAGAL menyimpan ke satu_sehat_log', [
                'message'        => $e->getMessage(),
                'idPelayanan'    => $idPelayanan,
                'resource'       => $resource,
                'userId'         => Auth::id(),
                'panjang_kirim'  => strlen((string) $data['kirim']),
                'panjang_terima' => strlen((string) $data['terima']),
                'trace'          => $e->getTraceAsString(),
            ]);
        }
    }

    private function baseResource(
        array $code,
        array $bodySite,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
    ): array {
        return [
            'resourceType'      => 'Observation',
            'status'            => 'final',
            'category'          => [[
                'coding' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                    'code'    => 'exam',
                    'display' => 'Exam',
                ]],
            ]],
            'code'              => [
                'coding' => [[
                    'system'  => $code['system'],
                    'code'    => $code['code'],
                    'display' => $code['display'],
                ]],
            ],
            'bodySite'          => [
                'coding' => [[
                    'system'  => 'http://snomed.info/sct',
                    'code'    => $bodySite['code'],
                    'display' => $bodySite['display'],
                ]],
            ],
            'subject'           => ['reference' => "Patient/{$patientId}"],
            'encounter'         => ['reference' => "Encounter/{$encounterId}"],
            'effectiveDateTime' => $effectiveAt,
            'performer'         => [[
                'reference' => 'Practitioner/' . $practitionerId,
            ]],
        ];
    }

    //  visus & pinhole — valueRatio (format 6/12)
    private function buildRatioEntry(
        array $code,
        array $bodySite,
        string $value,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
        array $referenceRange = [],
        array $interpretation = [],
    ): array {
        //  Parse "6/12" -> numerator=6, denominator=12
        [$num, $den] = array_map('intval', explode('/', $value));

        $resource = array_merge(
            $this->baseResource($code, $bodySite, $patientId, $encounterId, $effectiveAt, $practitionerId),
            [
                'valueRatio' => [
                    'numerator'   => ['value' => $num, 'unit' => 'm', 'system' => 'http://unitsofmeasure.org', 'code' => 'm'],
                    'denominator' => ['value' => $den, 'unit' => 'm', 'system' => 'http://unitsofmeasure.org', 'code' => 'm'],
                ],
            ]
        );

        if (!empty($referenceRange)) $resource['referenceRange']  = $referenceRange;
        if (!empty($interpretation)) $resource['interpretation']  = $interpretation;

        return ['fullUrl' => 'urn:uuid:' . \Str::uuid(), 'resource' => $resource, 'request' => ['method' => 'POST', 'url' => 'Observation']];
    }

    // glaukoma — valueQuantity + referenceRange
    private function buildGlaukomaEntry(
        array $code,
        float $value,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
    ): array {
        $resource = array_merge(
            $this->baseResource($code, ['code' => '', 'display' => ''], $patientId, $encounterId, $effectiveAt, $practitionerId),
            [
                'valueQuantity' => [
                    'value'  => $value,
                    'unit'   => 'mmHg',
                    'system' => 'http://unitsofmeasure.org',
                    'code'   => 'mm[Hg]',
                ],
                'referenceRange' => [
                    [
                        'low'  => ['value' => 10, 'unit' => 'mmHg', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
                        'high' => ['value' => 21, 'unit' => 'mmHg', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
                        'text' => 'Normal',
                    ],
                    [
                        'low'  => ['value' => 22, 'unit' => 'mmHg', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
                        'text' => 'Glaukoma',
                    ],
                ],
                'interpretation' => [[
                    'coding' => [[
                        'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation',
                        'code'    => $value <= 21 ? 'N' : 'H',
                        'display' => $value <= 21 ? 'Normal' : 'High',
                    ]],
                ]],
            ]
        );

        // Glaukoma tidak pakai bodySite, hapus
        unset($resource['bodySite']);

        return ['fullUrl' => 'urn:uuid:' . \Str::uuid(), 'resource' => $resource, 'request' => ['method' => 'POST', 'url' => 'Observation']];
    }

    // valueCodeableConcept entry (anterior, shadow, refleks)
    private function buildCodeableEntry(
        array $code,
        array $bodySite,
        array $valueCodeableConcept,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
    ): array {
        $resource = array_merge(
            $this->baseResource($code, $bodySite, $patientId, $encounterId, $effectiveAt, $practitionerId),
            [
                'valueCodeableConcept' => [
                    'coding' => [[
                        'system'  => $valueCodeableConcept['system'],
                        'code'    => $valueCodeableConcept['code'],
                        'display' => $valueCodeableConcept['display'],
                    ]],
                ],
            ]
        );

        return ['fullUrl' => 'urn:uuid:' . \Str::uuid(), 'resource' => $resource, 'request' => ['method' => 'POST', 'url' => 'Observation']];
    }

    // valueBoolean entry (retinopati)
    private function buildBooleanEntry(
        array $code,
        array $bodySite,
        bool $value,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
    ): array {
        $resource = array_merge(
            $this->baseResource($code, $bodySite, $patientId, $encounterId, $effectiveAt, $practitionerId),
            ['valueBoolean' => $value]
        );

        return ['fullUrl' => 'urn:uuid:' . \Str::uuid(), 'resource' => $resource, 'request' => ['method' => 'POST', 'url' => 'Observation']];
    }

    private function normalizeValue(string $value): string
    {
        return strtolower(trim(preg_replace('/\s+/', '_', $value)));
    }

    public function sendGangguanPenglihatan(string $idSkrining): void
    {
        $skrining    = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $penglihatan = GangguanPenglihatan::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $idPelayanan = $skrining->idPelayanan;
        $practitionerId = $skrining->id_petugas;
        $effectiveAt = now()->toIso8601String();

        $entries = [];

        foreach (['od', 'os'] as $side) {
            // ─── Visus 
            $visusValue = $penglihatan->{"visus_{$side}"};
            if ($visusValue) {
                $code = [
                    'system'  => 'http://loinc.org',
                    'code'    => $side === 'od' ? '79882-7' : '79881-9',
                    'display' => $side === 'od'
                        ? 'Visual acuity uncorrected Right eye by Snellen eye chart'
                        : 'Visual acuity uncorrected Left eye by Snellen eye chart',
                ];
                $bodySite   = $this->bodySite[$side]['eye'];
                $existingId = $this->findExisting($encounterId, $code['code'], $bodySite['code']);

                if (!$existingId) {
                    $entries[] = $this->buildRatioEntry($code, $bodySite, $visusValue, $patientId, $encounterId, $effectiveAt, $practitionerId);
                } else {
                    Log::info("Observation visus_{$side} sudah ada, skip");
                }
            }

            // ─── Pinhole 
            $pinholeValue = $penglihatan->{"pinhole_{$side}"};
            if ($pinholeValue) {
                $code = [
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '441841000',
                    'display' => 'Pinhole visual acuity test',
                ];
                $bodySite   = $this->bodySite[$side]['eye'];
                $existingId = $this->findExisting($encounterId, $code['code'], $bodySite['code']);

                if (!$existingId) {
                    $entries[] = $this->buildRatioEntry(
                        code: $code,
                        bodySite: $bodySite,
                        value: $pinholeValue,
                        patientId: $patientId,
                        encounterId: $encounterId,
                        effectiveAt: $effectiveAt,
                        practitionerId: $practitionerId,
                        referenceRange: [
                            ['text' => 'Kelainan Refraksi: 6/12 - 6/6'],
                            ['text' => 'Kelainan Organik: 3/60- <6/12'],
                        ],
                        interpretation: [[
                            'coding' => [[
                                'system'  => 'http://snomed.info/sct',
                                'code'    => '39021009',
                                'display' => 'Disorder of refraction',
                            ]],
                        ]],
                    );
                } else {
                    Log::info("Observation pinhole_{$side} sudah ada, skip");
                }
            }

            // ─── Anterior 
            $anteriorValue = $penglihatan->{"anterior_{$side}"};
            if ($anteriorValue) {
                $code = [
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '40137007',
                    'display' => 'Pupil light reflex',
                ];
                $bodySite        = $this->bodySite[$side]['anterior'];
                $existingId      = $this->findExisting($encounterId, $code['code'], $bodySite['code']);
                $normalized      = $this->normalizeValue($anteriorValue);
                $valueCodeable   = $this->anteriorMap[$normalized] ?? $this->anteriorMap['normal'];

                if (!$existingId) {
                    $entries[] = $this->buildCodeableEntry($code, $bodySite, $valueCodeable, $patientId, $encounterId, $effectiveAt, $practitionerId);
                } else {
                    Log::info("Observation anterior_{$side} sudah ada, skip");
                }
            }

            // ─── Shadow 
            $shadowValue = $penglihatan->{"shadow_{$side}"};
            if ($shadowValue) {
                $code = [
                    'system'  => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                    'code'    => 'OC000154',
                    'display' => 'Shadow test',
                ];
                $bodySite      = $this->bodySite[$side]['pupil'];
                $existingId    = $this->findExisting($encounterId, $code['code'], $bodySite['code']);
                $normalized    = $this->normalizeValue($shadowValue);
                $valueCodeable = $this->shadowMap[$normalized] ?? $this->shadowMap['negatif'];

                if (!$existingId) {
                    $entries[] = $this->buildCodeableEntry($code, $bodySite, $valueCodeable, $patientId, $encounterId, $effectiveAt, $practitionerId);
                } else {
                    Log::info("Observation shadow_{$side} sudah ada, skip");
                }
            }

            // ─── Refleks 
            $refleksValue = $penglihatan->{"refleks_{$side}"};
            if ($refleksValue) {
                $code = [
                    'system'  => 'http://snomed.info/sct',
                    'code'    => '43408002',
                    'display' => 'Fundus reflex',
                ];
                $bodySite      = $this->bodySite[$side]['fundus'];
                $existingId    = $this->findExisting($encounterId, $code['code'], $bodySite['code']);
                $normalized    = $this->normalizeValue($refleksValue);
                $valueCodeable = $this->refleksMap[$normalized] ?? $this->refleksMap['normal'];

                if (!$existingId) {
                    $entries[] = $this->buildCodeableEntry($code, $bodySite, $valueCodeable, $patientId, $encounterId, $effectiveAt, $practitionerId);
                } else {
                    Log::info("Observation refleks_{$side} sudah ada, skip");
                }
            }

            // ─── Glaukoma 
            $glaukomaValue = $penglihatan->{"glaukoma_{$side}"};
            if ($glaukomaValue) {
                $code = [
                    'system'  => 'http://loinc.org',
                    'code'    => $side === 'od' ? '79892-6' : '79893-4',
                    'display' => $side === 'od'
                        ? 'Right eye Intraocular pressure'
                        : 'Left eye Intraocular pressure',
                ];
                $existingId = $this->findExisting($encounterId, $code['code'], '');

                if (!$existingId) {
                    $entries[] = $this->buildGlaukomaEntry($code, (float) $glaukomaValue, $patientId, $encounterId, $effectiveAt, $practitionerId);
                } else {
                    Log::info("Observation glaukoma_{$side} sudah ada, skip");
                }
            }

            // ─── Retinopati ──────────────────────────────────────────
            $retinoValue = $penglihatan->{"retinopati_{$side}"};
            if (!is_null($retinoValue)) {
                $code = [
                    'system'  => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                    'code'    => 'OC000152',
                    'display' => 'Suspek retinopati',
                ];
                $bodySite   = $this->bodySite[$side]['eye'];
                $existingId = $this->findExisting($encounterId, $code['code'], $bodySite['code']);

                if (!$existingId) {
                    $entries[] = $this->buildBooleanEntry(
                        code: $code,
                        bodySite: $bodySite,
                        value: filter_var($retinoValue, FILTER_VALIDATE_BOOLEAN),
                        patientId: $patientId,
                        encounterId: $encounterId,
                        effectiveAt: $effectiveAt,
                        practitionerId: $practitionerId,
                    );
                } else {
                    Log::info("Observation retinopati_{$side} sudah ada, skip");
                }
            }
        }

        if (empty($entries)) {
            Log::info('Bundle Gangguan Penglihatan skip, semua sudah ada atau null');
            return;
        }

        $this->sendBundle($entries, $idPelayanan);

        Log::info('Bundle Gangguan Penglihatan berhasil', ['total' => count($entries)]);

        $penglihatan->update(['sent_at' => now()]);
    }
}
