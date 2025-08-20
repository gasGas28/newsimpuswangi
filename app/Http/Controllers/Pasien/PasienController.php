<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Filter\SimpusPasien;
use App\Models\Pasien\Pasien;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::all();
        return Inertia::render('Loket/SearchPasien', [
            'pasien' => $pasien,
        ]);
    }
    
}
