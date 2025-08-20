<?php

namespace App\Models\Home;

use Illuminate\Support\Facades\DB;

class DashboardHome
{
    public static function perDayAll(string $startDate, string $endDate)
    {
        $rows = DB::table('simpus_loket as l')
            ->leftJoin('simpus_pasien as p', 'p.ID', '=', 'l.pasienId')
            ->selectRaw("
                DATE(l.tglKunjungan) AS date,
                SUM(p.JENIS_KLMIN = 1) AS male,
                SUM(p.JENIS_KLMIN = 2) AS female
            ")
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $rows->map(fn($r) => [
            'date'   => $r->date,
            'male'   => (int)($r->male ?? 0),
            'female' => (int)($r->female ?? 0),
        ])->toArray();
    }

    public static function genderTotals(string $startDate, string $endDate)
    {
        $row = DB::table('simpus_loket as l')
            ->leftJoin('simpus_pasien as p', 'p.ID', '=', 'l.pasienId')
            ->selectRaw("
                SUM(p.JENIS_KLMIN = 1) AS male,
                SUM(p.JENIS_KLMIN = 2) AS female
            ")
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate])
            ->first();

        return [
            'male'   => (int)($row->male ?? 0),
            'female' => (int)($row->female ?? 0),
        ];
    }

    public static function paymentTotals(string $startDate, string $endDate)
    {
        $row = DB::table('simpus_loket as l')
            ->selectRaw("
                SUM(UPPER(TRIM(l.jknPbi)) IN ('BPJS','JKN','PBI')) AS bpjs,
                SUM(UPPER(TRIM(l.jknPbi)) NOT IN ('BPJS','JKN','PBI')) AS non_bpjs
            ")
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate])
            ->first();

        return [
            'bpjs'     => (int)($row->bpjs ?? 0),
            'non_bpjs' => (int)($row->non_bpjs ?? 0),
        ];
    }

    public static function visitTotals(string $startDate, string $endDate)
    {
        $row = DB::table('simpus_loket as l')
            ->selectRaw("
                SUM(UPPER(TRIM(l.kunjBaru)) = 'YA') AS baru,
                SUM(UPPER(TRIM(l.kunjBaru)) <> 'YA') AS lama
            ")
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate])
            ->first();

        return [
            'baru' => (int)($row->baru ?? 0),
            'lama' => (int)($row->lama ?? 0),
        ];
    }

    public static function referralTotals(string $startDate, string $endDate)
    {
        // sementara: semua internal
        $row = DB::table('simpus_loket as l')
            ->selectRaw("COUNT(*) AS internal, 0 AS rujukan")
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate])
            ->first();

        return [
            'internal' => (int)($row->internal ?? 0),
            'rujukan'  => (int)($row->rujukan ?? 0),
        ];
    }

    public static function topDiseases(string $startDate, string $endDate, int $limit = 10)
    {
        $rows = DB::table('simpus_loket as l')
            ->selectRaw("TRIM(COALESCE(l.keluhan, 'Tidak diketahui')) AS nama, COUNT(*) AS total")
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate])
            ->groupBy('nama')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        $ranked = [];
        $i = 1;
        foreach ($rows as $r) {
            $ranked[] = [
                'kode'  => sprintf('K%02d', $i++), // placeholder
                'nama'  => $r->nama,
                'total' => (int)$r->total,
            ];
        }
        return $ranked;
    }
}
