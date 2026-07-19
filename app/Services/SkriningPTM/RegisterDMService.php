<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class RegisterDMService
{
    protected array $kategoriNormal = ['Normal', 'Prediabetes'];

    public function getData(int $year, string $status = 'semua')
    {
        $query = DB::table('simpus_kunjungan_ptm')
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->join('simpus_diabetes', 'simpus_diabetes.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            ->whereYear('simpus_kunjungan_ptm.tanggal_skrining', $year);

        if ($status === 'tidak_normal') {
            $query->whereNotNull('simpus_diabetes.kategori_gula_darah_puasa')
                ->whereNotIn('simpus_diabetes.kategori_gula_darah_puasa', $this->kategoriNormal);
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
                'simpus_diabetes.gula_darah_puasa as gdp',
                'simpus_diabetes.gula_darah_2_jam_pp as gd2jpp',
                'simpus_diabetes.gula_darah_sewaktu as gds',
                'simpus_diabetes.hba1c as hba1c',
                'simpus_diabetes.kategori_gula_darah_puasa as kategori_gdp',
                'simpus_diabetes.kategori_gula_darah_sewaktu as kategori_gds',
                'simpus_diabetes.kategori_gula_darah_2_jam_pp as kategori_gd2jpp',
                'simpus_diabetes.kategori_hba1c as kategori_hba1c',
            ])
            ->orderBy('simpus_kunjungan_ptm.tanggal_skrining')
            ->get()
            ->map(function ($row) {
                // Tampilkan nilai gula darah yang terisi saja, urut prioritas:
                // GDP > GD2JPP > GDS > HbA1c. Sesuaikan urutan/label kalau perlu.
                $row->vital = match (true) {
                    !empty($row->gdp) => "GDP {$row->gdp}",
                    default => '-',
                };

                $row->jenis_kelamin = match ((int) $row->jenis_kelamin) {
                    1 => 'Laki-laki',
                    2 => 'Perempuan',
                    default => '-',
                };

                // simpus_diabetes TIDAK punya kolom obat (jumlah_obat / jenis_obat).
                // Kalau data obat ada di tabel lain, join di sini dan ganti 2 baris
                // di bawah. Sementara dikosongkan.
                $row->jumlah_obat = null;
                $row->jenis_obat = null;

                $row->tempat_layanan = $row->tempat_layanan ?? 'P';

                return $row;
            });

        return $rows->groupBy('nik');
    }
}
