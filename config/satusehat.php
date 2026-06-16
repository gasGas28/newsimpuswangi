<?php

// config/satusehat.php

return [

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    | staging  → https://api-satusehat-stg.dto.kemkes.go.id
    | production → https://api-satusehat.kemkes.go.id
    */

    'auth_url' => env('SATUSEHAT_AUTH_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials'),

    'fhir_url' => env('SATUSEHAT_FHIR_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1'),

    'client_id' => env('SATUSEHAT_CLIENT_ID'),

    'client_secret' => env('SATUSEHAT_CLIENT_SECRET'),

    'org_id' => env('SATUSEHAT_ORG_ID'),

];
