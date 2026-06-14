<?php

namespace App\Services\Satusehat;

use Illuminate\Support\Facades\Http;
use App\Models\SimpusSkriningPtm;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;
use App\Models\RuangLayanan\SkriningPTM\SimpusPelayananPTM;

class SatuSehatService
{
    public function getAccessToken(): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
            ->withBody(
                http_build_query([
                    'client_id' => config('services.satusehat.client_id'),
                    'client_secret' => config('services.satusehat.client_secret'),
                ]),
                'application/x-www-form-urlencoded'
            )
            ->post(
                'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials'
            );

        // dd(
        //     $response->status(),
        //     $response->body()
        // );
        return $response->json('access_token');
    }

    public function createEncounter(array $payload): string
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post(
                config('services.satusehat.fhir_url') . '/Encounter',
                $payload
            );

        if (! $response->successful()) {
            throw new \Exception($response->body());
        }

        return $response->json('id');
    }

    public function kirimEncounter(string $idSkrining): string
    {
        $skrining = KunjunganPTM::where('idSkrining', $idSkrining)
            ->firstOrFail();

        // sementara pakai Patient sandbox
        $patientId = 'P02478375538';
        $patientName = 'Nama Test';

        $payload = [
            'resourceType' => 'Encounter',
            'status' => 'arrived',
            'statusHistory' => [
                [
                    'status' => 'arrived',
                    'period' => [
                        'start' => now()->toIso8601String(),
                    ],
                ],
            ],
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
                'display' => 'ambulatory',
            ],
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/encounter/' . config('services.satusehat.organization_id'),
                    'value' => $idSkrining,
                ],
            ],
            'subject' => [
                'reference' => 'Patient/' . $patientId,
                'display' => $patientName,
            ],
            'participant' => [
                [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                                    'code' => 'ATND',
                                    'display' => 'attender',
                                ],
                            ],
                        ],
                    ],
                    'individual' => [
                        'reference' => 'Practitioner/' . config('services.satusehat.practitioner_id'),
                        'display' => config('services.satusehat.practitioner_name'),
                    ],
                ],
            ],
            'period' => [
                'start' => now()->toIso8601String(),
            ],
            'location' => [
                [
                    'location' => [
                        'reference' => 'Location/' . config('services.satusehat.location_id'),
                        'display' => config('services.satusehat.location_name'),
                    ],
                ],
            ],
            'serviceProvider' => [
                'reference' => 'Organization/' . config('services.satusehat.organization_id'),
            ],
        ];

        $encounterId = $this->createEncounter($payload);

        SimpusPelayananPTM::updateOrCreate(
            [
                'idSkrining' => $idSkrining,
            ],
            [
                'encounter_id' => $encounterId,
            ]
        );

        return $encounterId;
    }
}
