<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use App\Models\SatusehatSubmission;
use App\Services\Satusehat\FhirSatusehatService;
use App\Services\Satusehat\PtmPelayananToFhirMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SatusehatFhirController extends Controller
{
    public function __construct(
        protected FhirSatusehatService $fhirService,
    ) {}

    /**
     * Endpoint minimal untuk kirim Encounter + Observation/ServiceRequest.
     *
     * Expected payload (contoh minimal):
     * {
     *   "resource": {
     *      "encounter": { ...FHIR Encounter... },
     *      "observations": [ { ...FHIR Observation... }, ...]
     *   },
     *   "mode": "bulk"
     * }
     */
    public function submitPelayanan(Request $request)
    {
        $payload = $request->validate([
            'resource.encounter' => 'required|array',
            'resource.observations' => 'nullable|array',
            'resource.service_requests' => 'nullable|array',
        ]);

        $encounter = $payload['resource']['encounter'];
        $observations = $payload['resource']['observations'] ?? [];
        $serviceRequests = $payload['resource']['service_requests'] ?? [];

        // Normalisasi resourceType agar tidak salah
        $bundleId = (string) Str::uuid();

        $created = [];

        $encounter['resourceType'] = $encounter['resourceType'] ?? 'Encounter';
        $created[] = $this->fhirService->createFhirResource($encounter, 'Encounter');


        foreach ($observations as $obs) {
            $obs['resourceType'] = $obs['resourceType'] ?? 'Observation';
            $created[] = $this->fhirService->createFhirResource($obs, 'Observation');
        }

        foreach ($serviceRequests as $sr) {
            $sr['resourceType'] = $sr['resourceType'] ?? 'ServiceRequest';
            $created[] = $this->fhirService->createFhirResource($sr, 'ServiceRequest');
        }

        Log::info('Satusehat submitPelayanan success', [
            'bundle_id' => $bundleId,
            'count' => count($created),
        ]);

        return response()->json([
            'success' => true,
            'bundle_id' => $bundleId,
            'result' => $created,
        ]);
    }

    public function submitPtmPelayanan(Request $request, PtmPelayananToFhirMapper $mapper)
    {
        $payload = $request->validate([
            'DataPasien' => 'required|array',
            'formData' => 'required|array',
            'formData.subjektif' => 'nullable|array',
            'formData.objektif' => 'nullable|array',
            'formData.assessment' => 'nullable|array',
            'formData.status_pasien' => 'nullable|array',
            'formData.planning' => 'nullable|array',
            'composition' => 'nullable|array',
        ]);

        $submission = SatusehatSubmission::create([
            'user_id' => Auth::id(),
            'resource_type' => 'ptm',
            'status' => 'pending',
            'request_payload' => [
                'DataPasien' => $payload['DataPasien'],
                'formData' => $payload['formData'],
                'composition' => $payload['composition'] ?? null,
            ],
        ]);

        $bundleId = (string) Str::uuid();
        $created = [];

        try {
            $resource = $mapper->mapEncounterAndObservations(
                $payload['DataPasien'],
                $payload['formData']
            );

            $created[] = $this->fhirService->createFhirResource($resource['encounter'], 'Encounter');

            foreach ($resource['observations'] as $obs) {
                $created[] = $this->fhirService->createFhirResource($obs, 'Observation');
            }

            foreach ($resource['service_requests'] as $sr) {
                $created[] = $this->fhirService->createFhirResource($sr, 'ServiceRequest');
            }

            $submission->update([
                'bundle_id' => $bundleId,
                'response_payload' => $created,
                'status' => 'sent',
                'error_message' => null,
            ]);

            return response()->json([
                'success' => true,
                'bundle_id' => $bundleId,
                'result' => $created,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Satusehat submitPtmPelayanan failed', [
                'submission_id' => $submission->id,
                'error' => $exception->getMessage(),
            ]);

            $submission->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'response_payload' => [
                    'error' => $exception->getMessage(),
                ],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim data ke Satu Sehat. ' . $exception->getMessage(),
            ], 422);
        }
    }
}
