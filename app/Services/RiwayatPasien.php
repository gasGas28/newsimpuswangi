<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;


class RiwayatPasien
{
    public function getDataPasien($idSkrining)
    {
      $skrining = KunjunganPTM::select(
                'simpus_kunjungan_ptm.*',
                'simpus_pasien.*',
                'simpus_obesitas.*',
                'simpus_hipertensi.*'
            )
                ->join(
                    'simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien'
                )
                ->join(
                    'simpus_obesitas', 'simpus_obesitas.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining'
                )
                ->join(
                    'simpus_hipertensi', 'simpus_hipertensi.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining'
                )
                ->where('simpus_kunjungan_ptm.idSkrining', $idSkrining)
                ->get();
       
    //    dd($skrining);
       return $skrining;
    }
}
