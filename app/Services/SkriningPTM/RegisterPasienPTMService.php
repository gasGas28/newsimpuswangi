<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SkriningPTM\KunjunganPTM;

class RegisterPasienPTMService
{
    protected array $kategoriNormalDM = ['Normal', 'Prediabetes'];
    protected array $kategoriNormalHT = ['Normal'];

    public function getRegister(string $nik, string $status = 'semua')
    {
        $query = KunjunganPTM::select(
            'simpus_kunjungan_ptm.*',
            'simpus_pasien.*',
            'simpus_diabetes.*',
            'simpus_hipertensi.*'
        )
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->join('simpus_diabetes', 'simpus_diabetes.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            ->join('simpus_hipertensi', 'simpus_hipertensi.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            ->where('simpus_kunjungan_ptm.nik_pasien', $nik);

        if ($status === 'tidak_normal') {
            $query->where(function ($q) {
                $q->whereNotIn('simpus_hipertensi.kategori_tekanan_darah', $this->kategoriNormalHT)
                  ->orWhereNotIn('simpus_diabetes.kategori_gula_darah_puasa', $this->kategoriNormalDM)
                  ->orWhereNotIn('simpus_diabetes.kategori_gula_darah_sewaktu', $this->kategoriNormalDM)
                  ->orWhereNotIn('simpus_diabetes.kategori_gula_darah_2_jam_pp', $this->kategoriNormalDM)
                  ->orWhereNotIn('simpus_diabetes.kategori_hba1c', $this->kategoriNormalDM);
            });
        }

        return $query->get();
    }
}
