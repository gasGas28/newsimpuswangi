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
    /**
     * Key session untuk menyimpan filter tanggal dashboard PTM terakhir,
     * supaya tidak reset ke hari ini saat pindah halaman lalu balik lagi
     * (URL bersih tanpa query start_date/end_date).
     */
    private const SESSION_KEY_DASHBOARD_FILTER = 'ptm_dashboard_filters';

    public function __construct(
        private DashboardPTMService $dashboardPTM,
    ) {}

    public function dashboardPTM(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $today = now()->format('Y-m-d');

        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        if ($startDate && $endDate) {
            session([self::SESSION_KEY_DASHBOARD_FILTER => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]]);
        } else {
            $filterTersimpan = session(self::SESSION_KEY_DASHBOARD_FILTER);

            $startDate = $filterTersimpan['start_date'] ?? $today;
            $endDate   = $filterTersimpan['end_date'] ?? $today;
        }

        $filters = [
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ];

        return Inertia::render('Home/DashboardPTM', [
            'filters'   => $filters,
            'summary'   => $this->dashboardPTM->getSummary($filters),
            'perDayAll' => $this->dashboardPTM->getPerDayAll($filters),
            'serverNow' => $today,
        ]);
    }

  
}