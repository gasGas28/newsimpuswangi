<?php

return [
    // Base URL FHIR endpoint Satusehat (contoh): https://.../fhir
    'base_url' => env('SATUSEHAT_BASE_URL'),

    // Token URL OAuth2 (contoh): https://.../oauth2/token
    'token_url' => env('SATUSEHAT_TOKEN_URL'),

    'grant_type' => env('SATUSEHAT_GRANT_TYPE', 'client_credentials'),
    'scope' => env('SATUSEHAT_SCOPE'),

    'client_id' => env('SATUSEHAT_CLIENT_ID'),
    'client_secret' => env('SATUSEHAT_CLIENT_SECRET'),
];

