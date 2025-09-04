<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Home\DashboardHome;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Tanggal server (YYYY-mm-dd)
        $serverNow = Carbon::now()->format('Y-m-d');

        // Ambil rentang dari query; default = hari ini s/d hari ini
        $start = $request->input('start_date', $serverNow);
        $end   = $request->input('end_date',   $serverNow);

        // Normalisasi kalau kebalik
        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }

        // ===== Ambil data sesuai rentang =====
        $perDayAll = DashboardHome::perDayAll($start, $end);
        $gender    = DashboardHome::genderTotals($start, $end);
        $payment   = DashboardHome::paymentTotals($start, $end);
        $visit     = DashboardHome::visitTotals($start, $end);
        $referral  = DashboardHome::referralTotals($start, $end);
        $topDis    = DashboardHome::topDiseases($start, $end, 10);

        // Kirim ke Inertia (struktur sesuai frontend)
        return Inertia::render('Home/Home', [
            'serverNow' => $serverNow,
            'filters'   => [
                'start_date' => $start,
                'end_date'   => $end,
            ],
            'perDayAll'   => $perDayAll,   // sudah zero-fill untuk semua hari di rentang
            'gender'      => $gender,
            'payment'     => $payment,
            'visit'       => $visit,
            'referral'    => $referral,
            'topDiseases' => $topDis,
        ]);
    }
}
