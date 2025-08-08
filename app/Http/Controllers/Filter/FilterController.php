<?php

namespace App\Http\Controllers\Filter;

use App\Http\Controllers\Controller;
use App\Models\Filter\DataMasterUnit;
use App\Models\Filter\DataMasterUnitDetail;
use App\Models\Filter\DiagnosaKasus;
use App\Models\Filter\MasterWilayah;
use App\Models\Filter\SetupDesa;
use App\Models\Filter\SetupKecamatan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Filter\SimpusProvider;

class FilterController extends Controller
{
    public function index(){
        $providers = SimpusProvider::all();
        $data_master_unit = DataMasterUnit::with(['detail'])->get();
        $data_master_unit_detail = DataMasterUnitDetail::all();
        $kunj_kasus = DiagnosaKasus::all();
        $asal = MasterWilayah::all();
        $kecamatan = SetupKecamatan::where('no_kab', 10)
                          ->where('no_prop', 35)
                          ->get();
        $desa = SetupDesa::where('no_kab', 10)
                          ->where('no_prop', 35)
                          ->get();

        return Inertia::render('Filter/Index', [
            'providers' => $providers,
            'tempat_kunjungan' => $data_master_unit,
            'detail_tempat_kunjungan' => $data_master_unit_detail,
            'kunj_kasus' => $kunj_kasus,
            'asal' => $asal,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
        ]);
    }
    //
    public function modal(){
        return Inertia::render('Filter/Modal');
    }
    public function dev(){
        return Inertia::render('Filter/card');
    }
    //
}
