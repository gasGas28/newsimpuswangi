<?php

namespace App\Services;

use App\Models\Loket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoketService
{
    public function datatable(array $request)
    {
        // Konversi kasar dari get_datatables pada CI3
        // Untuk keperluan, kita ambil filter dasar dan paginate manual
        $query = DB::table('simpus_loket as a')
            ->leftJoin('simpus_pasien as p', 'a.pasienId', '=', 'p.ID')
            ->leftJoin('unit_profiles as u', 'a.puskId', '=', 'u.unit_id')
            ->select('a.*', 'p.NO_MR', 'p.NAMA_LGKP', 'p.NIK', 'u.nama_unit')
            ->orderBy('a.tglKunjungan', 'desc');

        // Jika DataTables send start/length/search
        $start = (int) ($request['start'] ?? 0);
        $length = (int) ($request['length'] ?? 10);

        // search global (basic)
        if (!empty($request['search']['value'] ?? null)) {
            $s = $request['search']['value'];
            $query->where(function ($q) use ($s) {
                $q->where('p.NAMA_LGKP', 'like', "%$s%")
                    ->orWhere('p.NO_MR', 'like', "%$s%")
                    ->orWhere('p.NIK', 'like', "%$s%");
            });
        }

        $totalFiltered = $query->count();

        $rows = $query->offset($start)->limit($length)->get();

        // Build data array similar to CI3 controller
        $data = [];
        foreach ($rows as $r) {
            $statusKunj = ($r->kunjBaru === 'true' || $r->kunjBaru === true) ? 'Baru' : 'Lama';
            $label = ($statusKunj === 'Baru') ? 'info' : 'primary';
            $noHp = $r->PHONE ?? '-';

            // background warna berdasarkan status kartu (simplified)
            $bgcolor = ($r->statusKartu === 'AKTIF' || strtolower($r->statusKartu) === 'aktif') ? '' : '#de6666';

            $row = [];
            $row[] = Carbon::parse($r->tglKunjungan)->format('d-m-Y') . '<br/>' . ($r->noUrut ?? '');
            $row[] = $r->no_antrian ? '<a class="btn btn-xs btn-warning" onclick="print_Antrian(\'' . $r->idLoket . '\')">' . $r->no_antrian . '</a>'
                : '<a class="btn btn-xs btn-info" onclick="get_noAntrian(\'' . $r->idLoket . '\',\'' . $r->kdPoli . '\')">Get Nomor <br>Antrian Poli</a>';

            $row[] = '<a class="btn btn-xs btn-info" target="_blank" href="/pelayananDetail/popRiwayatSimpus/' . $r->ID . '">' . ($r->NO_MR ?? '') . '</a><br/>' .
                ($r->NAMA_LGKP ?? '') . ' <strong>(' . ($r->umur ?? '') . ')</strong><br/>' . ($r->NIK ?? '') .
                '<br/><span class="label label-' . $label . '">' . $statusKunj . '</span>';

            $alamat = ($r->ALAMAT ?? '') . ' RT ' . ($r->NO_RT ?? '') . ' RW ' . ($r->NO_RW ?? '');
            $row[] = $alamat . '<br/>' . ($r->nama_unit ?? '') . '<br/><b>No TLP : </b>' . $noHp;
            $row[] = ($r->noKartu ?? '') . '<br/><p style="background-color:' . $bgcolor . ';font-size:11px;">' . ($r->statusKartu ?? '') . '</p><p style="font-size:10px;">' . ($r->providerKartu ?? '') . '</p>';
            $row[] = ($r->kdPoli ?? '') . '<br/>' . ($r->nmMal ?? '');
            $row[] = ($r->nama_unit ?? '');
            $row[] = ''; // placeholder for Urut/JKN info
            $row[] = '
                <a type="button" class="btn btn-xs btn-info" href="/loket/cetak_kartu/' . $r->pasienId . '"><i class="fa fa-credit-card"></i></a>
                <a type="button" class="btn btn-xs btn-warning" href="/loket/form_edit/' . $r->pasienId . '/' . $r->idLoket . '"><i class="fa fa-edit"></i></a>
            ';

            $data[] = $row;
        }

        return [
            'draw' => (int) ($request['draw'] ?? 1),
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ];
    }

    public function calculateAgeGroups(array &$data)
    {
        // expecting TGL_LHR present
        if (empty($data['TGL_LHR'])) {
            $data['kelUmur'] = null;
            return;
        }

        $dob = Carbon::parse($data['TGL_LHR']);
        $today = Carbon::today();
        $diff = $today->diff($dob);
        $y = $diff->y;
        $m = $diff->m;
        $d = $diff->d;

        if ($y == 0) {
            if ($m != 0) {
                $kel_umur = "3";
            } else {
                if (($d >= 0) && ($d <= 7)) {
                    $kel_umur = "1";
                } else if (($d >= 8) && ($d <= 28)) {
                    $kel_umur = "2";
                } else if (($d >= 29) && ($d <= 30)) {
                    $kel_umur = "3";
                } else {
                    $kel_umur = "3";
                }
            }
        } elseif ($y >= 1 && $y <= 4) $kel_umur = "4";
        elseif ($y >= 5 && $y <= 9) $kel_umur = "5";
        elseif ($y >= 10 && $y <= 14) $kel_umur = "6";
        elseif ($y >= 15 && $y <= 19) $kel_umur = "7";
        elseif ($y >= 20 && $y <= 44) $kel_umur = "8";
        elseif ($y >= 45 && $y <= 54) $kel_umur = "9";
        elseif ($y >= 55 && $y <= 59) $kel_umur = "10";
        elseif ($y >= 60 && $y <= 69) $kel_umur = "11";
        elseif ($y >= 70) $kel_umur = "12";
        else $kel_umur = "0";

        $data['kelUmur'] = $kel_umur;
        $data['umur'] = $y;
        $data['umur_bulan'] = $m;
        $data['umur_hari'] = $d;
    }

    // Example wrapping function to execute raw queries from CI3 (reports)
    public function rawSelect($sql, $bindings = [])
    {
        return DB::select($sql, $bindings);
    }
}
