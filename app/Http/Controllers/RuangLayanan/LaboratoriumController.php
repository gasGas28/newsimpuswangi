<?php

namespace App\Http\Controllers\RuangLayanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Carbon\Carbon;

class LaboratoriumController extends Controller
{
    /** =========================
     *  Konfigurasi & Utilities
     *  ========================= */
    private string $paramTable = 'parameter_uji'; // ganti ke 'loniccode.parameter_uji' jika beda DB

    /** Normalisasi string (kode, id, dsb.) */
    private function norm(string $s): string
    {
        return trim($s);
    }

    /**
     * Normalisasi / resolve loketId:
     * - Jika ada persis di simpus_loket / simpus_permohonan_lab → pakai apa adanya.
     * - Jika tidak ada, ambil digit dari param (mis. "LKT01" → "01"), pad-left 3 → "001",
     *   lalu bentuk "LOK001". Jika ada di loket/permohonan → pakai itu.
     * - Kalau tetap tidak ketemu, kembalikan param awal.
     */
    private function resolveLoketId(string $raw): string
    {
        $in = trim($raw);

        $existsLoket = DB::table('simpus_loket')->whereRaw('TRIM(idLoket)=?', [$in])->exists();
        if ($existsLoket) return $in;

        $existsPerm = DB::table('simpus_permohonan_lab')->whereRaw('TRIM(loketId)=?', [$in])->exists();
        if ($existsPerm) return $in;

        if (preg_match('/(\d+)/', strtoupper($in), $m)) {
            $digits = str_pad($m[1], 3, '0', STR_PAD_LEFT);
            $canon  = 'LOK' . $digits;

            $ok = DB::table('simpus_loket')->whereRaw('TRIM(idLoket)=?', [$canon])->exists()
               || DB::table('simpus_permohonan_lab')->whereRaw('TRIM(loketId)=?', [$canon])->exists();

            if ($ok) return $canon;
        }

        return $in;
    }

    /** Ambil nama poli dari loket (opsional helper) */
    private function getPoliNameByLoket(string $loketId): ?string
    {
        return DB::table('simpus_loket as l')
            ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->whereRaw('TRIM(l.idLoket)=?', [$loketId])
            ->value('poli.nmPoli');
    }

    /** =========================
     *  INDEX
     *  ========================= */
    public function index(Request $request)
    {
        // DataUnit
        $DataUnit = DB::table('data_master_unit_detail as d')
            ->leftJoin('data_master_unit as u', 'u.id_kategori', '=', 'd.id_kategori')
            ->leftJoin('setup_kec as kec', function ($j) {
                $j->on('kec.NO_KEC', '=', 'd.no_kec')
                  ->on('kec.NO_KAB', '=', DB::raw('18'))
                  ->on('kec.NO_PROP', '=', DB::raw('35'));
            })
            ->where('d.no_kec', 18)
            ->where('u.kategori', 'PUSKESMAS')
            ->select(
                'd.no_kec',
                'd.id_kategori',
                DB::raw('COALESCE(u.kategori, d.nama_unit) as kategori'),
                DB::raw('COALESCE(d.nama_unit, u.kategori) as nama_unit'),
                'kec.nama_kec'
            )
            ->groupBy('d.no_kec', 'd.id_kategori', 'kategori', 'nama_unit', 'kec.nama_kec')
            ->orderBy('kec.nama_kec')
            ->orderBy('nama_unit')
            ->get();

        // Dropdown filter
        $FilterKategori = DB::table('data_master_unit')
            ->select('id_kategori', 'kategori')
            ->orderBy('kategori')
            ->get();

        $FilterUnits = DB::table('data_master_unit_detail as d')
            ->join('data_master_unit as k', 'k.id_kategori', '=', 'd.id_kategori')
            ->join('unit_profiles as up', 'up.unit_id', '=', 'd.id_unit')
            ->select('k.id_kategori','k.kategori','up.unit_id','up.nama_unit')
            ->distinct()
            ->orderBy('k.kategori')
            ->orderBy('up.nama_unit')
            ->get();

        // join ke unit_profiles jika ada pasangan kolomnya
        $loketCols = Schema::getColumnListing('simpus_loket');
        $upCols    = Schema::getColumnListing('unit_profiles');

        $candidates = [
            ['loket' => 'unit_id',       'up' => 'unit_id'],
            ['loket' => 'puskesmas_id',  'up' => 'kode_puskesmas'],
            ['loket' => 'PUSK_ID',       'up' => 'kode_puskesmas'],
            ['loket' => 'kode_unit',     'up' => 'unit_id'],
            ['loket' => 'kode_puskesmas','up' => 'kode_puskesmas'],
        ];

        $loketKey = null; $upKey = null;
        foreach ($candidates as $pair) {
            if (in_array($pair['loket'], $loketCols) && in_array($pair['up'], $upCols)) {
                $loketKey = $pair['loket'];
                $upKey    = $pair['up'];
                break;
            }
        }

        $q = DB::table('simpus_permohonan_lab as lo')
            ->leftJoin('simpus_loket as l', 'l.idLoket', '=', 'lo.loketId')
            ->leftJoin('simpus_pasien as p', 'p.ID', '=', 'l.pasienId')
            ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli');

        if ($loketKey && $upKey) {
            $q->leftJoin('unit_profiles as up', "up.$upKey", '=', "l.$loketKey")
              ->leftJoin('data_master_unit_detail as d2', 'd2.id_unit', '=', 'up.unit_id')
              ->leftJoin('data_master_unit as k', 'k.id_kategori', '=', 'd2.id_kategori')
              ->addSelect(
                  DB::raw('up.unit_id as unit_id'),
                  DB::raw('up.nama_unit as nama_unit'),
                  DB::raw('k.id_kategori as id_kategori'),
                  DB::raw('k.kategori as kategori')
              );
        } else {
            $q->addSelect(
                DB::raw('NULL as unit_id'),
                DB::raw('NULL as nama_unit'),
                DB::raw('NULL as id_kategori'),
                DB::raw('NULL as kategori')
            );
        }

        $Rows = $q->addSelect(
                DB::raw('COALESCE(l.idLoket, lo.loketId) as idLoket'),
                DB::raw('COALESCE(p.NO_MR, lo.pasienId) as NO_MR'),
                DB::raw('COALESCE(p.NAMA_LGKP, "") as NAMA_LGKP'),
                DB::raw('COALESCE(p.ALAMAT, "") as alamat'),
                DB::raw('COALESCE(p.NO_RT, "") as no_rt'),
                DB::raw('COALESCE(p.NO_RW, "") as no_rw'),
                DB::raw('COALESCE(poli.nmPoli, lo.nmPoli) as nmPoli'),
                DB::raw('COALESCE(l.tglKunjungan, lo.tglDibuat) as tglKunjungan'),
                DB::raw("COALESCE(lo.alasanDirujuk, '-') as alasan"),
                DB::raw("CASE 
                            WHEN lo.statusLayanan = '1' THEN 'Selesai'
                            WHEN lo.statusLayanan = '2' THEN 'Proses'
                            ELSE 'Belum dilayani' 
                         END as status")
            )
            ->orderByDesc(DB::raw('COALESCE(l.tglKunjungan, lo.tglDibuat)'))
            ->limit(200)
            ->get();

        $Debug = [
            'rows'       => $Rows->count(),
            'sample'     => $Rows->take(3),
            'perm_lab'   => (int) DB::table('simpus_permohonan_lab')->count(),
            'join_used'  => ['loket_key' => $loketKey, 'unit_profiles_key' => $upKey],
        ];

        return Inertia::render('Ruang_Layanan/Laborat/index', [
            'DataUnit'        => $DataUnit,
            'DataPasien'      => $Rows,
            'FilterKategori'  => $FilterKategori,
            'FilterUnits'     => $FilterUnits,
            'Debug'           => $Debug,
        ]);
    }

    /** =========================
     *  PEMERIKSAAN (DETAIL)
     *  ========================= */
    public function pemeriksaan($loketId)
    {
        $paramLoketId    = trim($loketId);
        $resolvedLoketId = $this->resolveLoketId($paramLoketId);

        // 1) Identitas: LOKET → PASIEN → POLI
        $DataPasien = DB::table('simpus_loket as l')
            ->leftJoin('simpus_pasien as p', 'p.ID', '=', 'l.pasienId')
            ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('setup_kel as kel', function ($join) {
                $join->on('kel.NO_KEL', '=', 'p.NO_KEL')
                     ->on('kel.NO_KEC', '=', 'p.NO_KEC')
                     ->on('kel.NO_KAB', '=', 'p.NO_KAB')
                     ->on('kel.NO_PROP', '=', 'p.NO_PROP');
            })
            ->leftJoin('setup_kec as kec', function ($join) {
                $join->on('kec.NO_KEC', '=', 'p.NO_KEC')
                     ->on('kec.NO_KAB', '=', 'p.NO_KAB')
                     ->on('kec.NO_PROP', '=', 'p.NO_PROP');
            })
            ->leftJoin('setup_kab as kab', function ($join) {
                $join->on('kab.NO_KAB', '=', 'p.NO_KAB')
                     ->on('kab.NO_PROP', '=', 'p.NO_PROP');
            })
            ->leftJoin('setup_prop as prop', 'prop.NO_PROP', '=', 'p.NO_PROP')
            ->whereRaw('TRIM(l.idLoket) = ?', [$resolvedLoketId])
            ->select(
                'l.idLoket',
                'l.tglKunjungan',
                'l.umur', 'l.umur_bulan', 'l.umur_hari',
                DB::raw('p.NO_MR as NO_MR'),
                DB::raw('p.NAMA_LGKP as NAMA_LGKP'),
                DB::raw('p.NIK as NIK'),
                DB::raw('p.JENIS_KLMIN as jenis_klmin'),
                DB::raw('p.ALAMAT as alamat'),
                DB::raw('p.NO_RT as no_rt'),
                DB::raw('p.NO_RW as no_rw'),
                DB::raw('kel.nama_kel as nama_kel'),
                DB::raw('kec.nama_kec as nama_kec'),
                DB::raw('kab.nama_kab as nama_kab'),
                DB::raw('prop.nama_prop as nama_prop'),
                'poli.nmPoli',
                DB::raw("CASE 
                    WHEN p.TGL_LHR IN ('0000-00-00','0000-00-00 00:00:00','') THEN NULL
                    ELSE p.TGL_LHR 
                END as TGL_LHR")
            )
            ->first();

        // 2) PERMOHONAN TERBARU
        $DataPermohonan = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(loketId) = ?', [$resolvedLoketId])
            ->orderByDesc('tglDibuat')
            ->select(
                DB::raw('idPermohonan as order_id'),
                DB::raw('idPermohonan as idPermohonan'),
                'loketId',
                DB::raw('pasienId as pasien_id'),
                DB::raw('tglDibuat as tgl_dibuat'),
                DB::raw('tenagaMedisPengirim as tenaga_medis_perujuk'),
                DB::raw('tenagaMedisPenerima as tenaga_medis_pemeriksa'),
                DB::raw('nmPoli as poli'),
                DB::raw('alasanDirujuk as alasan'),
                DB::raw('hasilLabLuarFaskes as hasil_lab_luar_faskes'),
                DB::raw("CASE 
                            WHEN statusLayanan = '1' THEN 'Selesai'
                            WHEN statusLayanan = '2' THEN 'Proses'
                            ELSE 'Belum dilayani'
                         END as status"),
                DB::raw('statusLayanan as status_layanan'),
                DB::raw('sampleDiambil as sample_diambil_at'),
                DB::raw('sampleSelesai as sample_selesai_at')
            )
            ->first();

        // 3) Fallback identitas dari permohonan (kalau LOKET tidak ada)
        if (!$DataPasien && $DataPermohonan && $DataPermohonan->pasien_id) {
            $p = DB::table('simpus_pasien as p')
                ->leftJoin('setup_kel as kel', function ($join) {
                    $join->on('kel.NO_KEL', '=', 'p.NO_KEL')
                         ->on('kel.NO_KEC', '=', 'p.NO_KEC')
                         ->on('kel.NO_KAB', '=', 'p.NO_KAB')
                         ->on('kel.NO_PROP', '=', 'p.NO_PROP');
                })
                ->leftJoin('setup_kec as kec', function ($join) {
                    $join->on('kec.NO_KEC', '=', 'p.NO_KEC')
                         ->on('kec.NO_KAB', '=', 'p.NO_KAB')
                         ->on('kec.NO_PROP', '=', 'p.NO_PROP');
                })
                ->leftJoin('setup_kab as kab', function ($join) {
                    $join->on('kab.NO_KAB', '=', 'p.NO_KAB')
                         ->on('kab.NO_PROP', '=', 'p.NO_PROP');
                })
                ->leftJoin('setup_prop as prop', 'prop.NO_PROP', '=', 'p.NO_PROP')
                ->where('p.ID', $DataPermohonan->pasien_id)
                ->select(
                    DB::raw("'{$resolvedLoketId}' as idLoket"),
                    DB::raw('NULL as umur'),
                    DB::raw('NULL as umur_bulan'),
                    DB::raw('NULL as umur_hari'),
                    DB::raw('p.NO_MR as NO_MR'),
                    DB::raw('p.NAMA_LGKP as NAMA_LGKP'),
                    DB::raw('p.NIK as NIK'),
                    DB::raw('p.JENIS_KLMIN as jenis_klmin'),
                    DB::raw('p.ALAMAT as alamat'),
                    DB::raw('p.NO_RT as no_rt'),
                    DB::raw('p.NO_RW as no_rw'),
                    DB::raw('kel.nama_kel as nama_kel'),
                    DB::raw('kec.nama_kec as nama_kec'),
                    DB::raw('kab.nama_kab as nama_kab'),
                    DB::raw('prop.nama_prop as nama_prop'),
                    DB::raw('NULL as nmPoli'),
                    DB::raw('CAST(NULL AS DATE) as tglKunjungan')
                )
                ->first();

            if ($p) {
                $p->tglKunjungan = $DataPermohonan->tgl_dibuat
                    ? Carbon::parse($DataPermohonan->tgl_dibuat)->toDateString()
                    : null;
            }
            $DataPasien = $p;
        }

        // 4) OBJECTIVE: ambil by loketId, fallback by pasien terdekat waktu
        $DataObjective = DB::table('simpus_anamnesa as a')
            ->whereRaw('TRIM(a.loketId) = ?', [$resolvedLoketId])
            ->orderByDesc('a.createdDate')
            ->select(
                'a.keadaanUmum','a.kdSadar','a.tinggiBadan','a.beratBadan',
                'a.lingkarPerut','a.lingkarTangan',
                'a.sistole','a.diastole','a.respRate','a.heartRate','a.suhu',
                'a.createdDate','a.loketId'
            )
            ->first();

        if (!$DataObjective) {
            $pasienId = DB::table('simpus_loket')->whereRaw('TRIM(idLoket)=?', [$resolvedLoketId])->value('pasienId');
            if (!$pasienId && $DataPermohonan) {
                $pasienId = $DataPermohonan->pasien_id ?: DB::table('simpus_loket')
                    ->whereRaw('TRIM(idLoket)=?', [$DataPermohonan->loketId])
                    ->value('pasienId');
            }

            if ($pasienId) {
                $ref    = $DataPasien?->tglKunjungan ?: $DataPermohonan?->tgl_dibuat;
                $target = $ref ? Carbon::parse($ref)->endOfDay() : Carbon::now();

                $DataObjective = DB::table('simpus_anamnesa as a')
                    ->join('simpus_loket as l2', 'l2.idLoket', '=', 'a.loketId')
                    ->where('l2.pasienId', $pasienId)
                    ->select(
                        'a.keadaanUmum','a.kdSadar','a.tinggiBadan','a.beratBadan',
                        'a.lingkarPerut','a.lingkarTangan',
                        'a.sistole','a.diastole','a.respRate','a.heartRate','a.suhu',
                        'a.createdDate','a.loketId'
                    )
                    ->orderByRaw('CASE WHEN a.loketId = ? THEN 0 ELSE 1 END', [$resolvedLoketId])
                    ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, a.createdDate, ?)) ASC', [$target])
                    ->orderByDesc('a.createdDate')
                    ->first();
            }
        }

        // 5) DETAIL PEMERIKSAAN (simpus_tindakan) — gabungkan m.kode & parameter_uji
        $DataPemeriksaan = collect();

        if ($DataPermohonan && $DataPermohonan->order_id) {
            // subquery parameter_uji: dedupe per kode
            $puSub = DB::table($this->paramTable . ' as pu0')
                ->selectRaw("
                    TRIM(pu0.kode_parameter)               AS kode,
                    MAX(pu0.satuan)                         AS satuan,
                    MAX(NULLIF(pu0.nilai_normal,''))        AS nilai_normal,
                    MAX(NULLIF(pu0.nilai_kritis,''))        AS nilai_kritis
                ")
                ->whereNotNull('pu0.kode_parameter')
                ->groupBy(DB::raw('TRIM(pu0.kode_parameter)'));

            $DataPemeriksaan = DB::table('simpus_tindakan as t')
                ->leftJoin('simpus_master_pemeriksaan_lab as m', function ($j) {
                    $j->on(DB::raw('TRIM(m.kode)'), '=', DB::raw('TRIM(t.kdTindakan)'));
                })
                ->leftJoinSub($puSub, 'pu', function ($j) {
                    $j->on('pu.kode', '=', DB::raw('TRIM(t.kdTindakan)'));
                })
                ->whereRaw('TRIM(t.loketId) = ?', [$this->norm($resolvedLoketId)])
                ->whereRaw('TRIM(t.permohonanId) = ?', [$this->norm($DataPermohonan->order_id)])
                ->selectRaw("
                    DISTINCT
                    t.idTindakan,
                    t.idTindakan                                        AS detail_id,
                    t.kdTindakan                                        AS kode,
                    COALESCE(NULLIF(t.nmTindakanInd,''), t.nmTindakan)  AS nama_pemeriksaan,
                    t.nilaiLab                                          AS nilai_lab,

                    COALESCE(m.satuan, pu.satuan)                       AS satuan,
                    COALESCE(NULLIF(m.nilaiNormal,''), pu.nilai_normal) AS nilai_normal,
                    COALESCE(NULLIF(m.nilaiKritis,''), pu.nilai_kritis) AS nilai_kritis,
                    CONCAT(
                        'Nilai Normal : ', COALESCE(NULLIF(m.nilaiNormal,''), pu.nilai_normal, '-'),
                        '\nNilai Kritis : ', COALESCE(NULLIF(m.nilaiKritis,''), pu.nilai_kritis, '-')
                    ) AS nilai_normal_kritis
                ")
                ->orderBy('t.idTindakan')
                ->get();
        }

        $TenagaMedis = DB::table('master_dokter')
            ->select('kdDokter as id', 'nmDokter as nama', DB::raw('NULL as profesi'))
            ->orderBy('nmDokter')
            ->get();

        return Inertia::render('Ruang_Layanan/Laborat/pemeriksaan', [
            'DataPasien'      => $DataPasien,
            'DataPermohonan'  => $DataPermohonan,
            'DataPemeriksaan' => $DataPemeriksaan,
            'DataObjective'   => $DataObjective,
            'TenagaMedis'     => $TenagaMedis,
        ]);
    }

    /** =========================
     *  HEADER PERMOHONAN: Waktu & Status
     *  ========================= */
    public function setWaktuSample(Request $request)
    {
        $validated = $request->validate([
            'order_id'               => 'required|string',
            'sample_diambil_at'      => 'nullable|date',
            'sample_selesai_at'      => 'nullable|date',
            'tenaga_medis_pemeriksa' => 'nullable|string|max:255',
        ]);

        $status = '0';
        if (!empty($validated['sample_diambil_at'])) $status = '2';
        if (!empty($validated['sample_selesai_at'])) $status = '1';

        DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [trim($validated['order_id'])])
            ->update([
                'sampleDiambil'       => $validated['sample_diambil_at'] ?? null,
                'sampleSelesai'       => $validated['sample_selesai_at'] ?? null,
                'tenagaMedisPenerima' => $validated['tenaga_medis_pemeriksa'] ?? null,
                'statusLayanan'       => $status,
                'modifiedDate'        => now(),
            ]);

        return back();
    }

    /** =========================
     *  TINDAKAN: Hapus Semua / Hapus Satu / Update Nilai
     *  ========================= */
    public function hapusSemuaTindakan(Request $req)
    {
        $validated = $req->validate([
            'order_id' => ['required','string'],
        ]);

        $orderId = trim($validated['order_id']);

        $loketId = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [$orderId])
            ->value('loketId');

        if (!$loketId) {
            return back()->withErrors(['hapus' => 'Permohonan tidak ditemukan / loketId kosong.']);
        }

        DB::beginTransaction();
        try {
            $deleted = DB::table('simpus_tindakan')
                ->whereRaw('TRIM(permohonanId)=?', [$orderId])
                ->whereRaw('TRIM(loketId)=?', [$loketId])
                ->delete();

            DB::commit();
            return back()->with('ok', "hapus-semua-berhasil ($deleted item)");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['hapus' => $e->getMessage()]);
        }
    }

    public function hapusTindakan(Request $request)
    {
        $validated = $request->validate([
            'order_id'  => 'required|string',
            'detail_id' => 'required|integer',
        ]);

        DB::table('simpus_tindakan')
            ->where('idTindakan', $validated['detail_id'])
            ->whereRaw('TRIM(permohonanId)=?', [trim($validated['order_id'])])
            ->delete();

        return back();
    }

    public function updateNilaiLab(Request $request)
    {
        $validated = $request->validate([
            'order_id'          => 'required|string',
            'hasil'             => 'required|array',
            'hasil.*.detail_id' => 'required|integer',
            'hasil.*.nilai'     => 'nullable|string',
        ]);

        $permId = trim($validated['order_id']);

        foreach ($validated['hasil'] as $row) {
            DB::table('simpus_tindakan')
                ->where('idTindakan', $row['detail_id'])
                ->whereRaw('TRIM(permohonanId)=?', [$permId])
                ->update(['nilaiLab' => $row['nilai']]);
        }

        return back();
    }

    /** =========================
     *  HEADER PERMOHONAN: Simpan
     *  ========================= */
    public function simpanPermohonan(Request $request)
    {
        $validated = $request->validate([
            'idLoket'             => 'required|string',
            'idPasien'            => 'required',
            'idPelayanan'         => 'nullable',
            'hasilLabLuarFaskes'  => 'nullable|in:,0,1',
            'kdPoli'              => 'nullable|string',
            'tenagaMedisPengirim' => 'nullable|string|max:255',
            'alasanDirujuk'       => 'nullable|string',
            'tglDibuat'           => 'nullable|string',
        ]);

        $loketId   = $this->resolveLoketId($validated['idLoket']);
        $nmPoli    = $this->getPoliNameByLoket($loketId) ?? null;
        $tglDibuat = $validated['tglDibuat'] ?: now();

        $insert = [
            'loketId'             => $loketId,
            'pasienId'            => $validated['idPasien'],
            'tglDibuat'           => $tglDibuat,
            'tenagaMedisPengirim' => $validated['tenagaMedisPengirim'] ?? null,
            'tenagaMedisPenerima' => null,
            'nmPoli'              => $nmPoli,
            'alasanDirujuk'       => $validated['alasanDirujuk'] ?? null,
            'hasilLabLuarFaskes'  => ($validated['hasilLabLuarFaskes'] === '1') ? 1 : 0,
            'statusLayanan'       => '0',
            'createdDate'         => now(),
            'modifiedDate'        => now(),
        ];

        if (Schema::hasColumn('simpus_permohonan_lab', 'idPelayanan') && !empty($validated['idPelayanan'])) {
            $insert['idPelayanan'] = $validated['idPelayanan'];
        }
        if (Schema::hasColumn('simpus_permohonan_lab', 'kdPoli') && !empty($validated['kdPoli'])) {
            $insert['kdPoli'] = $validated['kdPoli'];
        }

        DB::table('simpus_permohonan_lab')->insert($insert);

        return back();
    }

    /** =========================
     *  Helper Insert Batch (master pemeriksaan lama)
     *  ========================= */
    private function insertTindakanBatch(string $loketId, string $permohonanId, $pelayananId, array $masters, array $nilaiMap = []): int
    {
        $now            = now();
        $cols           = Schema::getColumnListing('simpus_tindakan');
        $hasIdPelayanan = in_array('idPelayanan', $cols);
        $hasCreatedDate = in_array('createdDate', $cols);
        $hasModifiedDate= in_array('modifiedDate', $cols);

        $insertRows = [];

        foreach ($masters as $m) {
            $kode = $m->kode;

            $exists = DB::table('simpus_tindakan')
                ->whereRaw('TRIM(loketId)=?', [$loketId])
                ->whereRaw('TRIM(permohonanId)=?', [trim($permohonanId)])
                ->where('kdTindakan', $kode)
                ->exists();

            if ($exists) continue;

            $row = [
                'loketId'       => $loketId,
                'permohonanId'  => $permohonanId,
                'kdTindakan'    => $kode,
                'nmTindakan'    => $m->nmPemeriksaan,
                'nmTindakanInd' => $m->nmPemeriksaanInd,
                'nilaiLab'      => $nilaiMap[$m->idPemeriksaan] ?? null,
            ];

            if ($hasIdPelayanan && !empty($pelayananId)) $row['idPelayanan'] = $pelayananId;
            if ($hasCreatedDate)  $row['createdDate']   = $now;
            if ($hasModifiedDate) $row['modifiedDate']  = $now;

            $insertRows[] = $row;
        }

        if (count($insertRows)) {
            DB::table('simpus_tindakan')->insert($insertRows);
        }

        return count($insertRows);
    }

    /** =========================
     *  Tambah Detail dari master_pemeriksaan (lama)
     *  ========================= */
    public function pemeriksaanSimpan(Request $request)
    {
        $validated = $request->validate([
            'loketId'       => 'required|string',
            'permohonanId'  => 'required|string',
            'pelayananId'   => 'nullable',
            'pilih'         => 'required|array|min:1',
            'pilih.*'       => 'integer',
            'nilaiLab'      => 'nullable|array',
        ]);

        $loketId   = $this->resolveLoketId($validated['loketId']);
        $orderId   = trim($validated['permohonanId']);
        $nilaiMap  = $validated['nilaiLab'] ?? [];
        $pelayanan = $validated['pelayananId'] ?? null;

        $exists = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [$orderId])
            ->exists();

        if (!$exists) {
            return back()->withErrors(['permohonanId' => 'Permohonan ID tidak valid.']);
        }

        $masters = DB::table('simpus_master_pemeriksaan_lab')
            ->whereIn('idPemeriksaan', $validated['pilih'])
            ->select('idPemeriksaan','kode','nmPemeriksaan','nmPemeriksaanInd')
            ->orderBy('idPemeriksaan')
            ->get();

        DB::transaction(function () use ($loketId, $orderId, $pelayanan, $masters, $nilaiMap) {
            $this->insertTindakanBatch($loketId, $orderId, $pelayanan, $masters->all(), $nilaiMap);
        });

        return back();
    }

    /** =========================
     *  Tambah Detail dari master paket (lama)
     *  ========================= */
    public function paketPemeriksaanSimpan(Request $request, string $paket)
    {
        $validated = $request->validate([
            'loketId'      => 'required|string',
            'permohonanId' => 'required|string',
            'pelayananId'  => 'nullable',
        ]);

        $mapCol = [
            'paketCatin'        => 'paketCatin',
            'paketAnck1'        => 'paketAnck1',
            'paketAnck3'        => 'paketAnck3',
            'paketPtm'          => 'paketPtm',
            'paketDarahLengkap' => 'paketDarahLengkap',
            'paketWidal'        => 'paketWidal',
        ];
        if (!isset($mapCol[$paket])) {
            return back()->withErrors(['paket' => 'Paket tidak dikenal.']);
        }

        $loketId   = $this->resolveLoketId($validated['loketId']);
        $orderId   = trim($validated['permohonanId']);
        $pelayanan = $validated['pelayananId'] ?? null;

        $exists = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [$orderId])
            ->exists();

        if (!$exists) {
            return back()->withErrors(['permohonanId' => 'Permohonan ID tidak valid.']);
        }

        $masters = DB::table('simpus_master_pemeriksaan_lab')
            ->where($mapCol[$paket], 1)
            ->select('idPemeriksaan','kode','nmPemeriksaan','nmPemeriksaanInd')
            ->orderBy('headerId')
            ->orderBy('subheaderId')
            ->orderByRaw('COALESCE(noUrut, 9999)')
            ->get();

        DB::transaction(function () use ($loketId, $orderId, $pelayanan, $masters) {
            $this->insertTindakanBatch($loketId, $orderId, $pelayanan, $masters->all(), []);
        });

        return back();
    }

    /** =========================
     *  DETAIL sederhana untuk Print (JSON)
     *  ========================= */
    public function detail($idPermohonan)
    {
        $order = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [trim($idPermohonan)])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Permohonan tidak ditemukan'], 404);
        }

        $rows = DB::table('simpus_tindakan as t')
            ->leftJoin('simpus_master_pemeriksaan_lab as m', 'm.kode', '=', 't.kdTindakan')
            ->whereRaw('TRIM(t.permohonanId)=?', [trim($idPermohonan)])
            ->selectRaw("
                t.idTindakan as detail_id,
                t.kdTindakan as kode,
                COALESCE(NULLIF(t.nmTindakanInd,''), t.nmTindakan) as nama_pemeriksaan,
                t.nilaiLab as nilai_lab,
                m.satuan as satuan,
                CONCAT(
                    'Nilai Normal : ', COALESCE(m.nilaiNormal,'-'),
                    '\nNilai Kritis : ', COALESCE(m.nilaiKritis,'-')
                ) as nilai_normal_kritis
            ")
            ->orderBy('t.idTindakan')
            ->get();

        return response()->json([
            'permohonan'  => $order,
            'pemeriksaan' => $rows,
        ]);
    }

    /** =========================
     *  MASTER (lama): Header & Subheader
     *  ========================= */
    public function masterHeaders(Request $request)
    {
        $rows = DB::table('simpus_master_header_lab as h')
            ->leftJoin('simpus_master_pemeriksaan_lab as m', 'm.headerId', '=', 'h.idHeader')
            ->select(
                'h.idHeader',
                'h.nmHeader',
                DB::raw('COUNT(m.idPemeriksaan) as jumlah_item')
            )
            ->groupBy('h.idHeader', 'h.nmHeader')
            ->orderBy('h.nmHeader')
            ->get();

        return response()->json($rows);
    }

    public function masterSubheaders(Request $request, int $headerId)
    {
        $rows = DB::table('simpus_master_subheader_lab as s')
            ->where('s.headerId', $headerId)
            ->orderBy('s.nmSubheader')
            ->get(['s.idSubheader', 's.headerId', 's.nmSubheader']);

        return response()->json($rows);
    }

    public function paginasiMasterPemeriksaan(Request $request)
    {
        $search        = trim((string) $request->query('search', ''));
        $headerId      = $request->query('header_id');
        $subheaderId   = $request->query('subheader_id');
        $headerName    = trim((string) $request->query('header_name', ''));
        $subheaderName = trim((string) $request->query('subheader_name', ''));
        $paket         = trim((string) $request->query('paket', ''));

        $q = DB::table('simpus_master_pemeriksaan_lab as m')
            ->leftJoin('simpus_master_header_lab as h', 'h.idHeader', '=', 'm.headerId')
            ->leftJoin('simpus_master_subheader_lab as s', 's.idSubheader', '=', 'm.subheaderId');

        if ($search !== '') {
            $q->where(function ($x) use ($search) {
                $x->where('m.nmPemeriksaan', 'like', "%{$search}%")
                  ->orWhere('m.nmPemeriksaanInd', 'like', "%{$search}%")
                  ->orWhere('m.kode', 'like', "%{$search}%");
            });
        }

        if (!empty($headerId))    $q->where('m.headerId', (int) $headerId);
        if (!empty($subheaderId)) $q->where('m.subheaderId', (int) $subheaderId);
        if ($headerName !== '')    $q->where('h.nmHeader', $headerName);
        if ($subheaderName !== '') $q->where('s.nmSubheader', $subheaderName);

        if ($paket !== '') {
            $map = [
                'catin'         => 'm.paketCatin',
                'anck1'         => 'm.paketAnck1',
                'anck3'         => 'm.paketAnck3',
                'ptm'           => 'm.paketPtm',
                'darah_lengkap' => 'm.paketDarahLengkap',
                'widal'         => 'm.paketWidal',
            ];
            if (isset($map[$paket])) {
                $q->where($map[$paket], 1);
            }
        }

        $Master = $q->select([
                DB::raw('m.idPemeriksaan as id'),
                'm.idPemeriksaan',
                'm.kode',
                DB::raw('m.nmPemeriksaan as nama'),
                'm.nmPemeriksaanInd',
                'm.satuan',
                'm.headerId',
                'm.subheaderId',
                'h.nmHeader as header_name',
                's.nmSubheader as subheader_name',
                'm.noUrut',
                DB::raw("CONCAT(
                    'Nilai Normal : ', COALESCE(m.nilaiNormal, '-'),
                    '\nNilai Kritis : ', COALESCE(m.nilaiKritis, '-')
                ) as nilai_normal_kritis"),
            ])
            ->orderBy('h.nmHeader')
            ->orderBy('s.nmSubheader')
            ->orderByRaw('COALESCE(m.noUrut, 9999)')
            ->orderBy('m.kode')
            ->paginate(15);

        $links   = [];
        $links[] = ['label' => 'Previous', 'url' => $Master->previousPageUrl(), 'active' => false];

        $last = $Master->lastPage();
        $cur  = $Master->currentPage();

        $links[] = ['label' => 1, 'url' => $Master->url(1), 'active' => $cur === 1];
        if ($cur > 3) $links[] = ['label' => '...', 'url' => null, 'active' => false];

        for ($i = max(2, $cur - 1); $i <= min($last - 1, $cur + 1); $i++) {
            $links[] = ['label' => $i, 'url' => $Master->url($i), 'active' => $cur === $i];
        }

        if ($cur < $last - 2) $links[] = ['label' => '...', 'url' => null, 'active' => false];
        if ($last > 1) $links[] = ['label' => $last, 'url' => $Master->url($last), 'active' => $cur === $last];
        $links[] = ['label' => 'Next', 'url' => $Master->nextPageUrl(), 'active' => false];

        return response()->json([
            'data'  => $Master->items(),
            'meta'  => [
                'current_page' => $Master->currentPage(),
                'last_page'    => $Master->lastPage(),
                'per_page'     => $Master->perPage(),
                'total'        => $Master->total(),
            ],
            'links' => $links,
        ]);
    }

    /** =========================
     *  PARAMETER_UJI (baru): Header, Isi, Browse, Insert
     *  ========================= */
    public function paramHeaders(Request $request)
    {
        $rows = DB::table($this->paramTable . ' as h')
            ->leftJoin($this->paramTable . ' as d', function($j){
                $j->on('d.header', '=', DB::raw(0))
                  ->on('d.sub_header', '=', 'h.header');
            })
            ->where('h.header', '>', 0)
            ->when(Schema::hasColumn('parameter_uji', 'paket'), fn($q) => $q->where('h.paket', 'TRUE'))
            ->select([
                'h.header',
                DB::raw("MAX(h.nama_parameter) AS header_name"),
                DB::raw('COUNT(d.id_parameter) AS jumlah')
            ])
            ->groupBy('h.header')
            ->orderBy('h.header')
            ->get();

        return response()->json($rows);
    }

    public function paramSubheaders(Request $request, int $header)
    {
        $rows = DB::table('parameter_uji')
            ->where('header', 0)
            ->where('sub_header', $header)
            ->select([
                DB::raw('sub_header'),
                DB::raw('COUNT(*) AS jumlah')
            ])
            ->groupBy('sub_header')
            ->orderBy('sub_header')
            ->get();

        return response()->json($rows);
    }

    /** Helper insert batch dari PARAMETER_UJI → simpus_tindakan */
    private function insertParamBatch(string $loketId, string $permohonanId, $pelayananId, array $items): int
    {
        $now             = now();
        $cols            = Schema::getColumnListing('simpus_tindakan');
        $hasIdPelayanan  = in_array('idPelayanan', $cols);
        $hasCreatedDate  = in_array('createdDate', $cols);
        $hasModifiedDate = in_array('modifiedDate', $cols);

        $insertRows = [];

        foreach ($items as $p) {
            $kode = $this->norm($p->kode_parameter ?? '');
            if ($kode === '') continue;

            $exists = DB::table('simpus_tindakan')
                ->whereRaw('TRIM(loketId)=?', [$loketId])
                ->whereRaw('TRIM(permohonanId)=?', [trim($permohonanId)])
                ->whereRaw('TRIM(kdTindakan)=?', [$kode])
                ->exists();

            if ($exists) continue;

            $row = [
                'loketId'       => $loketId,
                'permohonanId'  => $permohonanId,
                'kdTindakan'    => $kode,
                'nmTindakan'    => $p->nama_parameter,
                'nmTindakanInd' => $p->nama_parameter,
                'nilaiLab'      => null,
            ];

            if ($hasIdPelayanan && !empty($pelayananId)) $row['idPelayanan'] = $pelayananId;
            if ($hasCreatedDate)  $row['createdDate']   = $now;
            if ($hasModifiedDate) $row['modifiedDate']  = $now;

            $insertRows[] = $row;
        }

        if ($insertRows) DB::table('simpus_tindakan')->insert($insertRows);
        return count($insertRows);
    }

    /** Ambil isi paket (anak) dari PARAMETER_UJI untuk header tertentu */
    public function paketParamItems(Request $request, int $header)
    {
        $sub = $request->query('sub_header');

        $q = DB::table($this->paramTable)
            ->where('header', 0)
            ->where('sub_header', $header);

        if (!is_null($sub) && $sub !== '') {
            $q->where('sub_header', (int)$sub);
        }

        $items = $q->select([
                'id_parameter',
                DB::raw('TRIM(kode_parameter) as kode'),
                DB::raw('nama_parameter as nama'),
                'satuan',
                'nilai_normal','nilai_kritis','header','sub_header',
                DB::raw("CONCAT(
                    'Nilai Normal : ', COALESCE(NULLIF(nilai_normal,''),'-'),
                    '\nNilai Kritis : ', COALESCE(NULLIF(nilai_kritis,''),'-')
                ) as nilai_normal_kritis")
            ])
            ->orderBy('sub_header')
            ->orderBy('id_parameter')
            ->get();

        return response()->json($items);
    }

    /** Simpan semua isi paket (berdasar header) ke simpus_tindakan */
    public function paramSimpan(Request $request, int $header)
    {
        $validated = $request->validate([
            'loketId'      => 'required|string',
            'permohonanId' => 'required|string',
            'pelayananId'  => 'nullable',
            'sub_header'   => 'nullable|integer',
        ]);

        $loketId   = $this->resolveLoketId($validated['loketId']);
        $orderId   = $this->norm($validated['permohonanId']);
        $pelayanan = $validated['pelayananId'] ?? null;

        $exists = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [$orderId])
            ->exists();
        if (!$exists) return back()->withErrors(['permohonanId' => 'Permohonan ID tidak valid.']);

        $q = DB::table($this->paramTable)
            ->where('header', 0)
            ->where('sub_header', $header);

        if (!empty($validated['sub_header'])) {
            $q->where('sub_header', (int) $validated['sub_header']);
        }

        $items = $q->select([
                'id_parameter',
                'kode_parameter',
                'nama_parameter',
                'nilai_normal',
                'nilai_kritis',
                'satuan'
            ])
            ->orderBy('id_parameter')
            ->get();

        DB::transaction(function () use ($loketId, $orderId, $pelayanan, $items) {
            $this->insertParamBatch($loketId, $orderId, $pelayanan, $items->all());
        });

        return back();
    }

    /** Browse parameter_uji (pencarian bebas / per header) */
    public function paramBrowse(Request $r)
    {
        $search     = trim($r->query('search', ''));
        $headerQ    = $r->query('header');
        $subHeaderQ = $r->query('sub_header');
        $perPage    = max(5, (int)$r->query('per_page', 25));

        $q = DB::table($this->paramTable);

        if ($headerQ !== null && $subHeaderQ === null) {
            $q->where('header', 0)->where('sub_header', (int)$headerQ);
        }
        if ($subHeaderQ !== null) {
            $q->where('sub_header', (int)$subHeaderQ);
        }

        if ($search !== '') {
            $like = '%'.str_replace(['%','_'], ['\%','\_'], $search).'%';
            $q->where(function($w) use ($like){
                $w->where('kode_parameter', 'like', $like)
                  ->orWhere('nama_parameter', 'like', $like)
                  ->orWhere('tipe_hasil_pemeriksaan', 'like', $like);
            });
        }

        $data = $q->select([
                'id_parameter',
                DB::raw('TRIM(kode_parameter) as kode'),
                DB::raw('nama_parameter as nama'),
                'satuan',
                DB::raw("TRIM(CONCAT(
                    'Nilai Normal : ', COALESCE(NULLIF(nilai_normal,''),'Negatif'), '\n',
                    'Nilai Kritis : ', COALESCE(NULLIF(nilai_kritis,''),'')
                )) AS nilai_normal_kritis")
            ])
            ->orderBy('id_parameter')
            ->paginate($perPage);

        return response()->json($data);
    }

    /** Simpan item terpilih (berdasar id_parameter[]) ke simpus_tindakan */
    public function paramSimpanTerpilih(Request $request)
    {
        $val = $request->validate([
            'loketId'      => 'required|string',
            'permohonanId' => 'required|string',
            'pelayananId'  => 'nullable',
            'ids'          => 'required|array|min:1',
            'ids.*'        => 'integer',
            'nilaiLab'     => 'nullable|array',
        ]);

        $loketId   = $this->resolveLoketId($val['loketId']);
        $orderId   = trim($val['permohonanId']);
        $pelayanan = $val['pelayananId'] ?? null;

        $exists = DB::table('simpus_permohonan_lab')
            ->whereRaw('TRIM(idPermohonan)=?', [$orderId])
            ->exists();
        if (!$exists) return back()->withErrors(['permohonanId' => 'Permohonan ID tidak valid.']);

        $items = DB::table($this->paramTable)
            ->whereIn('id_parameter', $val['ids'])
            ->select(['id_parameter','kode_parameter','nama_parameter','satuan','nilai_normal','nilai_kritis'])
            ->get();

        DB::transaction(function () use ($loketId, $orderId, $pelayanan, $items) {
            $this->insertParamBatch($loketId, $orderId, $pelayanan, $items->all());
        });

        return back();
    }
}
