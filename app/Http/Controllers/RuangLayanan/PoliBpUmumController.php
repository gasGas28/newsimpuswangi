<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use App\Models\RuangLayanan\MasterDokter;
use App\Models\RuangLayanan\SetupKec;
use App\Models\RuangLayanan\SimpusAlergiData;
use App\Models\RuangLayanan\SimpusAnamnesa;
use App\Models\RuangLayanan\SimpusDataDiagnosa;
use App\Models\RuangLayanan\SimpusDetailResepObat;
use App\Models\RuangLayanan\SimpusDiagnosa;
use App\Models\RuangLayanan\SimpusLoket;
use App\Models\RuangLayanan\SimpusMasterObat;
use App\Models\RuangLayanan\SimpusPasien;
use App\Models\RuangLayanan\SimpusPelayanan;
use App\Models\RuangLayanan\SimpusPermohonanLab;
use App\Models\RuangLayanan\SimpusPoliFKTL;
use App\Models\RuangLayanan\SimpusPoliFKTP;
use App\Models\RuangLayanan\SimpusProvider;
use App\Models\RuangLayanan\SimpusResepObat;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\StatusPulang;
use App\Models\RuangLayanan\SuratKeterangan;
use App\Models\RuangLayanan\SuratMaster;
use App\Models\RuangLayanan\SuratRujuk;
use App\Models\RuangLayanan\UnitProfile;
use DB;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

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
            ->where('l.kdPoli', 001)
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

        return Inertia::render('Ruang_Layanan/Umum/pasien_poli', [
            'DataUnit' => $DataUnit,
            'DataPasien' => $DataPasien,
        ]);

    }
    public function pelayanan($id, $idPoli, $idpelayanan)
    {
        // dd($idpelayanan);
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
                'p.ID',
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
        $AlergiPasien = SimpusAlergiData::with(['alergiObat', 'alergiMakanan'])->where('pasienId', $DataPasien[0]->ID)->get();
        $StatusPulang = StatusPulang::all();
        $SimpusResepObat = SimpusResepObat::with('SimpusDetailResepObat.MasterObat', 'Loket.SimpusPoli')->where('loketId', $DataPasien[0]->idLoket)->get();
        $MasterObat = SimpusMasterObat::all();
        // dd($SimpusResepObat);
        $TenagaMedisAskep = MasterDokter::whereIn('profesi_id', [19, 20])->get();
        $DiagnosaKeperawatan = SimpusDiagnosa::where('kategori', 1)->get();
        $DataRujuk = SimpusPelayanan::with(['SimpusLoket', 'SimpusPoli', 'StatusPulang'])->where('loketId', $DataPasien[0]->idLoket)->orderBy('createdDate', 'desc')->get();
        return Inertia::render('Ruang_Layanan/Umum/pelayanan', [
            'DataPasien' => $DataPasien,
            'DataAnamnesa' => $DataAnamnesa,
            'DataKesadaran' => $DataKesadaran,
            'DiagnosaKasus' => $DiagnosaKasus,
            'MasterAlergi' => $MasterAlergi,
            'SimpusDataDiagnosa' => $SimpusDataDiagnosa,
            'SimpusTindakan' => $SimpusTindakan,
            'AlergiPasien' => $AlergiPasien[0],
            'StatusPulang' => $StatusPulang,
            'SimpusResepObat' => $SimpusResepObat,
            'MasterObat' => $MasterObat,
            'idPelayanan' => $idpelayanan,
            'TenagaMedisAskep' => $TenagaMedisAskep,
            'DiagnosaKeperawatan' => $DiagnosaKeperawatan,
            'DataRujuk' => $DataRujuk,
            'idPoli' => $idPoli
        ]);
    }

    public function setAnamnesa(Request $request)
    {
        SimpusAnamnesa::where('loketid', $request->idLoket)
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
        $AlergiPasien = SimpusAlergiData::where('pasienId', $request->idPasien)->first();
        if (!$AlergiPasien) {
            SimpusAlergiData::create([
                'idAlergi' => Str::random(20),
                'pasienId' => $request->idPasien,
                'kodeAlergiObat' => $request->alergi_obat,
                'kodeAlergiMakanan' => $request->alergi_makanan,
                'keterangan' => $request->keterangan_alergi
            ]);
        } else {
            $AlergiPasien->update([
                'kodeAlergiObat' => $request->alergi_obat,
                'kodeAlergiMakanan' => $request->alergi_makanan,
                'keterangan' => $request->keterangan_alergi
            ]);
        }
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
        //dd($request->all());
        DB::table('simpus_anamnesa')->insert([
            'loketid' => $request->idLoket,
            'idAnamnesa' => Str::uuid(),
            'tglAnamnesa' => $request->tglKunjungan
        ]);
        $sudahDilayani = '2';
        $progressTime = now()->format('Y-m-d H:i:s');
        SimpusPelayanan::where('idpelayanan', $request->idPelayanan)
            ->update([
                'sudahDilayani' => $sudahDilayani,
                'progressTime' => $progressTime,
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

    public function setDiagnosaKeperawatan(Request $request, $idLoket, $idPelayanan)
    {
        //dd($request->all());
        SimpusDataDiagnosa::create([
            'kdDiagnosa' => $request->kode_diagnosa,
            'nmDiagnosa' => $request->nama_diagnosa,
            'kdPoli' => $request->kdPoli,
            'loketId' => $idLoket,
            'pelayananId' => $idPelayanan,
        ]);
        return redirect()->back();

    }

    public function setPlanningTindakan(Request $request)
    {
        // dd($request->all());
        SimpusTindakan::create([
            'idPelayanan' => $request->idPelayanan,
            'loketId' => $request->loketId,
            'kdTindakan' => $request->kode_tindakan,
            'nmTindakan' => $request->nama_tindakan,
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

    public function setResepObat(Request $request, $idLoket, $idPelayanan)
    {
        // dd($request->all());
        SimpusResepObat::create([
            'id_resep' => Str::uuid(),
            'kode_resep' => Str::random(10),
            'loketId' => $idLoket,
            'pelayananId' => $idPelayanan,
            'kategori' => $request->jenis_obat,
            'nama_puyer' => $request->jenis_obat === '1' ? 'Puyer' : ($request->jenis_obat === '0' ? 'Bukan Puyer' : null),
            'jumlah_puyer' => $request->jumlah_puyer,
            'dosis_pakai_puyer' => $request->dosis_pakai,
            'tiapJam' => $request->dosis_pakai_jam,
            'waktu' => is_array($request->waktu) ? implode(',', $request->waktu) : $request->waktu,
            'kondisi' => is_array($request->kondisi) ? implode(',', $request->kondisi) : $request->kondisi,
        ]);
        return redirect()->back();
    }
    public function setDetailResepObat(Request $request, $idResep, $idObat)
    {
        //dd($idResep);
        SimpusDetailResepObat::create([
            'id_resep_detail' => Str::uuid(),
            'resep_id' => $idResep,
            'poli' => $request->poli,
            'obat_id' => $idObat,
            'jumlah' => $request->jumlah_obat,
            'jumlah_puyer' => $request->jumlah_puyer,
            'dosis_pakai' => $request->dosis_pakai,
            'tiapJam' => $request->dosis_pakai_jam,
            'waktu' => is_array($request->waktu) ? implode(',', $request->waktu) : $request->waktu,
            'kondisi' => is_array($request->kondisi) ? implode(',', $request->kondisi) : $request->kondisi,
        ]);
        return redirect()->back();
    }
    public function hapusResepObat($idResepObat)
    {
        // dd($idResepObat);
        SimpusResepObat::where('id_resep', $idResepObat)->delete();
        SimpusDetailResepObat::where('resep_id', $idResepObat)->delete();
        return redirect()->back();
    }
    public function hapusDetailResepObat($idDetailResepObat)
    {
        SimpusDetailResepObat::where('id_resep_detail', $idDetailResepObat)->delete();
        return redirect()->back();

    }

    public function simpanRujukan(Request $request)
    {
        // dd($request->all());
        SimpusPelayanan::where('idPelayanan', $request->idPelayanan)->update([
            'sudahDilayani' => 1,
            'kdStatusPulang' => $request->status_pulang,
            'tenagaMedis' => $request->tenaga_medis,
            'endTime' => now()
        ]);
        return redirect()->back();


    }

    public function suketList($idPoli, $idPelayanan)
    {
        $suketList = SuratKeterangan::where('id_pelayanan', $idPelayanan)->with(['jenisSurat', 'SimpusPelayanan.SimpusLoket'])->get();
        //dd($suketList);
        return Inertia::render('Ruang_Layanan/Umum/suratKeterangan', [
            'suketList' => $suketList,
            'idPoli' => $idPoli,
            'idPelayanan' => $idPelayanan
        ]);

    }

    public function createSuratKeterangan($idPoli, $idPelayanan)
    {
        $jenisSurat = SuratMaster::get();
        $pelayanan = SimpusPelayanan::where('idPelayanan', $idPelayanan)->first();
        // dd($pelayanan);
        $dataAnamnesa = SimpusAnamnesa::with(['loket', 'loket.SimpusPasien.SetupKab', 'loket.SimpusPasien.SetupKel'])->where('loketId', $pelayanan->loketId)->first();
        //dd($dataAnamnesa);
        $TenagaMedisAskep = MasterDokter::whereIn('profesi_id', [19, 20])->where('aktif', 1)->get();
        return Inertia::render('Ruang_Layanan/Umum/SuratKeteranganCreate', [
            'jenisSurat' => $jenisSurat,
            'idPoli' => $idPoli,
            'idPelayanan' => $idPelayanan,
            'dataAnamnesa' => $dataAnamnesa,
            'tenagaMedisAskep' => $TenagaMedisAskep
        ]);
    }

    public function simpanSuket(Request $request, $idPoli)
    {
        // dd($request->all());
        if ($request->idAnamnesa) {
            SimpusAnamnesa::where('idAnamnesa', $request->idAnamnesa)->update(
                [
                    'tinggiBadan' => $request->tinggiBadan,
                    'beratBadan' => $request->beratBadan,
                    'sistole' => $request->sistole,
                    'diastole' => $request->diastole,
                    'respRate' => $request->respRate,
                    'heartRate' => $request->heartRate,
                    'suhu' => $request->suhu
                ]
            );
        }
        SuratKeterangan::create([
            'id_jns_surat' => $request->id_jns_surat,
            'jenis_surat' => $request->jenis_surat,
            'no_surat' => $request->no_surat,
            'keperluan' => $request->keperluan,
            'keterangan' => $request->ket_lain,
            'tgl_ijin_awal' => $request->tgl_ijin_awal,
            'tgl_ijin_akhir' => $request->tgl_ijin_akhir,
            'tgl_kematian' => $request->tgl_kematian,
            'jam_kematian' => $request->jam_kematian,
            'ket_kematian' => $request->ket_kematian,
            'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
            'mata_ka_ki' => $request->mata_ka_ki,
            'telinga_ka_ki' => $request->telinga_ka_ki,
            'test_buta_warna' => $request->test_buta_warna,
            'tenagaMedis' => $request->tenagaMedis,
            'id_pelayanan' => $request->id_pelayanan,
            'modified_date' => now(),
            'created_date' => now(),
        ]);
        return redirect()->back();
    }

    public function hapusSuket($idSurat)
    {
        SuratKeterangan::where('id_surat', $idSurat)->delete();
        return redirect()->back();
    }

    public function cetakSuket($idSurat)
    {
        // dd($idSurat);
        $suket = SuratKeterangan::where('id_surat', $idSurat)->with([
            'jenisSurat',
            'SimpusPelayanan.SimpusLoket.SimpusPasien'
        ])->first();
        $dataPasien = DB::table('simpus_pasien as p')
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
            ->join('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')->where('p.ID', $suket->SimpusPelayanan->SimpusLoket->pasienId)->first();
        $dataAnamnesa = SimpusAnamnesa::where('loketId', $suket->SimpusPelayanan->loketId)->first();

        $unit = UnitProfile::where('unit_id', 1)->first();
        $dokter = MasterDokter::where('idDokter', $suket->tenagaMedis)->first();
        $kecamatan = SetupKec::where('NO_KAB', 10)->where('NO_PROP', 35)->where('NO_KEC', $unit->no_kec)->first();
        // dd($kecamatan);
        //   dd($dokter);
        //   dd($dataAnamnesa);
        //dd($DataPasien);

        if ($suket->id_jns_surat == 1) {
            return Inertia::render('Ruang_Layanan/Umum/cetakSuratKeterangan', [
                'suket' => $suket,
                'dataPasien' => $dataPasien,
                'dataAnamnesa' => $dataAnamnesa,
                'unit' => $unit,
                'dokter' => $dokter,
                'kecamatan' => $kecamatan
            ]);
        } elseif ($suket->id_jns_surat == 2) {
            return Inertia::render('Ruang_Layanan/Umum/cetakSuratKeteranganDokter', [
                'suket' => $suket,
                'dataPasien' => $dataPasien,
                'dataAnamnesa' => $dataAnamnesa,
                'unit' => $unit,
                'dokter' => $dokter,
                'kecamatan' => $kecamatan
            ]);
        } elseif ($suket->id_jns_surat == 3) {
            return Inertia::render('Ruang_Layanan/Umum/cetakSuratKeteranganCatin', [
                'suket' => $suket,
                'dataPasien' => $dataPasien,
                'dataAnamnesa' => $dataAnamnesa,
                'unit' => $unit,
                'dokter' => $dokter,
                'kecamatan' => $kecamatan
            ]);
        } elseif ($suket->id_jns_surat == 4) {
            return Inertia::render('Ruang_Layanan/Umum/cetakSuratKeteranganCalonHaji', [
                'suket' => $suket,
                'dataPasien' => $dataPasien,
                'dataAnamnesa' => $dataAnamnesa,
                'unit' => $unit,
                'dokter' => $dokter,
                'kecamatan' => $kecamatan
            ]);
        } elseif ($suket->id_jns_surat == 5) {
            return Inertia::render('Ruang_Layanan/Umum/cetakSuratKematian', [
                'suket' => $suket,
                'dataPasien' => $dataPasien,
                'dataAnamnesa' => $dataAnamnesa,
                'unit' => $unit,
                'dokter' => $dokter,
                'kecamatan' => $kecamatan
            ]);

        }
    }
    public function editSuket($idPoli, $idPelayanan, $idSurat)
    {
        //dd($idSurat);
        $jenisSurat = SuratMaster::get();
        $suket = SuratKeterangan::where('id_surat', $idSurat)->first();
        $pelayanan = SimpusPelayanan::where('idPelayanan', $idPelayanan)->first();
        $dataAnamnesa = SimpusAnamnesa::with(['loket', 'loket.SimpusPasien.SetupKab', 'loket.SimpusPasien.SetupKel'])->where('loketId', $pelayanan->loketId)->first();
        $TenagaMedisAskep = MasterDokter::whereIn('profesi_id', [19, 20])->where('aktif', 1)->get();
        return Inertia::render('Ruang_Layanan/Umum/SuratKeteranganUpdate', [
            'jenisSurat' => $jenisSurat,
            'idPoli' => $idPoli,
            'idPelayanan' => $idPelayanan,
            'dataAnamnesa' => $dataAnamnesa,
            'suket' => $suket,
            'tenagaMedisAskep' => $TenagaMedisAskep
        ]);

    }

    public function updateSuket(Request $request)
    {
        //dd($request->all());

        //dd($request->all());
        if ($request->idAnamnesa) {
            SimpusAnamnesa::where('idAnamnesa', $request->idAnamnesa)->update(
                [
                    'tinggiBadan' => $request->tinggiBadan,
                    'beratBadan' => $request->beratBadan,
                    'sistole' => $request->sistole,
                    'diastole' => $request->diastole,
                    'respRate' => $request->respRate,
                    'heartRate' => $request->heartRate,
                    'suhu' => $request->suhu
                ]
            );
        }
        SuratKeterangan::where('id_surat', $request->idSurat)->update([
            'id_jns_surat' => $request->id_jns_surat,
            'no_surat' => $request->no_surat,
            'keperluan' => $request->keperluan,
            'keterangan' => $request->ket_lain,
            'tgl_ijin_awal' => $request->tgl_ijin_awal,
            'tgl_ijin_akhir' => $request->tgl_ijin_akhir,
            'tgl_kematian' => $request->tgl_kematian,
            'jam_kematian' => $request->jam_kematian,
            'ket_kematian' => $request->ket_kematian,
            'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
            'mata_ka_ki' => $request->mata_ka_ki,
            'telinga_ka_ki' => $request->telinga_ka_ki,
            'test_buta_warna' => $request->test_buta_warna,
            'tenagaMedis' => $request->tenagaMedis,
            'id_pelayanan' => $request->id_pelayanan,
            'modified_date' => now(),
        ]);
        return redirect()->back();
    }

    public function formLaborat(Request $request, $idPoli, $idLoket, $idPelayanan)
    {
        // dd($idPoli);
        $Pelayanan = SimpusPelayanan::with('SimpusLoket.anamnesa', 'SimpusPoli')->where('idPelayanan', $idPelayanan)->first();
        $Pasien = SimpusPasien::where('ID', $Pelayanan->SimpusLoket->pasienId)->first();
        $TenagaMedisAskep = MasterDokter::whereIn('profesi_id', [19, 20])->where('aktif', 1)->get();
        return Inertia::render('Ruang_Layanan/Umum/formLaborat', [
            'Pelayanan' => $Pelayanan,
            'Pasien' => $Pasien,
            'tenagaMedisAskep' => $TenagaMedisAskep
        ]);
    }

    public function simpanPermohonanLab(Request $request, $idLoket)
    {
        // dd($request->all());

        // $loket = SimpusLoket::where('');
        $namaPoli = SimpusPoliFKTP::where('kdPoli', $request->idPoli)->first();
        SimpusPermohonanLab::create([
            'idPermohonan' => Str::random(20),
            'pasienId' => $request->idPasien,
            'loketId' => $idLoket,
            'pelayananId' => $request->idPelayanan,
            'nmPoli' => $namaPoli->nmPoli,
            'tglDibuat' => now(),
            'tenagaMedisPengirim' => $request->tenaga_medis,
            'alasanDirujuk' => $request->alasan,
            'hasilLabLuarFaskes' => $request->hasil_lab_luar_faskes,
            'statusLayanan' => '0',
            'createdDate' => now(),
        ]);

        return redirect()->back();
    }

    public function suratRujukList($idPoli, $idPelayanan)
    {
        $suratRujuks = SuratRujuk::where('id_Pelayanan', $idPelayanan)->with(['tenagaMedis', 'provider', 'poliRujukan'])->get();
        // dd($suratRujuks);
        return Inertia::render('Ruang_Layanan/Umum/SuratRujukan', [
            'idPoli' => $idPoli,
            'idPelayanan' => $idPelayanan,
            'suratRujuks' => $suratRujuks
        ]);
    }
    public function suratRujukForm($idPoli, $idPelayanan, $idSurat = null)
    {
        $pelayanan = SimpusPelayanan::where('idPelayanan', $idPelayanan)->first();
        // dd($pelayanan);
        $dataAnamnesa = SimpusAnamnesa::with(['loket', 'loket.SimpusPasien.SetupKab', 'loket.SimpusPasien.SetupKel'])->where('loketId', $pelayanan->loketId)->first();
        //dd($dataAnamnesa);
        $TenagaMedisAskep = MasterDokter::whereIn('profesi_id', [19, 20])->where('aktif', 1)->get();
        $provider = SimpusProvider::select('kdProvider', 'nmProvider')->get();
        $poliFktl = SimpusPoliFKTL::get();
        if ($idSurat) {
            $surat = SuratRujuk::where('id_surat_rujukan', $idSurat)->first();
            return Inertia::render('Ruang_Layanan/Umum/SuratRujukanCreate', [
                'idPoli' => $idPoli,
                'idPelayanan' => $idPelayanan,
                'dataAnamnesa' => $dataAnamnesa,
                'tenagaMedisAskep' => $TenagaMedisAskep,
                'provider' => $provider,
                'poliFktl' => $poliFktl,
                'surat' => $surat
            ]);
        }
        return Inertia::render('Ruang_Layanan/Umum/SuratRujukanCreate', [
            'idPoli' => $idPoli,
            'idPelayanan' => $idPelayanan,
            'dataAnamnesa' => $dataAnamnesa,
            'tenagaMedisAskep' => $TenagaMedisAskep,
            'provider' => $provider,
            'poliFktl' => $poliFktl
        ]);

    }

    public function simpanSuratRujuk(Request $request, $idPoli, $idSurat = null)
    {
        //dd($idSurat);
        $unit = UnitProfile::select('nama_unit', 'alamat')->where('unit_id', 1)->first();
        if ($idSurat) {
            SuratRujuk::where('id_surat_rujukan', $idSurat)->update([
                'tgl_rujuk' => $request->tgl_rujuk,
                'no_surat' => $request->no_surat,
                'id_pelayanan' => $request->id_pelayanan,
                'no_hp' => $request->no_hp,
                'kdppk' => $request->provider,
                'kdPoliRujLan' => $request->Poli,
                'tenagaMedis' => $request->tenagaMedis,
                'nama_unit' => $unit->nama_unit,
                'alamat' => $unit->alamat,
                'created_by' => 1,
                'modified_date' => now()
            ]);

        } else {
            // dd($unit);
            SuratRujuk::create([
                'tgl_rujuk' => $request->tgl_rujuk,
                'no_surat' => $request->no_surat,
                'id_pelayanan' => $request->id_pelayanan,
                'no_hp' => $request->no_hp,
                'kdppk' => $request->provider,
                'kdPoliRujLan' => $request->Poli,
                'tenagaMedis' => $request->tenagaMedis,
                'nama_unit' => $unit->nama_unit,
                'alamat' => $unit->alamat,
                'created_by' => 1,
                'created_date' => now(),
                'modified_date' => now()
            ]);
        }

        return redirect()->back();

    }

    public function cetakRujukan($idSurat)
    {
        // dd($idSurat);
        $suratRujuk = SuratRujuk::where('id_surat_rujukan', $idSurat)->with(['tenagaMedis', 'provider', 'poliRujukan', 'pelayanan.SimpusLoket'])->first();
        // dd($suratRujuk);
        $dataPasien = DB::table('simpus_pasien as p')
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
            ->join('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')->where('p.ID', $suratRujuk->pelayanan->SimpusLoket->pasienId)->first();
        $dataAnamnesa = SimpusAnamnesa::where('loketId', $suratRujuk->pelayanan->loketId)->with(['kesadaran', 'loket.Diagnosa'])->first();
        $unit = UnitProfile::where('unit_id', 1)->first();
        // dd($dataPasien);
        return Inertia::render('Ruang_Layanan/Umum/FormCetakSuratRujukan', [
            'suratRujuk' => $suratRujuk,
            'dataPasien' => $dataPasien,
            'dataAnamnesa' => $dataAnamnesa,
            'unit' => $unit
        ]);



    }

    public function hapusSuratRujukan($idSurat)
    {
        // dd($idSurat);
        try {
            $deleted = SuratRujuk::where('id_surat_rujukan', $idSurat)->delete();
            if (!$deleted) {
                return back()->with('error', 'Surat rujukan tidak ditemukan.');
            }
            return redirect()->back()->with('success', 'Surat rujukan berhasil dihapus.');
        } catch (\Exception $err) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus surat rujukan.');
        }
    }

    public function riwayatPasien($idPoli, $idPasien)
    {
        // dd($idPoli);
        $riwayatPasien = SimpusPasien::where('ID', $idPasien)->with('SimpusLoket.anamnesa', 'SimpusLoket.Diagnosa', 'SimpusLoket.Tindakan', 'SimpusLoket.ResepObat.DetailResepObat.MasterObat')->first();
        //dd($riwayatPasien);
        return Inertia::render('Ruang_Layanan/Umum/riwayatPasien', [
            'riwayatPasien' => $riwayatPasien
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
