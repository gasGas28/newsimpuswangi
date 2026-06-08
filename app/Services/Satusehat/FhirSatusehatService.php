<?php

namespace App\Services\Satusehat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FhirSatusehatService
{
    public function __construct(
        protected ?string $baseUrl = null,
        protected ?string $tokenUrl = null,
        protected ?string $clientId = null,
        protected ?string $clientSecret = null,
        protected ?string $grantType = null,
        protected ?string $scope = null,
    ) {
        $this->baseUrl = $this->baseUrl ?? config('satusehat.base_url');
        $this->tokenUrl = $this->tokenUrl ?? config('satusehat.token_url');
        $this->clientId = $this->clientId ?? config('satusehat.client_id');
        $this->clientSecret = $this->clientSecret ?? config('satusehat.client_secret');
        $this->grantType = $this->grantType ?? (config('satusehat.grant_type') ?: 'client_credentials');
        $this->scope = $this->scope ?? config('satusehat.scope');
    }

    /*  Ambil access token menggunakan client_credentials (umum).
     */
    public function getAccessToken(): string
    {
        $payload = [
            'grant_type' => $this->grantType,

            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        if (!empty($this->scope)) {
            $payload['scope'] = $this->scope;
        }

        $res = Http::timeout(20)->asForm()->post($this->tokenUrl, $payload);

        if (!$res->ok()) {
            Log::error('Satusehat token request failed', [
                'status' => $res->status(),
                'body' => $this->safeBody($res->body()),
            ]);
            throw new \RuntimeException('Gagal mengambil token Satusehat');
        }

        $json = $res->json();
        $token = $json['access_token'] ?? null;

        if (!$token) {
            Log::error('Satusehat token response missing access_token', ['body' => $this->safeBody($res->body())]);
            throw new \RuntimeException('Token Satusehat tidak valid');
        }

        return (string) $token;
    }

    /** Kirim resource FHIR (mis. Encounter/Observation/ServiceRequest) via POST ke /<resource>
     */
    public function createFhirResource(array $fhirResource, string $resourceType): array
    {
        $token = $this->getAccessToken();

        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($resourceType, '/');

        $res = Http::timeout(30)
            ->withToken($token)
            ->acceptJson()
            ->contentType('application/json')
            ->post($url, $fhirResource);

        if (!$res->ok()) {
            Log::error('Satusehat createFhirResource failed', [
                'url' => $url,
                'status' => $res->status(),
                'body' => $this->safeBody($res->body()),
            ]);
            throw new \RuntimeException('Gagal kirim data ke Satusehat');
        }

        return $res->json() ?? [];
    }

    private function safeBody(string $body): string
    {
        $body = trim($body);
        if (strlen($body) > 4000) {
            return substr($body, 0, 4000) . '...';
        }
        return $body;
    }
}

