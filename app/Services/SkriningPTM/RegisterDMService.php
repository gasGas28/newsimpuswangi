<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class RegisterDMService
{
    public function getData(int $year)
    {
        $rows = DB::table('simpus_kunjungan_ptm')
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->join('simpus_diabetes', 'simpus_diabetes.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            // TODO: ganti 'tanggal_kunjungan' dengan nama kolom tanggal yang benar
            // di tabel simpus_kunjungan_ptm. sent_at di simpus_diabetes kemungkinan
            // timestamp kirim ke Satu Sehat, bukan tanggal periksa.
            ->whereYear('simpus_kunjungan_ptm.tanggal_skrining', $year)
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
