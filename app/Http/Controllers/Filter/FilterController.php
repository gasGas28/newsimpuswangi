<?php

namespace App\Http\Controllers\Filter;

use App\Http\Controllers\Controller;
use App\Models\Filter\DataMasterUnit;
use App\Models\Filter\DataMasterUnitDetail;
use App\Models\Filter\DiagnosaKasus;
use App\Models\Filter\MasterWilayah;
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

        return Inertia::render('Filter/Index', [
            'providers' => $providers,
            'tempat_kunjungan' => $data_master_unit,
            'detail_tempat_kunjungan' => $data_master_unit_detail,
            'kunj_kasus' => $kunj_kasus,
            'asal' => $asal,
        ]);
    }
    //
    public function modal(){
        return Inertia::render('Filter/Modal');
    }
    //
}
