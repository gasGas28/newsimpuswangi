<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use App\Models\RuangLayanan\SimpusLoket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;


class PoliBpUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
            ->where('no_kec', 18)
            ->orderBy('id_kategori')
            ->get();

        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->join('setup_kel as kel', function ($join) {
                $join->on('p.NO_KEL', '=', 'kel.NO_KEL')
                    ->on('p.NO_KEC', '=', 'kel.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kel.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })
            ->join('setup_kec as kec', function ($join) {
                $join->on('p.NO_KEC', '=', 'kec.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kec.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })
            ->join('setup_kab as kab', function ($join) {
                $join->on('p.NO_KAB', '=', 'kab.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })
            ->join('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
            ->where('l.kdPoli', operator: 001)
            ->select(
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli',
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'l.tglKunjungan',
                'l.idLoket'
            )
            ->get();

        return Inertia::render('Ruang_Layanan/Umum/pasien_poli', [
            'DataUnit' => $DataUnit,
            'DataPasien' => $DataPasien,
        ]);

    }
    public function pelayanan($id)
    {
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->join('setup_kel as kel', function ($join) {
                $join->on('p.NO_KEL', '=', 'kel.NO_KEL')
                    ->on('p.NO_KEC', '=', 'kel.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kel.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })
            ->join('setup_kec as kec', function ($join) {
                $join->on('p.NO_KEC', '=', 'kec.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kec.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })
            ->join('setup_kab as kab', function ($join) {
                $join->on('p.NO_KAB', '=', 'kab.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })
            ->join('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
            ->where('l.kdPoli', operator: 001)
            ->where('idLoket', $id)
            ->select(
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli',
                'l.kdPoli',
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'p.jenis_klmin',
                'l.umur',
                'l.umur_bulan',
                'umur_hari',
                'l.tglKunjungan',
                'l.idLoket'
            )
            ->get();
        $DataAnamnesa = DB::table('simpus_anamnesa')->where('loketId', $DataPasien[0]->idLoket)->first();
        $DataKesadaran = DB::table('simpus_kesadaran')->get();
        $DiagnosaKasus = DB::table('master_diagnosa_kasus')->get();
        $MasterAlergi = DB::table('master_alergi')->get();
        $DiagnosaMedis = DB::table('simpus_diagnosa')->get();
        return Inertia::render('Ruang_Layanan/Umum/pelayanan', [
            'DataPasien' => $DataPasien,
            'DataAnamnesa' => $DataAnamnesa,
            'DataKesadaran' => $DataKesadaran,
            'DiagnosaKasus' => $DiagnosaKasus,
            'MasterAlergi' =>$MasterAlergi,
            'DiagnosaMedis' => $DiagnosaMedis
        ]);

    }

    public function setAnamnesa(Request $request)
    {
        DB::table('simpus_anamnesa')
            ->where('loketid', $request->idLoket)
            ->update([
                'tglAnamnesa' => $request->tgl_anamnesa,
                'keluhan' => $request->keluhan_utama,
                'keluhanTambahan' => $request->keluhan_tambahan,
                'riwayatPenyakitSekarang' => $request->riwayat_penyakit_sekarang,
                'riwayatPenyakitDahulu' => $request->riwayat_penyakit_dahulu,
                'riwayatPenyakitKeluarga' => $request->riwayat_penyakit_keluarga,
                'terapiYangPernahDijalani' => $request->tindakan,
                'obatSeringDigunakan' => $request->obat_digunakan,
                'obatSeringDikonsumsi' => $request->obat_dikonsumsi
            ]);

           return redirect()->back();
    }
    public function setAnamnesaObjective(Request $request)
    {

        $anamnesa = DB::table('simpus_anamnesa')
            ->where('idAnamnesa', $request->idAnamnesa)
            ->first();
        $dataUpdate = [
            'keadaanUmum' => $request->keadaan_umum ?? $anamnesa->keadaanUmum,
            'kdSadar' => $request->kesadaran ?? $anamnesa->kdSadar,
            'imt' => $request->imt ?? $anamnesa->imt,
            'tinggiBadan' => $request->tinggi_badan ?? $anamnesa->tinggiBadan,
            'beratBadan' => $request->berat_badan ?? $anamnesa->beratBadan,
            'lingkarPerut' => $request->lingkar_perut ?? $anamnesa->lingkarPerut,
            'lingkarTangan' => $request->lingkar_lengan ?? $anamnesa->lingkarTangan,
            'sistole' => $request->sistole ?? $anamnesa->sistole,
            'diastole' => $request->diastole ?? $anamnesa->diastole,
            'respRate' => $request->resp_rate ?? $anamnesa->respRate,
            'heartRate' => $request->heart_rate ?? $anamnesa->heartRate,
            'suhu' => $request->suhu ?? $anamnesa->suhu,

            'thoraxJantung' => $request->jantung ?? $anamnesa->thoraxJantung,
            'thoraxJantungKet' => $request->ket_jantung ?? $anamnesa->thoraxJantungKet,

            'thoraxPulmo' => $request->pulmo ?? $anamnesa->thoraxPulmo,
            'thoraxPulmoKet' => $request->ket_pulmo ?? $anamnesa->thoraxPulmoKet,

            'abdomanAtas' => $request->abdomen_atas ?? $anamnesa->abdomanAtas,
            'abdomanAtasKet' => $request->ket_abdomen_atas ?? $anamnesa->abdomanAtasKet,

            'abdomanBawah' => $request->abdomen_bawah ?? $anamnesa->abdomanBawah,
            'abdomanBawahKet' => $request->ket_abdomen_bawah ?? $anamnesa->abdomanBawahket,

            'extrimitasAtas' => $request->extrimitas_atas ?? $anamnesa->extrimitasAtas,
            'extrimitasAtasKet' => $request->ket_extrimitas_atas ?? $anamnesa->extrimitasAtasKet,

            'extrimitasBawah' => $request->extrimitas_bawah ?? $anamnesa->extrimitasBawah,
            'extrimitasBawahKet' => $request->ket_extrimitas_bawah ?? $anamnesa->extrimitasBawahKet,

            'kepala' => $request->kepala ?? $anamnesa->kepala,
            'kepalaKet' => $request->ket_kepala ?? $anamnesa->kepalaKet,

            'mata' => $request->mata ?? $anamnesa->mata,
            'mataKet' => $request->ket_mata ?? $anamnesa->mataKet,

            'telinga' => $request->telinga ?? $anamnesa->telinga,
            'telingaKet' => $request->ket_telinga ?? $anamnesa->telingaKet,

            'leher' => $request->leher ?? $anamnesa->leher,
            'leherKet' => $request->ket_leher ?? $anamnesa->leherKet,

            'tenagaMedisAskep' => $request->tenaga_medis_askep ?? $anamnesa->tenagaMedisAskep,
        ];
        DB::table('simpus_anamnesa')
            ->where('idAnamnesa', $request->idAnamnesa)
            ->update($dataUpdate);
        return redirect()->back();

    }

    public function mulaiPemeriksaanPasien(Request $request)
    {
        DB::table('simpus_anamnesa')->insert([
            'loketid' => $request->idLoket,
            'idAnamnesa' => Str::uuid(),
            'tglAnamnesa' => $request->tglKunjungan
        ]);
        return redirect()->back();
    }

    public function setDiagnosaMedis(Request $request){
        dd($request->all());

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
