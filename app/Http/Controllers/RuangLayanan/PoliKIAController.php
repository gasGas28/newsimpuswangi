<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use App\Models\RuangLayanan\MasterRiwayat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\RuangLayanan\SimpusDataDiagnosa;
use App\Models\RuangLayanan\SimpusDiagnosa;
use App\Models\RuangLayanan\SimpusLoket;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\tindakan;
use Inertia\Inertia;

class PoliKIAController extends Controller
{
    // public function index()
    // {
    //     $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
    //         ->where('no_kec', 18)
    //         ->orderBy('id_kategori')
    //         ->get();

    //     $DataPasien = DB::table('simpus_loket as l')
    //         ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
    //         ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
    //         ->join('setup_kel as kel', function ($join) {
    //             $join->on('p.NO_KEL', '=', 'kel.NO_KEL')
    //                 ->on('p.NO_KEC', '=', 'kel.NO_KEC')
    //                 ->on('p.NO_KAB', '=', 'kel.NO_KAB')
    //                 ->on('p.NO_PROP', '=', 'kel.NO_PROP');
    //         })
    //         ->join('setup_kec as kec', function ($join) {
    //             $join->on('p.NO_KEC', '=', 'kec.NO_KEC')
    //                 ->on('p.NO_KAB', '=', 'kec.NO_KAB')
    //                 ->on('p.NO_PROP', '=', 'kec.NO_PROP');
    //         })
    //         ->join('setup_kab as kab', function ($join) {
    //             $join->on('p.NO_KAB', '=', 'kab.NO_KAB')
    //                 ->on('p.NO_PROP', '=', 'kab.NO_PROP');
    //         })
    //         ->join('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
    //         ->where('l.kdPoli', '=', '003')
    //         ->select(
    //             'p.NO_MR',
    //             'p.NAMA_LGKP',
    //             'p.NIK',
    //             'kel.nama_kel',
    //             'kec.nama_kec',
    //             'kab.nama_kab',
    //             'prop.nama_prop',
    //             'poli.nmPoli',
    //             'p.alamat',
    //             'p.no_rt',
    //             'p.no_rw',
    //             'l.tglKunjungan',
    //             'l.idLoket'
    //         )
    //         ->take(100)
    //         ->get();

    //     // dd($DataPasien);

    //     return Inertia::render('Ruang_Layanan/KIA/index', [
    //         'DataUnit' => $DataUnit,
    //         'DataPasien' => $DataPasien,
    //     ]);
    // }
    public function index()
    {
        $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
            ->where('no_kec', 18)
            ->orderBy('id_kategori')
            ->get();

        $DataPasien = DB::table('simpus_pelayanan as pel')
            ->join('simpus_loket as l', 'pel.loketId', '=', 'l.idLoket')
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
            ->where('l.kdPoli', 003)
            ->select(
                'pel.idpelayanan',
                'pel.tglPelayanan',
                'pel.sudahDilayani',
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
                'l.idLoket',
                'l.kdPoli'
            )
            ->get();
        //dd($DataPasien);

        return Inertia::render('Ruang_Layanan/KIA/RuangLayanan', [
            'DataUnit' => $DataUnit,
            'DataPasien' => $DataPasien,
        ]);
    }
    public function pelayanan($id, $idPoli, $idPelayanan)
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
            ->where('l.kdPoli', operator: 003)
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
        $diagnosa = SimpusDiagnosa::all();
        // $DataAnamnesa = DB::table('simpus_anamnesa')->where('loketId', $DataPasien[0]->idLoket)->first();
        // $DataKesadaran = DB::table('simpus_kesadaran')->get();
        // $DiagnosaKasus = DB::table('master_diagnosa_kasus')->get();
        // $MasterAlergi = DB::table('master_alergi')->get();
        // $SimpusDataDiagnosa = SimpusDataDiagnosa::with(['SimpusPoliFKTP', 'MasterDiagnosaKasus'])->where('kdPoli', '001')->where('loketId', $DataPasien[0]->idLoket)->get();
        // $SimpusTindakan = SimpusTindakan::where('loketId', $DataPasien[0]->idLoket)->get();
        // $DiagnosaKeperawatan = SimpusDiagnosa::where('kategori', 1)->get();
        // $diagnosa = SimpusDiagnosa::where('kategori', 1)->get();
        $diagnosa = SimpusDiagnosa::where('F6', 1)->get();
        // $tindakan = tindakan::where('idTindakan', 256);
        $tindakan = tindakan::where('kdPoli', 003)->get();
        $riwayat = MasterRiwayat::all();

        // dd($riwayat);


        // dd($DataPasien);
        // dd($DiagnosaKeperawatan);
        // dd($tindakan);
        return Inertia::render('Ruang_Layanan/KIA/Pelayanan', [
            'DataPasien' => $DataPasien,
            'idPelayanan' => $idPelayanan,
            'idPoli' => $idPoli,
            'diagnosa' => $diagnosa,
            'tindakan' => $tindakan,
            'riwayat' => $riwayat,
            // 'DataAnamnesa' => $DataAnamnesa,
            // 'DataKesadaran' => $DataKesadaran,
            // 'DiagnosaKasus' => $DiagnosaKasus,
            // 'MasterAlergi' => $MasterAlergi,
            // 'SimpusDataDiagnosa' => $SimpusDataDiagnosa,
            // 'SimpusTindakan' => $SimpusTindakan
        ]);

    }
    public function searchDiagnosa(Request $request)
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
}
