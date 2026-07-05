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

    /**
     * Total kasus per kategori PTM dalam rentang tanggal.
     * Hasil: ['hipertensi' => 12, 'diabetes' => 5, ...]
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
     * Hasil: [ ['date' => '2026-07-01', 'hipertensi' => 3, 'diabetes' => 1, ...], ... ]
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
                $row[$key] = (int) ($perKeyDaily[$key][$d] ?? 0);
            }

            $rows[] = $row;
        }

        return $rows;
    }

    protected function countForKey(string $key, array $filters): int
    {
        $cfg = $this->ptmTables[$key] ?? null;
        if (!$cfg || !Schema::hasTable($cfg['table'])) {
            return 0;
        }

        $result = $this->baseKeyQuery($cfg, $filters)
            ->selectRaw('COUNT(DISTINCT t.' . $cfg['pk'] . ') as jml')
            ->first();

        return (int) ($result->jml ?? 0);
    }

    protected function perDayForKey(string $key, array $filters): array
    {
        $cfg = $this->ptmTables[$key] ?? null;
        if (!$cfg || !Schema::hasTable($cfg['table'])) {
            return [];
        }

        return $this->baseKeyQuery($cfg, $filters)
            ->selectRaw('DATE(l.tglKunjungan) as tgl, COUNT(DISTINCT t.' . $cfg['pk'] . ') as jml')
            ->groupBy('tgl')
            ->pluck('jml', 'tgl')
            ->toArray();
    }

    /**
     * Query dasar: tabel penyakit -> skrining -> pelayanan -> loket,
     * difilter ke poli PTM (006) dan rentang tanggal kunjungan.
     */
    protected function baseKeyQuery(array $cfg, array $filters)
    {
        return DB::table($cfg['table'] . ' as t')
            ->join('simpus_skrining_ptm as skrining', 'skrining.idSkrining', '=', "t.{$cfg['fk']}")
            ->join('simpus_pelayanan as pel', 'pel.idpelayanan', '=', 'skrining.idPelayanan')
            ->join('simpus_loket as l', 'l.idLoket', '=', 'pel.loketId')
            ->where('l.kdPoli', '006')
            ->whereBetween('l.tglKunjungan', [
                $filters['start_date'] . ' 00:00:00',
                $filters['end_date']   . ' 23:59:59',
            ]);
    }
}