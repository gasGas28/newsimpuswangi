<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use App\Models\RuangLayanan\SimpusDataDiagnosa;
use App\Models\RuangLayanan\SimpusLoket;
use App\Models\RuangLayanan\SimpusTindakan;
use DB;
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
              $SimpusDataDiagnosa = SimpusDataDiagnosa::with(['SimpusPoliFKTP', 'MasterDiagnosaKasus'])->where('kdPoli', '001')->where('loketId', $DataPasien[0]->idLoket)->get();
        $SimpusTindakan = SimpusTindakan::where('loketId', $DataPasien[0]->idLoket)->get();
        return Inertia::render('Ruang_Layanan/Umum/pelayanan', [
            'DataPasien' => $DataPasien,
            'DataAnamnesa' => $DataAnamnesa,
            'DataKesadaran' => $DataKesadaran,
            'DiagnosaKasus' => $DiagnosaKasus,
            'MasterAlergi' => $MasterAlergi,
            'SimpusDataDiagnosa' => $SimpusDataDiagnosa,
            'SimpusTindakan' => $SimpusTindakan
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

    public function setDiagnosaMedis(Request $request)
    {
        //dd($request->all());
        SimpusDataDiagnosa::create([
            'kdDiagnosa' => $request->kode_diagnosa,
            'nmDiagnosa' => $request->nama_diagnosa,
            'diagnosaKasus' => $request->kunjungan_khusus,
            'keterangan' => $request->keterangan_kunjungan,
            'kdPoli' => $request->kdPoli,
            'loketId' => $request->loketId,
            'pelayananId' => $request->pelayananId,
        ]);
        return redirect()->back();
    }

    public function setPlanningTindakan(Request $request)
    {
        //dd($request->all());
        SimpusTindakan::create([
            'idPelayanan' => 1,
            'loketId' => $request->loketId,
            'kdTindakan'=> $request->kode_tindakan,
            'nmTindakan'=>$request->nama_tindakan,
            'nmTindakanInd' => $request->nama_tindakan_indonesia,
            'keterangan' => $request->keterangan_tindakan,
            'kdPoli' => $request->kdPoli,
        ]);
        return redirect()->back();
    }
    public function paginasi(Request $request)
    {
        $search = $request->get('search');

        // query awal
        $query = DB::table('simpus_diagnosa');

        // kalau ada search, filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nmDiag', 'like', "%{$search}%")
                    ->orWhere('kdDiag', 'like', "%{$search}%");
            });
        }

        $DiagnosaMedis = $query->paginate(10);

        // simpan query string search supaya ikut di pagination
        $DiagnosaMedis->appends(['search' => $search]);

        $links = [];

        // Previous
        $links[] = [
            'label' => 'Previous',
            'url' => $DiagnosaMedis->previousPageUrl(),
            'active' => false,
        ];

        $lastPage = $DiagnosaMedis->lastPage();
        $current = $DiagnosaMedis->currentPage();

        // Always tampil halaman pertama
        $links[] = [
            'label' => 1,
            'url' => $DiagnosaMedis->url(1),
            'active' => $current === 1,
        ];

        // Tambah "..." kalau current jauh dari 1
        if ($current > 3) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Window: current-1, current, current+1
        for ($i = max(2, $current - 1); $i <= min($lastPage - 1, $current + 1); $i++) {
            $links[] = [
                'label' => $i,
                'url' => $DiagnosaMedis->url($i),
                'active' => $current === $i,
            ];
        }

        // Tambah "..." kalau current jauh dari lastPage
        if ($current < $lastPage - 2) {
            $links[] = ['label' => '...', 'url' => null, 'active' => false];
        }

        // Always tampil halaman terakhir (kalau lebih dari 1)
        if ($lastPage > 1) {
            $links[] = [
                'label' => $lastPage,
                'url' => $DiagnosaMedis->url($lastPage),
                'active' => $current === $lastPage,
            ];
        }

        // Next
        $links[] = [
            'label' => 'Next',
            'url' => $DiagnosaMedis->nextPageUrl(),
            'active' => false,
        ];

        return response()->json([
            'data' => $DiagnosaMedis->items(),
            'meta' => [
                'current_page' => $DiagnosaMedis->currentPage(),
                'last_page' => $DiagnosaMedis->lastPage(),
                'per_page' => $DiagnosaMedis->perPage(),
                'total' => $DiagnosaMedis->total(),
            ],
            'links' => $links,
        ]);
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
