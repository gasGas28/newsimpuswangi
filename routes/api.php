<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\SimpanTindakanRequest;

Route::post('/test-form-request', function (SimpanTindakanRequest $request) {
    dd([
        'validated' => $request->validated(),
        'all' => $request->all(),
        'class' => get_class($request),
    ]);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// Satusehat FHIR - submit pelayanan (Encounter + Observation/ServiceRequest)
Route::post('/satusehat/fhir/submit-pelayanan', [
    \App\Http\Controllers\Satusehat\SatusehatFhirController::class,
    'submitPelayanan'
]);

