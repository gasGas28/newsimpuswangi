<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;

class RegisterPasienPTMService
{
    public function getRegister()
    {
        $query = KunjunganPTM::select(
            'simpus_kunjungan_ptm.*',
            'simpus_pasien.*',
            'simpus_diabetes.*',
            'simpus_hipertensi.*'
        )
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->join('simpus_diabetes', 'simpus_diabetes.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            ->join('simpus_hipertensi', 'simpus_hipertensi.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining');
        return $query->get();
    }
}
