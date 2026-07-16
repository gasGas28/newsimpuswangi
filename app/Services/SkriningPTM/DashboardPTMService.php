<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\CarbonPeriod;

class DashboardPTMService
{
    /**
     * Peta kunci PTM (dipakai frontend) -> nama tabel, kolom FK ke simpus_skrining_ptm,
     * dan primary key tabel tersebut.
     *
     * CATATAN PENTING:
     * - 'hipertensi', 'diabetes', 'obesitas' dikonfirmasi dari LaporanPTMService.
     * - 'kolesterol' saya map ke tabel simpus_profil_lipid (yang punya kolom
     *   kolesterol_total) karena tidak ada tabel "kolesterol" terpisah yang saya lihat.
     * - 'stroke', 'jantung', 'kanker', 'gagal_ginjal' MASIH ASUMSI nama tabel —
     *   belum ada di kode sebelumnya. Ganti 'table', 'fk', dan 'pk' di bawah
     *   sesuai skema database Anda yang sebenarnya.
     */
    protected array $ptmTables = [
        'hipertensi'   => ['table' => 'simpus_hipertensi',   'fk' => 'skriningID', 'pk' => 'id'],
        'diabetes'     => ['table' => 'simpus_diabetes',     'fk' => 'skriningID', 'pk' => 'id'],
        'obesitas'     => ['table' => 'simpus_obesitas',     'fk' => 'skriningID', 'pk' => 'id'],
        'kolesterol'   => ['table' => 'simpus_profil_lipid', 'fk' => 'skriningID', 'pk' => 'id'],
    ];

    protected const JENIS_BARU = 'Kunjungan Baru';
    protected const JENIS_LAMA = 'Kunjungan Lama';

    /**
     * Total kasus per kategori PTM dalam rentang tanggal, dipecah
     * jadi kunjungan baru, kunjungan lama, dan total keduanya.
     * Hasil: ['hipertensi' => ['baru' => 4, 'lama' => 8, 'total' => 12], ...]
     */
    public function getSummary(array $filters): array
    {
        $summary = [];
        foreach (array_keys($this->ptmTables) as $key) {
            $summary[$key] = $this->countForKey($key, $filters);
        }
        return $summary;
    }

    /**
     * Breakdown harian tiap kategori, kontinu dari start_date s/d end_date
     * (tanggal tanpa kasus tetap diisi 0 supaya garis chart tidak putus).
     * Tiap kategori per-hari juga dipecah baru/lama/total.
     * Hasil: [ ['date' => '2026-07-01', 'hipertensi' => ['baru'=>1,'lama'=>2,'total'=>3], ...], ... ]
     */
    public function getPerDayAll(array $filters): array
    {
        $perKeyDaily = [];
        foreach (array_keys($this->ptmTables) as $key) {
            $perKeyDaily[$key] = $this->perDayForKey($key, $filters);
        }

        $period = CarbonPeriod::create($filters['start_date'], $filters['end_date']);
        $rows = [];

        foreach ($period as $date) {
            $d   = $date->format('Y-m-d');
            $row = ['date' => $d];

            foreach (array_keys($this->ptmTables) as $key) {
                $row[$key] = $perKeyDaily[$key][$d] ?? ['baru' => 0, 'lama' => 0, 'total' => 0];
            }

            $rows[] = $row;
        }

        return $rows;
    }

    protected function countForKey(string $key, array $filters): array
    {
        $cfg = $this->ptmTables[$key] ?? null;
        $empty = ['baru' => 0, 'lama' => 0, 'total' => 0];

        if (!$cfg || !Schema::hasTable($cfg['table'])) {
            return $empty;
        }

        $result = $this->baseKeyQuery($cfg, $filters)
            ->selectRaw(
                "COUNT(DISTINCT CASE WHEN kj.jenis_kunjungan = ? THEN t.{$cfg['pk']} END) as baru,
                 COUNT(DISTINCT CASE WHEN kj.jenis_kunjungan = ? THEN t.{$cfg['pk']} END) as lama,
                 COUNT(DISTINCT t.{$cfg['pk']}) as total",
                [self::JENIS_BARU, self::JENIS_LAMA]
            )
            ->first();

        return [
            'baru'  => (int) ($result->baru ?? 0),
            'lama'  => (int) ($result->lama ?? 0),
            'total' => (int) ($result->total ?? 0),
        ];
    }

    protected function perDayForKey(string $key, array $filters): array
    {
        $cfg = $this->ptmTables[$key] ?? null;
        if (!$cfg || !Schema::hasTable($cfg['table'])) {
            return [];
        }

        $rows = $this->baseKeyQuery($cfg, $filters)
            ->selectRaw(
                "DATE(l.tglKunjungan) as tgl,
                 COUNT(DISTINCT CASE WHEN kj.jenis_kunjungan = ? THEN t.{$cfg['pk']} END) as baru,
                 COUNT(DISTINCT CASE WHEN kj.jenis_kunjungan = ? THEN t.{$cfg['pk']} END) as lama,
                 COUNT(DISTINCT t.{$cfg['pk']}) as total",
                [self::JENIS_BARU, self::JENIS_LAMA]
            )
            ->groupBy('tgl')
            ->get();

        $out = [];
        foreach ($rows as $r) {
            $out[$r->tgl] = [
                'baru'  => (int) $r->baru,
                'lama'  => (int) $r->lama,
                'total' => (int) $r->total,
            ];
        }

        return $out;
    }

    /**
     * Query dasar: tabel penyakit -> skrining -> pelayanan -> loket,
     * plus join ke kunjungan_ptm untuk status baru/lama,
     * difilter ke poli PTM (006) dan rentang tanggal kunjungan.
     */
    protected function baseKeyQuery(array $cfg, array $filters)
    {
        return DB::table($cfg['table'] . ' as t')
            ->join('simpus_skrining_ptm as skrining', 'skrining.idSkrining', '=', "t.{$cfg['fk']}")
            ->join('simpus_pelayanan as pel', 'pel.idpelayanan', '=', 'skrining.idPelayanan')
            ->join('simpus_loket as l', 'l.idLoket', '=', 'pel.loketId')
            ->leftJoin('simpus_kunjungan_ptm as kj', 'kj.idSkrining', '=', 'skrining.idSkrining')
            ->where('l.kdPoli', '006')
            ->whereBetween('l.tglKunjungan', [
                $filters['start_date'] . ' 00:00:00',
                $filters['end_date']   . ' 23:59:59',
            ]);
    }
}
