<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use App\Models\RuangLayanan\KIA\Alergi;
use App\Models\RuangLayanan\KIA\KunjunganAnc;
use App\Models\RuangLayanan\MasterDiagnosaKasus;
use App\Models\RuangLayanan\MasterAlergi;
use App\Models\RuangLayanan\MasterObstetri;
use App\Models\RuangLayanan\MasterRiwayat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\RuangLayanan\SimpusDataDiagnosa;
use App\Models\RuangLayanan\SimpusDiagnosa;
use App\Models\RuangLayanan\SimpusDiagnosaaa;
use App\Models\RuangLayanan\SimpusLoket;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\tindakan;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class AncController extends Controller
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

        return Inertia::render('Ruang_Layanan/KIA/ANC/Index', [
            'DataUnit' => $DataUnit,
            'DataPasien' => $DataPasien,
        ]);
    }
    public function pelayanan($id, $idPoli, $idPelayanan)
    {
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_pelayanan as pel', 'l.idLoket', '=', 'pel.loketId')
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
                'p.ID',
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
                'l.idLoket',
                'pel.idPelayanan'
            )
            ->get();
        $diagnosa = SimpusDiagnosaaa::whereNotNull('F3')->get();
        $diagnosaKeperawatan = SimpusDiagnosa::where('kategori', 1)->get();
        $tindakan = tindakan::where('kdPoli', 003)->get();
        $riwayat = MasterRiwayat::all();
        $AlergiMakanan = Alergi::where('category', 1)->get();
        $AlergiObat = Alergi::where('category', 2)->get();
        $KunjunganAnc = KunjunganAnc::all();
        $DataDiagnosa = SimpusDataDiagnosa::all();
        // dd($AlergiObat);
        // dd($riwayat);
        // dd($DataPasien);
        // dd($DiagnosaKeperawatan);
        // dd($diagnosa);
        return Inertia::render('Ruang_Layanan/KIA/ANC/Pelayanan', [
            'DataPasien' => $DataPasien,
            'idPelayanan' => $idPelayanan,
            'idPoli' => $idPoli,
            'diagnosa' => $diagnosa,
            'tindakan' => $tindakan,
            'riwayat' => $riwayat,
            'diagnosaKeperawatan' => $diagnosaKeperawatan,
            'AlergiMakanan' => $AlergiMakanan,
            'AlergiObat' => $AlergiObat,
            'KunjunganAnc' => $KunjunganAnc,
            'DataDiagnosa' => $DataDiagnosa,
            // 'DataAnamnesa' => $DataAnamnesa,
            // 'DataKesadaran' => $DataKesadaran,
            // 'DiagnosaKasus' => $DiagnosaKasus,
            // 'MasterAlergi' => $MasterAlergi,
            // 'SimpusDataDiagnosa' => $SimpusDataDiagnosa,
            // 'SimpusTindakan' => $SimpusTindakan
        ]);
    }

    public function setKunjunganANC(Request $request)
    {
        KunjunganAnc::create([
            'loketId' => $request->loketId,
            'pasienId' => $request->pasienId,
            'tanggal_kunjungan' => $request->tgl_kunjungan,
            'usia_kehamilan' => $request->usia_hamil,
            'trimester' => $request->trimester,
        ]);
        return redirect()->back();
    }
    public function setObstetri(Request $request)
    {
        // Validasi awal (opsional tapi bagus untuk keamanan)
        $request->validate([
            'pasienId' => 'required',
            'statusImunisasi' => 'required',
            'imunisasi' => 'array',
        ]);

        MasterObstetri::create([
            'pasienId' => $request->pasienId,
            'gravida' => $request->gravida,
            'partus' => $request->partus,
            'abortus' => $request->abortus,
            'tphtDate' => $request->tanggal_tpht,
            'bbSebelumHamil' => $request->bb_sebelum,
            'bb_target' => $request->bbTarget,
            'tinggiBadan' => $request->tbadan,
            'imt' => $request->imt,
            'status_imt' => $request->status_imt,
            'jarak_hamil' => $request->jarak_hamil,
            'imunisasiTtStatus' => $request->statusImunisasi,
            //   tanggal_tpht: '',
            //   bb_sebelum: '',
            //   bbTarget: '',
            //   imt: '',
            //   status_imt: '',
            //   jarak_hamil: '',
            //   statusImunisasi
        ]);
        // dd($data);
        return redirect()->back();
    }

    public function setDataDiagnosa(Request $request)
    {
        SimpusDataDiagnosa::create([
            'kdDiagnosa' => $request->kode_diagnosa,
            'nmDiagnosa' => $request->nama_diagnosa,
            'diagnosaKasus' => $request->kunjungan_khusus,
            'keterangan' => $request->keterangan,
            'kdPoli' => $request->kdPoli,
            'loketId' => $request->loketId,
            'pelayananId' => $request->pelayananId,
        ]);
        // dd($data);
        return redirect()->back();
    }
    public function setDataDiagnosaKep(Request $request)
    {
        $data = SimpusDataDiagnosa::create([
            'kdDiagnosa' => $request->kode_diagnosa,
            'nmDiagnosa' => $request->diagnosaKep,
            'kdPoli' => $request->kdPoli,
            'loketId' => $request->loketId,
            'pelayananId' => $request->pelayananId,
        ]);
        dd($data);
        return redirect()->back();
    }
    public function hapusDataDiagnosa($id)
    {
        $diagnosa = SimpusDataDiagnosa::find($id);

        if (!$diagnosa) {
            return back()->withErrors(['msg' => 'Data tidak ditemukan']);
        }

        $diagnosa->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
