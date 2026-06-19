<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Http\Controllers\Controller;


class DashboardPtmController extends Controller
{
    /**
     * Daftar key PTM yang didukung.
     * Tambahkan/kurangi di sini saja kalau jenis PTM berubah —
     * dipakai konsisten di summary maupun tren harian.
     *
     * 'count_column' = kolom PK tabel skrining masing-masing,
     * dipakai untuk COUNT(DISTINCT ...) agar leftJoin 1:many tidak
     * menggandakan hitungan.
     *
     * SESUAIKAN nama kolom PK di bawah ini dengan struktur tabel
     * aslinya jika berbeda.
     */
    protected array $ptmTables = [
        'hipertensi'    => ['table' => 'simpus_hipertensi',    'alias' => 'hipertensi',    'pk' => 'id'],
        'diabetes'      => ['table' => 'simpus_diabetes',      'alias' => 'diabetes',      'pk' => 'id'],
        'obesitas'      => ['table' => 'simpus_obesitas',      'alias' => 'obesitas',      'pk' => 'id'],
        'asam_urat'     => ['table' => 'simpus_asam_urat',     'alias' => 'asam_urat',     'pk' => 'id'],
        'profil_lipid'  => ['table' => 'simpus_profil_lipid',  'alias' => 'profil_lipid',  'pk' => 'id'],
    ];

    public function index(Request $request)
    {
        $startDate = $request->query('start_date')
            ?: Carbon::today()->subDays(7)->toDateString();
        $endDate   = $request->query('end_date') ?: Carbon::today()->toDateString();

        // Validasi ringan: kalau end_date < start_date, swap supaya query tetap aman
        if ($endDate < $startDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $summary = $this->getSummaryPTM($startDate, $endDate);
        $tren    = $this->getTrenHarianPTM($startDate, $endDate);

        return Inertia::render('Home/DashboardPTM', [
            'filters' => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'summary'  => $summary,
            'trenHarian' => $tren,
        ]);
    }

    /**
     * Total kasus per jenis PTM dalam rentang tanggal (untuk metric cards & tabel ringkasan).
     */
    protected function getSummaryPTM(string $startDate, string $endDate): array
    {
        $query = $this->baseQuery($startDate, $endDate);

        $selects = [];
        foreach ($this->ptmTables as $key => $cfg) {
            $selects[] = "COUNT(DISTINCT {$cfg['alias']}.{$cfg['pk']}) as {$key}";
        }

        $row = $query->selectRaw(implode(', ', $selects))->first();

        // Pastikan semua key ada & berupa integer, walau hasilnya null/0
        $summary = [];
        foreach ($this->ptmTables as $key => $cfg) {
            $summary[$key] = (int) ($row->{$key} ?? 0);
        }

        return $summary;
    }

    /**
     * Tren harian per jenis PTM (untuk line chart), satu baris per tanggal.
     */
    protected function getTrenHarianPTM(string $startDate, string $endDate): array
    {
        $query = $this->baseQuery($startDate, $endDate);

        $selects = ['l.tglKunjungan as tanggal'];
        foreach ($this->ptmTables as $key => $cfg) {
            $selects[] = "COUNT(DISTINCT {$cfg['alias']}.{$cfg['pk']}) as {$key}";
        }

        $rows = $query
            ->groupBy('l.tglKunjungan')
            ->orderBy('l.tglKunjungan')
            ->selectRaw(implode(', ', $selects))
            ->get();

        // Isi tanggal yang kosong (tidak ada kasus sama sekali) agar chart tidak bolong
        $byDate = $rows->keyBy('tanggal');
        $result = [];

        $cursor = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($cursor->lte($end)) {
            $dateStr = $cursor->toDateString();
            $existing = $byDate->get($dateStr);

            $entry = ['tanggal' => $dateStr];
            foreach ($this->ptmTables as $key => $cfg) {
                $entry[$key] = $existing ? (int) $existing->{$key} : 0;
            }

            $result[] = $entry;
            $cursor->addDay();
        }

        return $result;
    }

    /**
     * Query dasar bersama (join + filter) yang dipakai summary & tren.
     * Sengaja TIDAK pakai select('*') — hanya join tabel yang dibutuhkan
     * untuk menghitung jenis PTM, tanpa menarik kolom pasien/loket yang
     * tidak relevan untuk dashboard agregat ini.
     */
    protected function baseQuery(string $startDate, string $endDate)
    {
        $query = DB::table('simpus_loket as l')
            ->join('simpus_pelayanan as pel', 'l.idLoket', '=', 'pel.loketId')
            ->join('simpus_skrining_ptm as skrining', 'pel.idpelayanan', '=', 'skrining.idPelayanan')
            ->where('l.kdPoli', '006')
            ->whereBetween('l.tglKunjungan', [$startDate, $endDate]);

        foreach ($this->ptmTables as $key => $cfg) {
            $query->leftJoin(
                "{$cfg['table']} as {$cfg['alias']}",
                'skrining.idSkrining',
                '=',
                "{$cfg['alias']}.skriningID"
            );
        }

        return $query;
    }
}
