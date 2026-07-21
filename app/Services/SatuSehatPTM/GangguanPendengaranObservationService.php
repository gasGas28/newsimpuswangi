<?php

namespace App\Services\SatuSehatPTM;

use App\Models\RuangLayanan\SkriningPTM\GangguanPendengaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusGangguanPendengaran;
use App\Models\RuangLayanan\SkriningPTM\SatuSehatLog;

class GangguanPendengaranObservationService
{
    // ✅ bodySite SNOMED
    private array $bodySite = [
        'kanan' => ['code' => '25577004', 'display' => 'Right ear structure'],
        'kiri'  => ['code' => '89644007', 'display' => 'Left ear structure'],
    ];

    // ✅ code per jenis pemeriksaan
    private array $codeMap = [
        'tuli'    => ['code' => 'OC000150', 'display' => 'Suspek tuli kongenital',  'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term'],
        'omsk'    => ['code' => 'OC000149', 'display' => 'Suspek OMSK',             'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term'],
        'serumen' => ['code' => '18070006', 'display' => 'Impacted cerumen',         'system' => 'http://snomed.info/sct'],
        'presbi'  => ['code' => 'OC000151', 'display' => 'Suspek presbikusis',      'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term'],
        'bisik'   => ['code' => '247301006', 'display' => 'Finding of ability to hear whisper', 'system' => 'http://snomed.info/sct'],
    ];

    // ✅ valueCodeableConcept untuk bisik
    private array $bisikValueMap = [
        'normal'  => ['code' => '275727004', 'display' => 'Hearing test normal'],
        'gangguan'   => ['code' => '300221005', 'display' => 'Hearing for whisper impaired'],   // ⚠️ sesuaikan
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

    private function findExisting(string $encounterId, string $code): ?string
    {
        $response = Http::withToken($this->getToken())
            ->acceptJson()
            ->get(config('services.satusehat.fhir_url') . '/Observation', [
                'encounter' => $encounterId,
                'code'      => $code,
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
            resource: 'Observation',
            idResponse: null,
            method: 'POST',
            kirim: $payload,
            terima: $terima,
        );

        if (!$response->successful()) {
            Log::error('SatuSehat: gagal mengirim Bundle Gangguan Pendengaran', [
                'idPelayanan' => $idPelayanan,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
            throw new \Exception('Gagal mengirim Bundle Gangguan Pendengaran: ' . $response->body());
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

    // ✅ Boolean entry (tuli, omsk, serumen, presbi)
    private function buildBooleanEntry(
        array $code,
        string $side,
        bool $value,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
    ): array {
        $bodySite = $this->bodySite[$side];

        return [
            'fullUrl'  => 'urn:uuid:' . \Str::uuid(),
            'resource' => [
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
                'valueBoolean' => $value,
            ],
            'request' => [
                'method' => 'POST',
                'url'    => 'Observation',
            ],
        ];
    }

    // ✅ valueCodeableConcept entry (bisik)
    private function buildBisikEntry(
        string $side,
        string $value,
        string $patientId,
        string $encounterId,
        string $effectiveAt,
        string $practitionerId,
    ): array {
        $bodySite = $this->bodySite[$side];
        $code     = $this->codeMap['bisik'];
        $normalized = strtolower(trim($value));
        $bisikValue = $this->bisikValueMap[$normalized] ?? $this->bisikValueMap['normal'];

        return [
            'fullUrl'  => 'urn:uuid:' . \Str::uuid(),
            'resource' => [
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
                'valueCodeableConcept' => [
                    'coding' => [[
                        'system'  => 'http://snomed.info/sct',
                        'code'    => $bisikValue['code'],
                        'display' => $bisikValue['display'],
                    ]],
                ],
            ],
            'request' => [
                'method' => 'POST',
                'url'    => 'Observation',
            ],
        ];
    }

    public function sendGangguanPendengaran(string $idSkrining): void
    {
        $skrining    = KunjunganPTM::where('idSkrining', $idSkrining)->firstOrFail();
        $pendengaran = GangguanPendengaran::where('skriningID', $idSkrining)->firstOrFail();

        $patientId   = $skrining->patient_id;
        $encounterId = $skrining->encounter_id;
        $idPelayanan = $skrining->idPelayanan;
        $effectiveAt = now()->toIso8601String();
        $practitionerId = $skrining->id_petugas;

        $entries = [];

        // ─── Boolean fields ──────────────────────────────────────────
        $booleanFields = [
            'tuli_kiri'     => ['jenis' => 'tuli',    'side' => 'kiri'],
            'tuli_kanan'    => ['jenis' => 'tuli',    'side' => 'kanan'],
            'omsk_kiri'     => ['jenis' => 'omsk',    'side' => 'kiri'],
            'omsk_kanan'    => ['jenis' => 'omsk',    'side' => 'kanan'],
            'serumen_kiri'  => ['jenis' => 'serumen', 'side' => 'kiri'],
            'serumen_kanan' => ['jenis' => 'serumen', 'side' => 'kanan'],
            'presbi_kiri'   => ['jenis' => 'presbi',  'side' => 'kiri'],
            'presbi_kanan'  => ['jenis' => 'presbi',  'side' => 'kanan'],
        ];

        foreach ($booleanFields as $field => $meta) {
            $value = $pendengaran->$field;
            if (is_null($value)) continue;

            $code       = $this->codeMap[$meta['jenis']];
            $existingId = $this->findExisting($encounterId, $code['code']);

            if ($existingId) {
                Log::info("Observation {$field} sudah ada, skip", ['observation_id' => $existingId]);
                continue;
            }

            $entries[] = $this->buildBooleanEntry(
                code: $code,
                side: $meta['side'],
                value: filter_var($value, FILTER_VALIDATE_BOOLEAN),
                patientId: $patientId,
                encounterId: $encounterId,
                effectiveAt: $effectiveAt,
                practitionerId: $practitionerId,
            );
        }

        // ─── Bisik fields ────────────────────────────────────────────
        $bisikFields = [
            'bisik_kiri'  => 'kiri',
            'bisik_kanan' => 'kanan',
        ];

        foreach ($bisikFields as $field => $side) {
            $value = $pendengaran->$field;
            if (is_null($value)) continue;

            $existingId = $this->findExisting($encounterId, $this->codeMap['bisik']['code']);

            if ($existingId) {
                Log::info("Observation {$field} sudah ada, skip", ['observation_id' => $existingId]);
                continue;
            }

            $entries[] = $this->buildBisikEntry(
                side: $side,
                value: $value,
                patientId: $patientId,
                encounterId: $encounterId,
                effectiveAt: $effectiveAt,
                practitionerId: $practitionerId,
            );
        }

        if (empty($entries)) {
            Log::info('Bundle Gangguan Pendengaran skip, semua sudah ada atau null');
            return;
        }

        $this->sendBundle($entries, $idPelayanan);

        Log::info('Bundle Gangguan Pendengaran berhasil', ['total' => count($entries)]);

        $pendengaran->update(['sent_at' => now()]);
    }
}