<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Home\DashboardHome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Default = hari ini
        $serverNow = Carbon::now()->format('Y-m-d');

        $mode  = $request->input('mode', 'harian');
        $date  = $request->input('date', $serverNow);
        $start = $request->input('start', $date);
        $end   = $request->input('end', $date);

        if ($mode === 'harian') {
            $start = $date;
            $end   = $date;
        }

        // Ambil data dari model
        $perDayAll = DashboardHome::perDayAll($start, $end);
        $gender    = DashboardHome::genderTotals($start, $end);
        $payment   = DashboardHome::paymentTotals($start, $end);
        $visit     = DashboardHome::visitTotals($start, $end);
        $referral  = DashboardHome::referralTotals($start, $end);
        $topDis    = DashboardHome::topDiseases($start, $end, 10);

        return Inertia::render('Home/Home', [
            'serverNow'   => $serverNow,
            'filters'     => [
                'mode' => $mode,
                'date' => $date,
                'start'=> $start,
                'end'  => $end,
            ],
            'perDayAll'   => $perDayAll,
            'gender'      => $gender,
            'payment'     => $payment,
            'visit'       => $visit,
            'referral'    => $referral,
            'topDiseases' => $topDis,
        ]);
    }
}
