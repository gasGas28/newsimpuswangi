<?php

namespace App\Http\Controllers\RuangLayanan\KunjOnline;

use App\Http\Controllers\Controller;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use App\Models\RuangLayanan\MasterDokter;
use App\Models\RuangLayanan\SimpusAlergiData;
use App\Models\RuangLayanan\SimpusAnamnesa;
use App\Models\RuangLayanan\SimpusDataDiagnosa;
use App\Models\RuangLayanan\SimpusDetailResepObat;
use App\Models\RuangLayanan\SimpusDiagnosa;
use App\Models\RuangLayanan\SimpusLoket;
use App\Models\RuangLayanan\SimpusMasterObat;
use App\Models\RuangLayanan\SimpusPelayanan;
use App\Models\RuangLayanan\SimpusResepObat;
use App\Models\RuangLayanan\SimpusTindakan;
use App\Models\RuangLayanan\StatusPulang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Inertia;

class KunjOnlineController extends Controller
{
    /**
     * kdPoli khusus untuk Kunjungan Online
     */
    private const KDPOLI_ONLINE = 999;

    /** =========================
     * LIST PASIEN (index)
     * ========================= */
    public function index()
    {
        $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
            ->where('no_kec', 18)
            ->orderBy('id_kategori')
            ->get();

        // Catatan: di DB kamu ada dua gaya penamaan (UPPERCASE & lowercase).
        // Di-select keduanya + alias umum agar front-end fleksibel.
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('setup_kel as kel', function ($j) {
                $j->on('p.NO_KEL', '=', 'kel.NO_KEL')
                  ->on('p.NO_KEC', '=', 'kel.NO_KEC')
                  ->on('p.NO_KAB', '=', 'kel.NO_KAB')
                  ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })
            ->leftJoin('setup_kec as kec', function ($j) {
                $j->on('p.NO_KEC', '=', 'kec.NO_KEC')
                  ->on('p.NO_KAB', '=', 'kec.NO_KAB')
                  ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })
            ->leftJoin('setup_kab as kab', function ($j) {
                $j->on('p.NO_KAB', '=', 'kab.NO_KAB')
                  ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })
            ->leftJoin('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
            ->whereIn('l.kdPoli', [self::KDPOLI_ONLINE, (string) self::KDPOLI_ONLINE])
            ->orderByDesc('l.tglKunjungan')
            ->select([
                'l.idLoket',
                'l.tglKunjungan',
                DB::raw('p.NO_MR as NO_MR'),
                DB::raw('p.NO_MR as no_mr'),
                DB::raw('p.NO_MR as no_rm'),
                DB::raw('p.NAMA_LGKP as NAMA_LGKP'),
                DB::raw('p.NAMA_LGKP as nama_lgkp'),
                DB::raw('p.NAMA_LGKP as nama'),
                DB::raw('p.NIK as NIK'),
                DB::raw('p.NIK as nik'),
                DB::raw('p.ALAMAT as ALAMAT'),
                DB::raw('p.ALAMAT as alamat'),
                DB::raw('p.NO_RT as NO_RT'),
                DB::raw('p.NO_RT as no_rt'),
                DB::raw('p.NO_RT as rt'),
                DB::raw('p.NO_RW as NO_RW'),
                DB::raw('p.NO_RW as no_rw'),
                DB::raw('p.NO_RW as rw'),
                DB::raw('p.JENIS_KLMIN as JENIS_KLMIN'),
                DB::raw('p.JENIS_KLMIN as jenis_klmin'),
                DB::raw('COALESCE(l.umur,0) as umur'),
                DB::raw('COALESCE(l.umur,0) as umur_tahun'),
                DB::raw('COALESCE(l.umur_bulan,0) as umur_bulan'),
                DB::raw('COALESCE(l.umur_hari,0) as umur_hari'),
                DB::raw("COALESCE(kel.NAMA_KEL,'') as NAMA_KEL"),
                DB::raw("COALESCE(kel.NAMA_KEL,'') as nama_kel"),
                DB::raw("COALESCE(kel.NAMA_KEL,'') as kelurahan"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as NAMA_KEC"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as nama_kec"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as kecamatan"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as NAMA_KAB"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as nama_kab"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as kabupaten"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as NAMA_PROP"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as nama_prop"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as provinsi"),
                DB::raw("COALESCE(poli.nmPoli,'Kunjungan Online') as nmPoli"),
            ])
            ->get();

        return Inertia::render('Ruang_Layanan/KunjunganOnline/pasien_poli', [
            'DataUnit'   => $DataUnit,
            'DataPasien' => $DataPasien,
        ]);
    }

    /** =========================
     * HALAMAN PELAYANAN (detail)
     * ========================= */
    public function pelayanan($id)
    {
        $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
            ->where('no_kec', 18)
            ->orderBy('id_kategori')
            ->get();

        $row = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('setup_kel as kel', function ($j) {
                $j->on('p.NO_KEL', '=', 'kel.NO_KEL')
                  ->on('p.NO_KEC', '=', 'kel.NO_KEC')
                  ->on('p.NO_KAB', '=', 'kel.NO_KAB')
                  ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })
            ->leftJoin('setup_kec as kec', function ($j) {
                $j->on('p.NO_KEC', '=', 'kec.NO_KEC')
                  ->on('p.NO_KAB', '=', 'kec.NO_KAB')
                  ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })
            ->leftJoin('setup_kab as kab', function ($j) {
                $j->on('p.NO_KAB', '=', 'kab.NO_KAB')
                  ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })
            ->leftJoin('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
            ->where('l.idLoket', $id)
            ->whereIn('l.kdPoli', [self::KDPOLI_ONLINE, (string) self::KDPOLI_ONLINE])
            ->select([
                'l.idLoket',
                'l.tglKunjungan',
                DB::raw('l.tglKunjungan as tgl_kunjungan'),
                DB::raw('p.ID as pasienId'),

                DB::raw('p.NO_MR as NO_MR'),
                DB::raw('p.NO_MR as no_mr'),
                DB::raw('p.NO_MR as no_rm'),

                DB::raw('p.NAMA_LGKP as NAMA_LGKP'),
                DB::raw('p.NAMA_LGKP as nama_lgkp'),
                DB::raw('p.NAMA_LGKP as nama'),

                DB::raw('p.NIK as NIK'),
                DB::raw('p.NIK as nik'),

                DB::raw('p.ALAMAT as ALAMAT'),
                DB::raw('p.ALAMAT as alamat'),
                DB::raw('p.NO_RT as NO_RT'),
                DB::raw('p.NO_RT as no_rt'),
                DB::raw('p.NO_RT as rt'),
                DB::raw('p.NO_RW as NO_RW'),
                DB::raw('p.NO_RW as no_rw'),
                DB::raw('p.NO_RW as rw'),

                DB::raw('p.JENIS_KLMIN as JENIS_KLMIN'),
                DB::raw('p.JENIS_KLMIN as jenis_klmin'),
                DB::raw("CASE p.JENIS_KLMIN WHEN 1 THEN 'L' WHEN 2 THEN 'P' ELSE '-' END as jk"),

                DB::raw('COALESCE(l.umur,0) as umur'),
                DB::raw('COALESCE(l.umur,0) as umur_tahun'),
                DB::raw('COALESCE(l.umur_bulan,0) as umur_bulan'),
                DB::raw('COALESCE(l.umur_hari,0) as umur_hari'),

                DB::raw("COALESCE(kel.NAMA_KEL,'') as NAMA_KEL"),
                DB::raw("COALESCE(kel.NAMA_KEL,'') as nama_kel"),
                DB::raw("COALESCE(kel.NAMA_KEL,'') as kelurahan"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as NAMA_KEC"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as nama_kec"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as kecamatan"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as NAMA_KAB"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as nama_kab"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as kabupaten"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as NAMA_PROP"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as nama_prop"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as provinsi"),

                DB::raw("COALESCE(poli.nmPoli,'Kunjungan Online') as nmPoli"),
                DB::raw('COALESCE(l.noKartu, p.noKartu) as noKartu'),
                DB::raw('COALESCE(l.noKartu, p.noKartu) as no_bpjs'),
                DB::raw('COALESCE(l.kdProvider, p.kdProvider) as kdProvider'),
                DB::raw('COALESCE(l.kdProvider, p.kdProvider) as provider'),
                'l.kdPoli',
            ])
            ->first();

        if (!$row) {
            abort(404, 'Data Kunjungan Online (kdPoli=999) untuk ID tersebut tidak ditemukan.');
        }

        // Anamnesa (array agar aman untuk komponen)
        $DataAnamnesa = SimpusAnamnesa::where('loketId', $id)->first();
        $DataKesadaran = DB::table('simpus_kesadaran')->get();
        $DiagnosaKasus = DB::table('master_diagnosa_kasus')->get();
        $MasterAlergi  = DB::table('master_alergi')->get();

        // Alergi pasien (sesuai gaya umum: with relation)
        $AlergiPasien = SimpusAlergiData::with(['alergiObat', 'alergiMakanan'])
            ->where('pasienId', $row->pasienId ?? null)
            ->get();

        return Inertia::render('Ruang_Layanan/KunjunganOnline/pelayanan', [
            'DataUnit'       => $DataUnit,
            'DataPasien'     => [$row],                               // array 1 item
            'DataAnamnesa'   => $DataAnamnesa , // array/empty
            'DataKesadaran'  => $DataKesadaran,
            'DiagnosaKasus'  => $DiagnosaKasus,
            'MasterAlergi'   => $MasterAlergi,
            'AlergiPasien'   => $AlergiPasien,                         // ← dipakai Subjective
            // 'DiagnosaMedis' bisa ditambahkan kalau page butuh
        ]);
    }

    /** =========================
     * SIMPAN SUBJECTIVE
     * ========================= */
    public function setAnamnesa(Request $request)
    {
        // Simpan / update anamnesa (berdasarkan loketId) — gaya mirip Umum
        SimpusAnamnesa::updateOrCreate(
            ['loketid' => $request->idLoket],
            [
                'tglAnamnesa'               => $request->tgl_anamnesa,
                'keluhan'                   => $request->keluhan_utama,
                'keluhanTambahan'           => $request->keluhan_tambahan,
                'riwayatPenyakitSekarang'   => $request->riwayat_penyakit_sekarang,
                'riwayatPenyakitDahulu'     => $request->riwayat_penyakit_dahulu,
                'riwayatPenyakitKeluarga'   => $request->riwayat_penyakit_keluarga,
                'terapiYangPernahDijalani'  => $request->tindakan,
                'obatSeringDigunakan'       => $request->obat_digunakan,
                'obatSeringDikonsumsi'      => $request->obat_dikonsumsi,
            ]
        );

        // Simpan alergi pasien (mirip Umum)
        if ($request->filled('idPasien')) {
            $AlergiPasien = SimpusAlergiData::where('pasienId', $request->idPasien)->first();
            if (!$AlergiPasien) {
                SimpusAlergiData::create([
                    'idAlergi'         => Str::random(20),
                    'pasienId'         => $request->idPasien,
                    'kodeAlergiObat'   => $request->alergi_obat,
                    'kodeAlergiMakanan'=> $request->alergi_makanan,
                    'keterangan'       => $request->keterangan_alergi,
                ]);
            } else {
                $AlergiPasien->update([
                    'kodeAlergiObat'    => $request->alergi_obat,
                    'kodeAlergiMakanan' => $request->alergi_makanan,
                    'keterangan'        => $request->keterangan_alergi,
                ]);
            }
        }

        return redirect()->back();
    }

    /** =========================
     * SIMPAN OBJECTIVE
     * ========================= */
    public function setAnamnesaObjective(Request $request)
    {
        // Ambil record dulu agar update tidak mengosongkan data lama
        $anamnesa = DB::table('simpus_anamnesa')
            ->where('idAnamnesa', $request->idAnamnesa)
            ->first();

        if (!$anamnesa) {
            return redirect()->back()->with('error', 'Anamnesa tidak ditemukan.');
        }

        $dataUpdate = [
            'keadaanUmum'    => $request->keadaan_umum    ?? $anamnesa->keadaanUmum,
            'kdSadar'        => $request->kesadaran       ?? $anamnesa->kdSadar,
            'imt'            => $request->imt             ?? $anamnesa->imt,
            'tinggiBadan'    => $request->tinggi_badan    ?? $anamnesa->tinggiBadan,
            'beratBadan'     => $request->berat_badan     ?? $anamnesa->beratBadan,
            'lingkarPerut'   => $request->lingkar_perut   ?? ($anamnesa->lingkarPerut ?? null),
            'lingkarTangan'  => $request->lingkar_lengan  ?? ($anamnesa->lingkarTangan ?? null),
            'sistole'        => $request->sistole         ?? $anamnesa->sistole,
            'diastole'       => $request->diastole        ?? $anamnesa->diastole,
            'respRate'       => $request->resp_rate       ?? ($anamnesa->respRate ?? null),
            'heartRate'      => $request->heart_rate      ?? ($anamnesa->heartRate ?? null),
            'suhu'           => $request->suhu            ?? $anamnesa->suhu,

            'thoraxJantung'    => $request->jantung      ?? ($anamnesa->thoraxJantung ?? null),
            'thoraxJantungKet' => $request->ket_jantung  ?? ($anamnesa->thoraxJantungKet ?? null),

            'thoraxPulmo'      => $request->pulmo        ?? ($anamnesa->thoraxPulmo ?? null),
            'thoraxPulmoKet'   => $request->ket_pulmo    ?? ($anamnesa->thoraxPulmoKet ?? null),

            'abdomanAtas'      => $request->abdomen_atas ?? ($anamnesa->abdomanAtas ?? null),
            'abdomanAtasKet'   => $request->ket_abdomen_atas ?? ($anamnesa->abdomanAtasKet ?? null),

            'abdomanBawah'     => $request->abdomen_bawah ?? ($anamnesa->abdomanBawah ?? null),
            'abdomanBawahKet'  => $request->ket_abdomen_bawah ?? ($anamnesa->abdomanBawahket ?? null),

            'extrimitasAtas'     => $request->extrimitas_atas ?? ($anamnesa->extrimitasAtas ?? null),
            'extrimitasAtasKet'  => $request->ket_extrimitas_atas ?? ($anamnesa->extrimitasAtasKet ?? null),

            'extrimitasBawah'    => $request->extrimitas_bawah ?? ($anamnesa->extrimitasBawah ?? null),
            'extrimitasBawahKet' => $request->ket_extrimitas_bawah ?? ($anamnesa->extrimitasBawahKet ?? null),

            'kepala'          => $request->kepala       ?? ($anamnesa->kepala ?? null),
            'kepalaKet'       => $request->ket_kepala   ?? ($anamnesa->kepalaKet ?? null),

            'mata'            => $request->mata         ?? ($anamnesa->mata ?? null),
            'mataKet'         => $request->ket_mata     ?? ($anamnesa->mataKet ?? null),

            'telinga'         => $request->telinga      ?? ($anamnesa->telinga ?? null),
            'telingaKet'      => $request->ket_telinga  ?? ($anamnesa->telingaKet ?? null),

            'leher'           => $request->leher        ?? ($anamnesa->leher ?? null),
            'leherKet'        => $request->ket_leher    ?? ($anamnesa->leherKet ?? null),

            'tenagaMedisAskep'=> $request->tenaga_medis_askep ?? ($anamnesa->tenagaMedisAskep ?? null),
        ];

        DB::table('simpus_anamnesa')
            ->where('idAnamnesa', $request->idAnamnesa)
            ->update($dataUpdate);

        return redirect()->back();
    }

    /** =========================
     * MULAI PEMERIKSAAN
     * ========================= */
    public function mulaiPemeriksaanPasien(Request $request)
    {
        DB::table('simpus_anamnesa')->insert([
            'loketId'     => $request->idLoket,
            'idAnamnesa'  => Str::uuid(),
            'tglAnamnesa' => $request->tglKunjungan,
        ]);

        // Jika ikut membawa idPelayanan (opsional), kita update progress juga (mirip di Umum)
        if ($request->filled('idPelayanan')) {
            SimpusPelayanan::where('idpelayanan', $request->idPelayanan)
                ->update([
                    'sudahDilayani' => '2',
                    'progressTime'  => now()->format('Y-m-d H:i:s'),
                ]);
        }

        return redirect()->back();
    }

    /** =========================
     * DIAGNOSA MEDIS (placeholder)
     * ========================= */
    public function setDiagnosaMedis(Request $request)
    {
        // contoh implementasi (sesuaikan kebutuhan Kunjungan Online):
        // SimpusDataDiagnosa::create([...]);
        return redirect()->back();
    }

    /** =========================
     * SURAT RUJUKAN (opsional)
     * ========================= */
    public function suratRujukan($id)
    {
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->where('l.idLoket', $id)
            ->select('p.NO_MR','p.NAMA_LGKP','p.NIK','l.tglKunjungan','l.idLoket')
            ->first();

        $table = 'surat_rujukan';

        $listRujukan = Schema::hasTable($table)
            ? DB::table($table)
                ->where('id_pelayanan', $id)
                ->select(
                    'id_surat_rujukan as idRujukan',
                    'tgl_rujuk       as tanggal',
                    'no_surat        as noSurat',
                    'nama_unit       as rumahSakit',
                    'kdPoliRujLan    as poli',
                    'tenagaMedis',
                    'kdppk',
                    'alamat',
                    'no_hp'
                )
                ->orderByDesc('tgl_rujuk')
                ->get()
            : collect();

        return Inertia::render('Ruang_Layanan/KunjunganOnline/SuratRujukan', [
            'DataPasien'  => $DataPasien,
            'listRujukan' => $listRujukan,
            'backRoute'   => route('ruang-layanan.kunjungan-online.pelayanan', $id),
            'createRoute' => route('ruang-layanan.kunjungan-online.surat-rujukan.create', $id),
        ]);
    }

    public function createSuratRujukan($id)
    {
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->leftJoin('setup_kel as kel', function ($j) {
                $j->on('p.NO_KEL','=','kel.NO_KEL')
                  ->on('p.NO_KEC','=','kel.NO_KEC')
                  ->on('p.NO_KAB','=','kel.NO_KAB')
                  ->on('p.NO_PROP','=','kel.NO_PROP');
            })
            ->leftJoin('setup_kec as kec', function ($j) {
                $j->on('p.NO_KEC','=','kec.NO_KEC')
                  ->on('p.NO_KAB','=','kec.NO_KAB')
                  ->on('p.NO_PROP','=','kec.NO_PROP');
            })
            ->leftJoin('setup_kab as kab', function ($j) {
                $j->on('p.NO_KAB','=','kab.NO_KAB')
                  ->on('p.NO_PROP','=','kab.NO_PROP');
            })
            ->leftJoin('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')
            ->where('l.idLoket', $id)
            ->select(
                'l.idLoket',
                'l.tglKunjungan',
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'p.TMPT_LHR as tempat_lahir',
                'p.TGL_LHR  as tgl_lahir',
                'p.AGAMA    as agama',
                'p.ALAMAT   as alamat',
                'p.NO_RT    as no_rt',
                'p.NO_RW    as no_rw',
                'p.JENIS_PKRJN as jenis_pekerjaan_kode',
                DB::raw("COALESCE(kel.NAMA_KEL,'')  as nama_kel"),
                DB::raw("COALESCE(kec.NAMA_KEC,'') as nama_kec"),
                DB::raw("COALESCE(kab.NAMA_KAB,'') as nama_kab"),
                DB::raw("COALESCE(prop.NAMA_PROP,'') as nama_prop")
            )
            ->first();

        if (!$DataPasien) abort(404, 'Pasien/kunjungan tidak ditemukan.');

        $rumahSakitList = Schema::hasTable('master_unit_rujukan')
            ? DB::table('master_unit_rujukan')->select('kdppk','nama_unit')->orderBy('nama_unit')->get()
            : collect();

        $poliList = Schema::hasTable('simpus_poli_fktp')
            ? DB::table('simpus_poli_fktp')->select('kdPoli','nmPoli')->orderBy('nmPoli')->get()
            : collect();

        $dokterList = Schema::hasTable('master_tenaga_medis')
            ? DB::table('master_tenaga_medis')->select('id as idDokter','nama as namaDokter')->orderBy('nama')->get()
            : collect();

        return Inertia::render('Ruang_Layanan/KunjunganOnline/SuratRujukanCreate', [
            'DataPasien'      => $DataPasien,
            'backRoute'       => route('ruang-layanan.kunjungan-online.pelayanan', $id),
            'saveRoute'       => route('ruang-layanan.kunjungan-online.surat-rujukan.store', $id),
            'rumahSakitList'  => $rumahSakitList,
            'poliList'        => $poliList,
            'dokterList'      => $dokterList,
        ]);
    }

    public function storeSuratRujukan(Request $request, $id)
    {
        $validated = $request->validate([
            'tgl_rujuk'   => 'required|string',
            'no_surat'    => 'nullable|string',
            'no_hp'       => 'nullable|string|max:13',
            'kdppk'       => 'required|string|max:10',
            'kdPoliRujLan'=> 'required|string|max:5',
            'tenagaMedis' => 'nullable|string|max:12',
            'nama_unit'   => 'nullable|string|max:50',
            'alamat'      => 'nullable|string|max:100',
        ]);

        try {
            $tgl = Carbon::createFromFormat('d-m-Y', $validated['tgl_rujuk'])->format('Y-m-d');
        } catch (\Throwable $e) {
            $tgl = now()->toDateString();
        }

        if (!Schema::hasTable('surat_rujukan')) {
            return back()->with('error', 'Tabel surat_rujukan belum tersedia.');
        }

        DB::table('surat_rujukan')->insert([
            'tgl_rujuk'     => $tgl,
            'no_surat'      => $validated['no_surat'] ?? null,
            'id_pelayanan'  => $id,
            'no_hp'         => $validated['no_hp'] ?? null,
            'kdppk'         => $validated['kdppk'],
            'kdPoliRujLan'  => $validated['kdPoliRujLan'],
            'tenagaMedis'   => $validated['tenagaMedis'] ?? null,
            'nama_unit'     => $validated['nama_unit'] ?? null,
            'alamat'        => $validated['alamat'] ?? null,
            'created_date'  => now(),
            'modified_date' => now(),
        ]);

        return redirect()->route('ruang-layanan.kunjungan-online.pelayanan', $id)
            ->with('success', 'Surat rujukan berhasil disimpan.');
    }

    /* =========================
     * UTIL: COALESCE SELECTOR
     * ========================= */
    protected function coalesce(string $table, array $candidates, string $fallback)
    {
        // Bikin COALESCE(t.candidate1, t.candidate2, ..., t.fallback) as fallback
        $cols = array_map(fn ($c) => "$table.$c", $candidates);
        $cols[] = "$table.$fallback";
        $expr = 'COALESCE('.implode(',', $cols).')';
        return DB::raw("$expr as $fallback");
    }
}
