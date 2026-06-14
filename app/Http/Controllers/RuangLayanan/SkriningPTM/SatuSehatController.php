<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Satusehat\SatuSehatService;
class SatuSehatController extends Controller
{
    protected $satusehatService;
    public function __construct(SatuSehatService $satusehatService)
    {
       $this->satusehatService = $satusehatService;
    }
    public function testEncounter(string $idSkrining, SatuSehatService $satusehatService)
{
    try {
        $encounterId = $satusehatService->kirimEncounter($idSkrining);

        return response()->json([
            'success' => true,
            'encounter_id' => $encounterId,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
    //
}
