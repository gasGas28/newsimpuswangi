<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Farmasi\MasterObatController;
use App\Http\Controllers\PengeluaranLangsungController;

Route::apiResource('obat', MasterObatController::class);

Route::prefix('farmasi')->group(function () {
    Route::get('/resep-langsung', [PengeluaranLangsungController::class, 'index']);
    Route::post('/resep-langsung', [PengeluaranLangsungController::class, 'store']);
    Route::get('/resep-langsung/{id}', [PengeluaranLangsungController::class, 'show']);
    Route::put('/resep-langsung/{id}', [PengeluaranLangsungController::class, 'update']);
    Route::delete('/resep-langsung/{id}', [PengeluaranLangsungController::class, 'destroy']);
});