<?php

namespace App\Http\Controllers\RuangLayanan\SkriningPTM; // sesuaikan dengan namespace controller Anda yang sebenarnya

use App\Services\SkriningPTM\LaporanPTMService;
use App\Services\SkriningPTM\LaporanService;
use App\Services\SkriningPTM\DashboardPTMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Http\Controllers\Controller;


class DashboardPTMController extends Controller // sesuaikan nama class sesuai project Anda
{
    public function __construct(
        private LaporanPTMService $laporanPTM,
        private DashboardPTMService $dashboardPTM,
        private LaporanService $laporan,
    ) {}

    // =========================================================================
    //  LAPORAN PTM  (Halaman Laporan/SkriningPTM/Index)
    // =========================================================================

    /**
     * Render halaman laporan PTM beserta data awal.
     * Filter dikirim via query string dari frontend (Inertia router.get).
     *
     * Query params yang didukung:
     *   - tgl_awal        : Y-m-d
     *   - tgl_akhir       : Y-m-d
     *   - jenis_kelamin   : L | P   (kosong = semua)
     *   - kelompok_usia   : 15-19 | 20-44 | 45-59 | 60+  (kosong = semua)
     *   - no_kel          : kode kelurahan  (kosong = semua)
     */
    public function laporanPTM(Request $request)
    {
        $request->validate([
            'tgl_awal'      => 'nullable|date',
            'tgl_akhir'     => 'nullable|date|after_or_equal:tgl_awal',
            'jenis_kelamin' => 'nullable|in:L,P',
            'kelompok_usia' => 'nullable|in:15-19,20-44,45-59,60+',
            'no_kel'        => 'nullable|string',
        ]);

        $filters = [
            'tgl_awal'      => $request->query('tgl_awal'),
            'tgl_akhir'     => $request->query('tgl_akhir'),
            'jenis_kelamin' => $request->query('jenis_kelamin'),
            'kelompok_usia' => $request->query('kelompok_usia'),
            'no_kel'        => $request->query('no_kel'),
        ];

        // Hanya tarik data jika filter tanggal sudah diisi
        // (hindari query berat saat halaman pertama dibuka)
        $dataTampil  = !empty($filters['tgl_awal']) && !empty($filters['tgl_akhir']);
        $dataLaporan = $dataTampil ? $this->laporanPTM->getDataLaporan($filters)        : [];
        $statistik   = $dataTampil ? $this->laporanPTM->getStatistikRingkasan($filters) : $this->defaultStatistik();

        // Daftar kelurahan untuk dropdown filter desa di frontend
        $daftarKelurahan = DB::table('setup_kel')
            ->select('NO_KEL as no_kel', 'nama_kel')
            ->orderBy('nama_kel')
            ->get();

        return Inertia::render('Laporan/SkriningPTM/Index', [
            'filters'         => $filters,
            'DataLaporan'     => $dataLaporan,
            'Statistik'       => $statistik,
            'DaftarKelurahan' => $daftarKelurahan,
            'dataTampil'      => $dataTampil,
        ]);
    }

    /**
     * Nilai default statistik saat belum ada filter tanggal yang diisi,
     * supaya frontend tidak error mengakses properti yang undefined.
     */
    protected function defaultStatistik(): array
    {
        return [
            'total_skrining'     => 0,
            'total_hipertensi'   => 0,
            'total_diabetes'     => 0,
            'total_obesitas'     => 0,
            'total_asam_urat'    => 0,
            'total_profil_lipid' => 0,
        ];
    }

    // =========================================================================
    //  DASHBOARD PTM  (Halaman Home/DashboardPTM)
    // =========================================================================

    /**
     * Render halaman dashboard PTM: kartu ringkasan 8 kategori penyakit
     * + tren harian untuk line chart. Default rentang tanggal = hari ini
     * kalau belum ada filter (sesuai kebutuhan frontend yang selalu punya
     * start_date/end_date terisi).
     *
     * Query params:
     *   - start_date : Y-m-d (default: hari ini)
     *   - end_date   : Y-m-d (default: hari ini)
     */
    public function dashboardPTM(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $today = now()->format('Y-m-d');

        $filters = [
            'start_date' => $request->query('start_date', $today),
            'end_date'   => $request->query('end_date', $today),
        ];

        return Inertia::render('Home/DashboardPTM', [
            'filters'   => $filters,
            'summary'   => $this->dashboardPTM->getSummary($filters),
            'perDayAll' => $this->dashboardPTM->getPerDayAll($filters),
            'serverNow' => $today,
        ]);
    }

  
}