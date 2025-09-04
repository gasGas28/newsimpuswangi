<?php

namespace App\Http\Controllers\RuangLayanan\KunjOnline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\RuangLayanan\DataMasterUnitDetail;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon; // ← tambahkan baris ini

class KunjOnlineController extends Controller
{
    /**
     * List pasien kunjungan online
     */
    public function index()
    {
        // Dropdown Unit (biarin sama kaya di Umum, kalau ga kepake kosongin aja)
        $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
            ->where('no_kec', 18)
            ->orderBy('id_kategori')
            ->get();

        // Ambil pasien hanya untuk poli Kunjungan Online (kdPoli = 999)
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
            ->where('l.kdPoli', '999') // ⬅️ Hanya kunjungan online
            ->select(
                'l.idLoket',
                'l.tglKunjungan',
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli'
            )
            ->orderBy('l.tglKunjungan', 'desc')
            ->get();

        return Inertia::render('Ruang_Layanan/KunjunganOnline/pasien_poli', [
            'DataPasien' => $DataPasien,
            'DataUnit'   => $DataUnit,
        ]);
    }


    /**
     * Halaman detail/pelayanan pasien online
     */
public function pelayanan($id)
{
    // Dropdown Unit
    $DataUnit = DataMasterUnitDetail::with('DataMasterUnit')
        ->where('no_kec', 18)
        ->orderBy('id_kategori')
        ->get();

    // Ambil pasien Kunjungan Online (kdPoli = 999), hasilkan ARRAY (->get()) seperti "Umum"
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
        ->where('l.idLoket', $id)
        ->whereIn('l.kdPoli', [999, '999']) // khusus Kunjungan Online
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
            // umur dari loket saja (tanpa DOB)
            DB::raw('COALESCE(l.umur, 0)       as umur'),
            DB::raw('COALESCE(l.umur_bulan, 0) as umur_bulan'),
            DB::raw('COALESCE(l.umur_hari, 0)  as umur_hari'),
            'l.tglKunjungan',
            'l.idLoket'
        )
        ->get(); // <<< array seperti di "Umum"

    if ($DataPasien->isEmpty()) {
        abort(404, 'Data Kunjungan Online (kdPoli=999) untuk ID tersebut tidak ditemukan.');
    }

    // Anamnesa (object)
    $DataAnamnesa = DB::table('simpus_anamnesa')
        ->where('loketid', $id)->orWhere('loketId', $id)
        ->first();

    // Referensi lain (array)
    $DataKesadaran = DB::table('simpus_kesadaran')->get();
    $DiagnosaKasus = DB::table('master_diagnosa_kasus')->get();
    $MasterAlergi  = DB::table('master_alergi')->get();
    $DiagnosaMedis = DB::table('simpus_diagnosa')->get();

    return Inertia::render('Ruang_Layanan/KunjunganOnline/pelayanan', [
        // *** PAKAI PascalCase SAJA — cocok dengan "Umum" ***
        'DataUnit'       => $DataUnit,
        'DataPasien'     => $DataPasien,   // ARRAY (1 item) → kompatibel PelayananPasien
        'DataAnamnesa'   => $DataAnamnesa ? [$DataAnamnesa] : [],  // ✅ dibungkus array
        'DataKesadaran'  => $DataKesadaran,
        'DiagnosaKasus'  => $DiagnosaKasus,
        'MasterAlergi'   => $MasterAlergi,
        'DiagnosaMedis'  => $DiagnosaMedis,

        // JANGAN kirim versi camelCase (dataPasien, dataAnamnesa, dst) supaya tidak dobel
    ]);

}


    public function setAnamnesa(Request $request)
    {
        DB::table('simpus_anamnesa')
            ->updateOrInsert(
                ['loketId' => $request->idLoket],
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

        return redirect()->back();
    }

    public function setAnamnesaObjective(Request $request)
    {
        DB::table('simpus_anamnesa')
            ->where('idAnamnesa', $request->idAnamnesa)
            ->update([
                'keadaanUmum' => $request->keadaan_umum,
                'kdSadar'     => $request->kesadaran,
                'imt'         => $request->imt,
                'tinggiBadan' => $request->tinggi_badan,
                'beratBadan'  => $request->berat_badan,
                'suhu'        => $request->suhu,
            ]);

        return redirect()->back();
    }

    public function mulaiPemeriksaanPasien(Request $request)
    {
        DB::table('simpus_anamnesa')->insert([
            'loketId'     => $request->idLoket,
            'idAnamnesa'  => Str::uuid(),
            'tglAnamnesa' => $request->tglKunjungan,
        ]);

        return redirect()->back();
    }

    public function setDiagnosaMedis(Request $request)
    {
        dd($request->all());
    }

public function suratRujukan($id)
{
    $DataPasien = DB::table('simpus_loket as l')
        ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
        ->where('l.idLoket', $id)
        ->select('p.NO_MR','p.NAMA_LGKP','p.NIK','l.tglKunjungan','l.idLoket')
        ->first();

    // ======== PAKAI TABEL YANG ADA DI DB-MU ========
    $table = 'simpus_surat_rujukan'; // <— ganti kalau nama aslinya beda

    // fallback aman kalau tabel belum ada (biar gak 500)
    $listRujukan = Schema::hasTable($table)
        ? DB::table($table)
            ->where('id_pelayanan', $id) // id loket kamu
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



// opsional: halaman create
// still inside App\Http\Controllers\RuangLayanan\KunjOnline\KunjOnlineController

/**
 * Tampilkan form buat surat rujukan
 */
public function createSuratRujukan($id)
{
    // Ambil identitas pasien + alamat lengkap
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
            'p.AGAMA    as agama',        // <-- pakai AGAMA (uppercase)
            'p.ALAMAT   as alamat',       // <-- pakai ALAMAT
            'p.NO_RT    as no_rt',
            'p.NO_RW    as no_rw',
            'p.JENIS_PKRJN as jenis_pekerjaan_kode',  // kode pekerjaan (angka)
            DB::raw("COALESCE(kel.nama_kel,'')  as nama_kel"),
            DB::raw("COALESCE(kec.nama_kec,'') as nama_kec"),
            DB::raw("COALESCE(kab.nama_kab,'') as nama_kab"),
            DB::raw("COALESCE(prop.nama_prop,'') as nama_prop")
        )
        ->first();

    if (!$DataPasien) {
        abort(404, 'Pasien/kunjungan tidak ditemukan.');
    }

    // Dropdown (kalau tabelnya belum ada, kasih array kosong biar aman)
    $rumahSakitList = Schema::hasTable('master_unit_rujukan')
        ? DB::table('master_unit_rujukan')->select('kdppk','nama_unit')->orderBy('nama_unit')->get()
        : collect();

    $poliList = Schema::hasTable('simpus_poli_fktp')
        ? DB::table('simpus_poli_fktp')->select('kdPoli','nmPoli')->orderBy('nmPoli')->get()
        : collect();

    $dokterList = Schema::hasTable('master_tenaga_medis')
        ? DB::table('master_tenaga_medis')->select('id as idDokter','nama as namaDokter')->orderBy('nama')->get()
        : collect();

    // route buat tombol kembali & simpan (harus cocok dengan props di Vue)
    $backRoute = route('ruang-layanan.kunjungan-online.pelayanan', $id);
    $saveRoute = route('ruang-layanan.kunjungan-online.surat-rujukan.store', $id);

    return Inertia::render('Ruang_Layanan/KunjunganOnline/SuratRujukanCreate', [
        'DataPasien'      => $DataPasien,
        'backRoute'       => $backRoute,
        'saveRoute'       => $saveRoute,
        'rumahSakitList'  => $rumahSakitList,
        'poliList'        => $poliList,
        'dokterList'      => $dokterList,
    ]);
}

/**
 * Simpan surat rujukan -> ke tabel `surat_rujukan`
 */
public function storeSuratRujukan(Request $request, $id)
{
    // Validasi sesuai field form
    $validated = $request->validate([
        'tgl_rujuk'   => 'required|string',   // d-m-Y dari UI
        'no_surat'    => 'nullable|string',
        'no_hp'       => 'nullable|string|max:13',
        'kdppk'       => 'required|string|max:10', // RS tujuan
        'kdPoliRujLan'=> 'required|string|max:5',
        'tenagaMedis' => 'nullable|string|max:12',
        'nama_unit'   => 'nullable|string|max:50',
        'alamat'      => 'nullable|string|max:100',
    ]);

    // konversi tanggal d-m-Y -> Y-m-d
    try {
        $tgl = Carbon::createFromFormat('d-m-Y', $validated['tgl_rujuk'])->format('Y-m-d');
    } catch (\Exception $e) {
        $tgl = now()->toDateString();
    }

    if (!Schema::hasTable('surat_rujukan')) {
        return back()->with('error', 'Tabel surat_rujukan belum tersedia.');
    }

    DB::table('surat_rujukan')->insert([
        // id_surat_rujukan auto_increment
        'tgl_rujuk'     => $tgl,
        'no_surat'      => $validated['no_surat'] ?? null,
        'id_pelayanan'  => $id, // relasi ke idLoket
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

public function riwayatKesehatan($id)
{
    // Ambil kunjungan + pasien dari idLoket ini
    $kunj = DB::table('simpus_loket as l')
        ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
        ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
        ->where('l.idLoket', $id)
        ->select(
            'l.idLoket',
            'l.pasienId',
            'p.NO_MR',
            'p.NAMA_LGKP',
            'p.NIK',
            'p.ALAMAT as alamat',
            'p.NO_RT as no_rt',
            'p.NO_RW as no_rw',
            'p.NO_KEL', 'p.NO_KEC', 'p.NO_KAB', 'p.NO_PROP',
            DB::raw("COALESCE(poli.nmPoli,'-') as nmPoliAktif"),
            'l.tglKunjungan'
        )
        ->first();

    if (!$kunj) {
        abort(404, 'Kunjungan tidak ditemukan.');
    }

    // Ambil referensi alamat lengkap
    $alamatFull = DB::table('setup_kel as kel')
        ->leftJoin('setup_kec as kec', function ($j) {
            $j->on('kel.NO_KEC','=','kec.NO_KEC')
              ->on('kel.NO_KAB','=','kec.NO_KAB')
              ->on('kel.NO_PROP','=','kec.NO_PROP');
        })
        ->leftJoin('setup_kab as kab', function ($j) {
            $j->on('kec.NO_KAB','=','kab.NO_KAB')
              ->on('kec.NO_PROP','=','kab.NO_PROP');
        })
        ->leftJoin('setup_prop as prop', 'kab.NO_PROP', '=', 'prop.NO_PROP')
        ->where('kel.NO_KEL', $kunj->NO_KEL ?? 0)
        ->where('kel.NO_KEC', $kunj->NO_KEC ?? 0)
        ->where('kel.NO_KAB', $kunj->NO_KAB ?? 0)
        ->where('kel.NO_PROP', $kunj->NO_PROP ?? 0)
        ->select(
            DB::raw("COALESCE(kel.nama_kel,'') as nama_kel"),
            DB::raw("COALESCE(kec.nama_kec,'') as nama_kec"),
            DB::raw("COALESCE(kab.nama_kab,'') as nama_kab"),
            DB::raw("COALESCE(prop.nama_prop,'') as nama_prop"),
        )
        ->first();

    // Dapatkan semua riwayat kunjungan pasien (berdasarkan NO_MR)
    $riwayat = DB::table('simpus_loket as l')
        ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
        ->whereIn('l.pasienId', function ($q) use ($kunj) {
            $q->from('simpus_pasien')->where('ID', $kunj->pasienId)->select('ID');
        })
        ->orderBy('l.tglKunjungan', 'desc')
        ->select(
            'l.idLoket',
            'l.tglKunjungan',
            DB::raw("COALESCE(poli.nmPoli,'-') as nmPoli"),
            // Lokasi. Kalau ada tabel master puskesmas lain, silakan ganti joinnya.
            DB::raw("'PUSKESMAS WONGSOREJO' as lokasi")
        )
        ->get();

    // ====== Ambil detail per-kunjungan (anamnesa, diagnosa, lab, tindakan, obat) ======
    $mapDetail = [];

    // ANAMNESA
    if (Schema::hasTable('simpus_anamnesa')) {
        $anamAll = DB::table('simpus_anamnesa')
            ->whereIn('loketId', $riwayat->pluck('idLoket'))
            ->get()
            ->groupBy('loketId');
        foreach ($anamAll as $loketId => $rows) {
            $a = $rows->first();
            // Format ringkas mirip contoh gambar
            $txt = [];
            if (isset($a->tinggiBadan))   $txt[] = 'TB : ' . (int)$a->tinggiBadan . ' cm';
            if (isset($a->beratBadan))    $txt[] = 'BB : ' . (float)$a->beratBadan . ' kg';
            if (isset($a->lingkarPerut))  $txt[] = 'Lingkar Perut: ' . (float)$a->lingkarPerut . ' cm';
            if (isset($a->sistole))       $txt[] = 'Sistole : ' . $a->sistole;
            if (isset($a->diastole))      $txt[] = 'Diastole : ' . $a->diastole;
            if (isset($a->heartRate))     $txt[] = 'heart rate : ' . $a->heartRate;
            if (isset($a->respirateRate)) $txt[] = 'Respirate rate : ' . $a->respirateRate;
            if (isset($a->keluhan))       $txt[] = 'keluhan :' . $a->keluhan;
            if (isset($a->keluhanTambahan)) $txt[] = 'keluhan tambahan: ' . $a->keluhanTambahan;

            $mapDetail[$loketId]['anamnesa'] = implode(', ', $txt);
        }
    }

    // DIAGNOSA
// DIAGNOSA (kolom relasi bisa beda-beda antar tabel)
if (Schema::hasTable('simpus_diagnosa')) {

    // pilih nama kolom yang ada di tabel
    $diagRelCol = Schema::hasColumn('simpus_diagnosa', 'loketId') ? 'loketId'
                : (Schema::hasColumn('simpus_diagnosa', 'loketid') ? 'loketid'
                : (Schema::hasColumn('simpus_diagnosa', 'idLoket') ? 'idLoket' : null));

    if ($diagRelCol) {
        // kolom kode & nama diagnosa (fallback bila beda nama)
        $diagKodeCol = Schema::hasColumn('simpus_diagnosa','kdDiag') ? 'kdDiag'
                    : (Schema::hasColumn('simpus_diagnosa','kode_icd') ? 'kode_icd'
                    : (Schema::hasColumn('simpus_diagnosa','kode') ? 'kode' : DB::raw("''")));

        $diagNamaCol = Schema::hasColumn('simpus_diagnosa','diagnosa') ? 'diagnosa'
                    : (Schema::hasColumn('simpus_diagnosa','diagnosa_text') ? 'diagnosa_text'
                    : (Schema::hasColumn('simpus_diagnosa','diagnosaNama') ? 'diagnosaNama' : DB::raw("''")));

        $diagAll = DB::table('simpus_diagnosa')
            ->whereIn($diagRelCol, $riwayat->pluck('idLoket'))
            ->select(
                DB::raw("$diagRelCol as loketKey"),
                DB::raw("GROUP_CONCAT(CONCAT(COALESCE($diagKodeCol,''),' ',COALESCE($diagNamaCol,'')) SEPARATOR '; ') as teks")
            )
            ->groupBy('loketKey')
            ->get();

        foreach ($diagAll as $d) {
            $mapDetail[$d->loketKey]['diagnosa'] = $d->teks;
        }
    }
}


    // LAB (opsional, pakai nama tabel yang ada)
    if (Schema::hasTable('simpus_lab_hasil')) {
        $labAll = DB::table('simpus_lab_hasil')
            ->whereIn('loketId', $riwayat->pluck('idLoket'))
            ->select('loketId',
                DB::raw("GROUP_CONCAT(CONCAT(COALESCE(nama_pemeriksaan,''),': ',COALESCE(hasil,'')) SEPARATOR '; ') as teks")
            )
            ->groupBy('loketId')
            ->get();
        foreach ($labAll as $l2) {
            $mapDetail[$l2->loketId]['lab'] = $l2->teks;
        }
    }

    // TINDAKAN (opsional)
// TINDAKAN (pakai kolom yang benar di tabelmu)
if (Schema::hasTable('simpus_tindakan')) {
    $relCol = Schema::hasColumn('simpus_tindakan', 'loketId') ? 'loketId'
            : (Schema::hasColumn('simpus_tindakan', 'idPelayanan') ? 'idPelayanan' : null);

    if ($relCol) {
        // urutan prioritas nama tindakan: nmTindakan -> nmTindakanInd -> deskripsi
        $namaCol = Schema::hasColumn('simpus_tindakan', 'nmTindakan') ? 'nmTindakan'
                 : (Schema::hasColumn('simpus_tindakan', 'nmTindakanInd') ? 'nmTindakanInd'
                 : (Schema::hasColumn('simpus_tindakan', 'deskripsi') ? 'deskripsi' : null));

        if ($namaCol) {
            $tindAll = DB::table('simpus_tindakan')
                ->whereIn($relCol, $riwayat->pluck('idLoket'))
                ->select(
                    DB::raw("$relCol as loketKey"),
                    DB::raw("GROUP_CONCAT(COALESCE($namaCol,'') SEPARATOR '; ') as teks")
                )
                ->groupBy('loketKey')
                ->get();

            foreach ($tindAll as $t) {
                $mapDetail[$t->loketKey]['tindakan'] = $t->teks;
            }
        }
    }
}


    // OBAT / RESEP (opsional)
if (Schema::hasTable('simpus_resep_detail') && Schema::hasTable('simpus_resep_obat')) {
    $detailTbl = 'simpus_resep_detail';
    $headerTbl = 'simpus_resep_obat';

    // cari key join detail -> header
    $detailKey = Schema::hasColumn($detailTbl, 'resep_id') ? 'resep_id'
               : (Schema::hasColumn($detailTbl, 'id_resep') ? 'id_resep' : null);

    $headerKey = Schema::hasColumn($headerTbl, 'id_resep') ? 'id_resep'
               : (Schema::hasColumn($headerTbl, 'resep_id') ? 'resep_id' : null);

    // cari kolom loket di header
    $headerLoketCol = Schema::hasColumn($headerTbl, 'loketId') ? 'loketId'
                    : (Schema::hasColumn($headerTbl, 'idPelayanan') ? 'idPelayanan'
                    : (Schema::hasColumn($headerTbl, 'loketid') ? 'loketid' : null));

    if ($detailKey && $headerKey && $headerLoketCol) {
        // coba join master obat (opsional)
        $obatTbl = null; $obatIdCol = null; $obatNameCol = null;
        foreach (['farmasi_obat','master_obat','simpus_obat','apotek_obat','m_obat'] as $t) {
            if (!Schema::hasTable($t)) continue;
            foreach (['id_obat','kode_obat','kd_obat','id','kode'] as $idc) {
                if (!Schema::hasColumn($t, $idc)) continue;
                foreach (['nama_obat','nm_obat','nama','namaBarang','nama_obat_ind'] as $nc) {
                    if (Schema::hasColumn($t, $nc)) {
                        $obatTbl = $t; $obatIdCol = $idc; $obatNameCol = $nc; break 2;
                    }
                }
            }
        }

        // kolom relasi ke master dari detail
        $detailObatKey = null;
        foreach (['obat_id','id_obat','kd_obat','kode_obat'] as $c) {
            if (Schema::hasColumn($detailTbl, $c)) { $detailObatKey = $c; break; }
        }

        // dosis & jumlah
        $doseCol   = Schema::hasColumn($detailTbl,'dosis_pakai') ? 'dosis_pakai'
                   : (Schema::hasColumn($detailTbl,'dosis') ? 'dosis' : null);
        $jumlahExp = Schema::hasColumn($detailTbl,'jumlah')
                   ? "CASE WHEN d.jumlah IS NULL OR d.jumlah=0 THEN '' ELSE CONCAT(' x', d.jumlah) END"
                   : "''";

        $qb = DB::table("$detailTbl as d")
            ->join("$headerTbl as h", "d.$detailKey", '=', "h.$headerKey")
            ->whereIn("h.$headerLoketCol", $riwayat->pluck('idLoket'));

        if ($obatTbl && $detailObatKey) {
            $qb->leftJoin("$obatTbl as o", "o.$obatIdCol", '=', "d.$detailObatKey");
        }

        $nameExpr = ($obatTbl && $obatNameCol) ? "COALESCE(o.$obatNameCol, d.keterangan)" : "COALESCE(d.keterangan, CONCAT('OBT:', d.$detailObatKey))";
        $doseExpr = $doseCol ? "COALESCE(d.$doseCol,'')" : "''";

        $rowsObat = $qb->select(
                DB::raw("h.$headerLoketCol as loketKey"),
                DB::raw("GROUP_CONCAT(TRIM(CONCAT($nameExpr, ' ', $doseExpr, ' ', $jumlahExp)) SEPARATOR '; ') as teks")
            )
            ->groupBy('loketKey')
            ->get();

        foreach ($rowsObat as $o) {
            $mapDetail[$o->loketKey]['obat'] = $o->teks;
        }
    }
}


    // Susun array final untuk tabel
    $rows = [];
    $no = 1;
    foreach ($riwayat as $r) {
        $det = $mapDetail[$r->idLoket] ?? [];
        $rows[] = [
            'no'          => $no++,
            'tgl'         => Carbon::parse($r->tglKunjungan)->format('d-m-Y'),
            'lokasi'      => $r->lokasi,
            'status'      => 'Poli : ' . $r->nmPoli,
            'anamnesa'    => $det['anamnesa'] ?? '',
            'lab'         => $det['lab'] ?? '',
            'diagnosa'    => $det['diagnosa'] ?? '',
            'tindakan'    => $det['tindakan'] ?? '',
            'obat'        => $det['obat'] ?? '',
        ];
    }

    // Data header pasien untuk cetakan
    $DataPasien = [
        'no_mr'      => $kunj->NO_MR,
        'nama'       => $kunj->NAMA_LGKP,
        'nik'        => $kunj->NIK,
        'alamat'     => trim(($kunj->alamat ?? '').' RT '.$kunj->no_rt.' / RW '.$kunj->no_rw),
        'kel'        => $alamatFull->nama_kel ?? '',
        'kec'        => $alamatFull->nama_kec ?? '',
        'kab'        => $alamatFull->nama_kab ?? '',
        'prop'       => $alamatFull->nama_prop ?? '',
        'tgl_reg'    => Carbon::parse($kunj->tglKunjungan)->format('d-m-Y'),
    ];

    return Inertia::render('Ruang_Layanan/KunjunganOnline/riwayat_kesehatan', [
        'DataPasien' => $DataPasien,
        'Riwayat'    => $rows,
        'backRoute'  => route('ruang-layanan.kunjungan-online.pelayanan', $id),
    ]);
}

public function cppt($id)
{
   
    // === HEADER PASIEN DARI idLoket ===
    $kunj = DB::table('simpus_loket as l')
        ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
        ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
        ->where('l.idLoket', $id)
        ->select(
            'l.idLoket',
            'l.pasienId',
            'l.kdPoli',
            'l.tglKunjungan',
            'p.NO_MR',
            'p.NAMA_LGKP',
            'p.TGL_LHR',
            'p.ALAMAT as alamat',
            'p.NO_RT as no_rt',
            'p.NO_RW as no_rw',
            'p.NIK',
            DB::raw("COALESCE(poli.nmPoli,'-') as nmPoliAktif")
        )
        ->first();

    if (!$kunj) abort(404, 'Kunjungan tidak ditemukan.');

    // alamat referensi (opsional)
    $addr = DB::table('simpus_pasien as p')
        ->leftJoin('setup_kel as kel', function ($j) {
            $j->on('p.NO_KEL','=','kel.NO_KEL')->on('p.NO_KEC','=','kel.NO_KEC')->on('p.NO_KAB','=','kel.NO_KAB')->on('p.NO_PROP','=','kel.NO_PROP');
        })
        ->leftJoin('setup_kec as kec', function ($j) {
            $j->on('p.NO_KEC','=','kec.NO_KEC')->on('p.NO_KAB','=','kec.NO_KAB')->on('p.NO_PROP','=','kec.NO_PROP');
        })
        ->leftJoin('setup_kab as kab', function ($j) {
            $j->on('p.NO_KAB','=','kab.NO_KAB')->on('p.NO_PROP','=','kab.NO_PROP');
        })
        ->leftJoin('setup_prop as prop', 'p.NO_PROP','=','prop.NO_PROP')
        ->where('p.ID', $kunj->pasienId)
        ->select(
            DB::raw("COALESCE(kel.nama_kel,'') as kel"),
            DB::raw("COALESCE(kec.nama_kec,'') as kec"),
            DB::raw("COALESCE(kab.nama_kab,'') as kab"),
            DB::raw("COALESCE(prop.nama_prop,'') as prop")
        )->first();

    // === SEMUA KUNJUNGAN PASIEN INI (untuk list CPPT) ===
    $kunjList = DB::table('simpus_loket as l')
        ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
        ->where('l.pasienId', $kunj->pasienId)
        ->orderBy('l.tglKunjungan', 'desc')
        ->select(
            'l.idLoket','l.tglKunjungan',
            DB::raw("COALESCE(poli.nmPoli,'-') as nmPoli")
        )
        ->get();

    // === KUMPULKAN DETAIL PER idLoket (S, O, A, P, Petugas) ===
    $map = [];

    // S + O (simpus_anamnesa)
    if (Schema::hasTable('simpus_anamnesa')) {
        $anam = DB::table('simpus_anamnesa')
            ->whereIn('loketId', $kunjList->pluck('idLoket'))
            ->get()->keyBy('loketId');

        foreach ($anam as $loketId => $a) {
            // S (Subjective)
            $S = [];
            if (!empty($a->keluhan))          $S[] = "keluhan :".$a->keluhan;
            if (!empty($a->keluhanTambahan))  $S[] = "keluhan tambahan: ".$a->keluhanTambahan;

            // O (Objective)
            $O = [];
            if (isset($a->tinggiBadan))   $O[] = "TB : ".(int)$a->tinggiBadan." cm";
            if (isset($a->beratBadan))    $O[] = "BB : ".(float)$a->beratBadan." kg";
            if (isset($a->lingkarPerut))  $O[] = "Lingkar Perut: ".(float)$a->lingkarPerut." cm";
            if (isset($a->sistole))       $O[] = "Sistole : ".$a->sistole;
            if (isset($a->diastole))      $O[] = "Diastole : ".$a->diastole;
            if (isset($a->heartRate))     $O[] = "heart rate : ".$a->heartRate;
            if (isset($a->respirateRate)) $O[] = "Respirate rate : ".$a->respirateRate;

            $map[$loketId]['S'] = implode(', ', $S);
            $map[$loketId]['O'] = implode(', ', $O);
        }
    }

    // A (Assessment) = Diagnosa + Lab
    if (Schema::hasTable('simpus_diagnosa')) {
        $rel = Schema::hasColumn('simpus_diagnosa','loketId') ? 'loketId' :
               (Schema::hasColumn('simpus_diagnosa','loketid') ? 'loketid' :
               (Schema::hasColumn('simpus_diagnosa','idLoket') ? 'idLoket' : null));
        if ($rel) {
            $kode = Schema::hasColumn('simpus_diagnosa','kdDiag') ? 'kdDiag' :
                    (Schema::hasColumn('simpus_diagnosa','kode_icd') ? 'kode_icd' :
                    (Schema::hasColumn('simpus_diagnosa','kode') ? 'kode' : DB::raw("''")));
            $nama = Schema::hasColumn('simpus_diagnosa','diagnosa') ? 'diagnosa' :
                    (Schema::hasColumn('simpus_diagnosa','diagnosa_text') ? 'diagnosa_text' :
                    (Schema::hasColumn('simpus_diagnosa','diagnosaNama') ? 'diagnosaNama' : DB::raw("''")));

            $dAll = DB::table('simpus_diagnosa')
                ->whereIn($rel, $kunjList->pluck('idLoket'))
                ->select(
                    DB::raw("$rel as k"),
                    DB::raw("GROUP_CONCAT(CONCAT(COALESCE($kode,''),' ',COALESCE($nama,'')) SEPARATOR '; ') as t")
                )->groupBy('k')->get();

            foreach ($dAll as $d) $map[$d->k]['A_diag'] = $d->t;
        }
    }
    if (Schema::hasTable('simpus_lab_hasil')) {
        $lAll = DB::table('simpus_lab_hasil')
            ->whereIn('loketId', $kunjList->pluck('idLoket'))
            ->select('loketId',
                DB::raw("GROUP_CONCAT(CONCAT(COALESCE(nama_pemeriksaan,''),': ',COALESCE(hasil,'')) SEPARATOR '; ') as t")
            )->groupBy('loketId')->get();
        foreach ($lAll as $l) $map[$l->loketId]['A_lab'] = $l->t;
    }

    // P (Planning) = Tindakan + Pengobatan (resep)
    if (Schema::hasTable('simpus_tindakan')) {
        $rel = Schema::hasColumn('simpus_tindakan','loketId') ? 'loketId' :
               (Schema::hasColumn('simpus_tindakan','idPelayanan') ? 'idPelayanan' : null);
        $nama = Schema::hasColumn('simpus_tindakan','nmTindakan') ? 'nmTindakan' :
                (Schema::hasColumn('simpus_tindakan','nmTindakanInd') ? 'nmTindakanInd' :
                (Schema::hasColumn('simpus_tindakan','deskripsi') ? 'deskripsi' : null));
        if ($rel && $nama) {
            $tAll = DB::table('simpus_tindakan')
                ->whereIn($rel, $kunjList->pluck('idLoket'))
                ->select(DB::raw("$rel as k"), DB::raw("GROUP_CONCAT(COALESCE($nama,'') SEPARATOR '; ') as t"))
                ->groupBy('k')->get();
            foreach ($tAll as $t) $map[$t->k]['P_tindakan'] = $t->t;
        }
    }
    if (Schema::hasTable('simpus_resep_detail') && Schema::hasTable('simpus_resep_obat')) {
        $o = DB::table('simpus_resep_detail as d')
            ->join('simpus_resep_obat as h','d.resep_id','=','h.id_resep')
            ->whereIn('h.loketId', $kunjList->pluck('idLoket'))
            ->select(
                DB::raw("h.loketId as k"),
                DB::raw("
                    REPLACE(
                        GROUP_CONCAT(
                            TRIM(CONCAT(
                                COALESCE(d.keterangan, CONCAT('OBT:', d.obat_id)),
                                ' ',
                                COALESCE(d.dosis_pakai,''),
                                CASE WHEN d.jumlah IS NULL OR d.jumlah=0 THEN '' ELSE CONCAT(' x', d.jumlah) END
                            )) SEPARATOR '; '
                        ), '  ', ' '
                    ) as t
                ")
            )
            ->groupBy('k')->get();
        foreach ($o as $r) $map[$r->k]['P_obat'] = $r->t;
    }

    // Petugas (ambil dari loket.createdBy atau kdDokter kalau ada)
    $petugas = DB::table('simpus_loket')
        ->whereIn('idLoket', $kunjList->pluck('idLoket'))
        ->select('idLoket','kdDokter','createdBy')->get()->keyBy('idLoket');

    // === Susun baris CPPT ===
    $rows = [];
    foreach ($kunjList as $k) {
        $d = $map[$k->idLoket] ?? [];
        $pet = $petugas[$k->idLoket] ?? null;

        $tanggal = '';
        try { $tanggal = Carbon::parse($k->tglKunjungan)->format('d-m-Y'); } catch (\Throwable $e) { $tanggal = (string)$k->tglKunjungan; }

        $rows[] = [
            'tgl'   => $tanggal,
            'unit'  => "*Unit*\nPUSKESMAS WONGSOREJO\n*Ruang layanan*\nPoli : ".$k->nmPoli,
            'S'     => $d['S'] ?? '',
            'O'     => $d['O'] ?? '',
            'A'     => "*Assesment*\n".($d['A_diag'] ?? '')."\n*Laborat*\n".($d['A_lab'] ?? ''),
            'P'     => "*Tindakan*\n".($d['P_tindakan'] ?? '')."\n\n*Pengobatan*\n".($d['P_obat'] ?? ''),
            'petugas' => ($pet?->kdDokter ?: $pet?->createdBy ?: ''),
        ];
    }

    // header pasien untuk area atas
    $DataPasien = [
        'no_rm'   => $kunj->NO_MR,
        'nama'    => $kunj->NAMA_LGKP,
        'tgl_lhr' => $kunj->TGL_LHR,
        'nik'     => $kunj->NIK,
        'alamat'  => trim(($kunj->alamat ?? '').' , Desa '.($addr->kel ?? '').' , Kec. '.($addr->kec ?? '')),
        'kab'     => $addr->kab ?? '', 'prop' => $addr->prop ?? '',
    ];

    return Inertia::render('Ruang_Layanan/KunjunganOnline/cppt', [
        'DataPasien' => $DataPasien,
        'Items'      => $rows,
        'backRoute'  => route('ruang-layanan.kunjungan-online.pelayanan', $id),
    ]);
}



}