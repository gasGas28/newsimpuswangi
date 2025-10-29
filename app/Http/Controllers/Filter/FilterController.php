<?php

namespace App\Http\Controllers\Filter;

use App\Http\Controllers\Controller;
use App\Models\Filter\DataMasterUnit;
use App\Models\Filter\DataMasterUnitDetail;
use App\Models\Filter\DiagnosaKasus;
use App\Models\Filter\MasterWilayah;
use App\Models\Filter\SetupDesa;
use App\Models\Filter\SetupKecamatan;
use App\Models\Filter\SimpusKunjungan;
use App\Models\Filter\Loket;
use App\Models\Filter\SimpusLoket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Filter\SimpusProvider;
use App\Models\Filter\UnitFilter;

class FilterController extends Controller
{
    
    public function index()
    {
        // $providers = SimpusProvider::all();
        // $data_master_unit = DataMasterUnit::with(['detail'])->get();
        // $data_master_unit_detail = DataMasterUnitDetail::all();
        // $kunj_kasus = DiagnosaKasus::all();
        // $asal = MasterWilayah::all();
        // $kecamatan = SetupKecamatan::where('no_kab', 10)
        //     ->where('no_prop', 35)
        //     ->get();
        // $desa = SetupDesa::where('no_kab', 10)
        //     ->where('no_prop', 35)
        //     ->get();
        // $unit = UnitFilter::where('pelayanan', 'TRUE')->get();
        // $kunjungan = SimpusKunjungan::all();
        $data = SimpusLoket::with([
            'pasien',
            'anamnesa',
            'obat',
            'kunjungan'
        ])->get();

        // dd($data);
        // dd($data->first()->toArray());

        return Inertia::render('Filter/card', [
            'rekamMedis' => $data,
        ]);
    }

    // public function index()
    // {
    //     $providers = SimpusProvider::all();
    //     $data_master_unit = DataMasterUnit::with(['detail'])->get();
    //     $data_master_unit_detail = DataMasterUnitDetail::all();
    //     $kunj_kasus = DiagnosaKasus::all();
    //     $asal = MasterWilayah::all();
    //     $kecamatan = SetupKecamatan::where('no_kab', 10)
    //         ->where('no_prop', 35)
    //         ->get();
    //     $desa = SetupDesa::where('no_kab', 10)
    //         ->where('no_prop', 35)
    //         ->get();
    //     $unit = UnitFilter::where('pelayanan', 'TRUE')->get();
    //     $kunjungan = SimpusKunjungan::all();
    //     $data = SimpusLoket::with([
    //         'pasien',
    //         'anamnesa',
    //         'obat',
    //         'kunjungan'
    //     ])->get();
    //     // $loket = \App\Models\Filter\SimpusLoket::with('anamnesa')->first();
    //     // dd($loket->idLoket, $loket->anamnesa);
    //     // dd(SimpusLoket::with('anamnesa')->first()->toArray());

    //     return Inertia::render('Filter/Index', [
    //         'providers' => $providers,
    //         'tempat_kunjungan' => $data_master_unit,
    //         'detail_tempat_kunjungan' => $data_master_unit_detail,
    //         'kunj_kasus' => $kunj_kasus,
    //         'asal' => $asal,
    //         'kecamatan' => $kecamatan,
    //         'desa' => $desa,
    //         'unit' => $unit,
    //         'kunjungan' => $kunjungan,
    //         'rekamMedis' => $data
    //     ]);
    // }
    //
    // public function modal()
    // {
    //     return Inertia::render('Filter/Modal');
    // }
}
