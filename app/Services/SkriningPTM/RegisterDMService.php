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
            $normal = array_map('mb_strtolower', $this->kategoriNormal); // ['normal', 'prediabetes']
            $placeholders = implode(',', array_fill(0, count($normal), '?'));

            $query->where(function ($q) use ($normal, $placeholders) {
                $q->whereRaw("LOWER(TRIM(simpus_diabetes.kategori_gula_darah_puasa)) NOT IN ({$placeholders})", $normal)
                    ->orWhereRaw("LOWER(TRIM(simpus_diabetes.kategori_gula_darah_sewaktu)) NOT IN ({$placeholders})", $normal)
                    ->orWhereRaw("LOWER(TRIM(simpus_diabetes.kategori_gula_darah_2_jam_pp)) NOT IN ({$placeholders})", $normal)
                    ->orWhereRaw("LOWER(TRIM(simpus_diabetes.kategori_hba1c)) NOT IN ({$placeholders})", $normal);
            });
        }

        $rows = $query
            ->select([
                'simpus_pasien.NIK as nik',
                'simpus_pasien.NAMA_LGKP as nama',
                'simpus_pasien.TGL_LHR as tgl_lahir',
                'simpus_pasien.jenis_klmin as jenis_kelamin', // TODO: cek nama kolom asli
                'simpus_pasien.ALAMAT as alamat',
                'simpus_pasien.PHONE as no_hp',                 // TODO: cek nama kolom asli, boleh null
                'simpus_kunjungan_ptm.tanggal_skrining as tanggal_kunjungan', // TODO: sesuaikan
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
                    !empty($row->gd2jpp) => "GD2JPP {$row->gd2jpp}",
                    !empty($row->gds) => "GDS {$row->gds}",
                    !empty($row->hba1c) => "HbA1c {$row->hba1c}",
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
