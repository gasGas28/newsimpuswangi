<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class RegisterHTService
{
    protected array $kategoriNormal = ['Normal', 'Elevated', 'Hipertensi Grade 1'];

    public function getData(int $year, string $status = 'semua')
    {
        $query = DB::table('simpus_kunjungan_ptm')
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->join('simpus_hipertensi', 'simpus_hipertensi.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            ->whereYear('simpus_kunjungan_ptm.tanggal_skrining', $year);

        if ($status === 'tidak_normal') {
            $query->whereNotNull('simpus_hipertensi.kategori_tekanan_darah')
                ->whereNotIn('simpus_hipertensi.kategori_tekanan_darah', $this->kategoriNormal);
        }

        $rows = $query
            ->select([
                'simpus_pasien.NIK as nik',
                'simpus_pasien.NAMA_LGKP as nama',
                'simpus_pasien.TGL_LHR as tgl_lahir',
                'simpus_pasien.jenis_klmin as jenis_kelamin',
                'simpus_pasien.ALAMAT as alamat',
                'simpus_pasien.PHONE as no_hp',
                'simpus_kunjungan_ptm.tanggal_skrining as tanggal_kunjungan',
                'simpus_hipertensi.sistolik as sistolik',
                'simpus_hipertensi.tekanan_diastolik as diastolik',
                'simpus_hipertensi.kategori_tekanan_darah as kategori_tekanan_darah',
                'simpus_hipertensi.suhu as suhu',
                'simpus_hipertensi.nadi as nadi',
                'simpus_hipertensi.pernapasan as pernapasan',
            ])
            ->orderBy('simpus_kunjungan_ptm.tanggal_skrining')
            ->get()
            ->map(function ($row) {
                $row->vital = ($row->sistolik && $row->diastolik)
                    ? "{$row->sistolik}/{$row->diastolik}"
                    : '-';
                $row->jenis_kelamin = match ((int) $row->jenis_kelamin) {
                    1 => 'Laki-laki',
                    2 => 'Perempuan',
                    default => '-',
                };
                $row->jumlah_obat = null;
                $row->jenis_obat = null;
                $row->tempat_layanan = $row->tempat_layanan ?? 'P';

                return $row;
            });

        return $rows->groupBy('nik');
    }
}
