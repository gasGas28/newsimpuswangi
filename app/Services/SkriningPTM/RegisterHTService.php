<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class RegisterHTService
{
    public function getData(int $year)
    {
        $rows = DB::table('simpus_kunjungan_ptm')
            ->join('simpus_pasien', 'simpus_pasien.NIK', '=', 'simpus_kunjungan_ptm.nik_pasien')
            ->join('simpus_hipertensi', 'simpus_hipertensi.skriningID', '=', 'simpus_kunjungan_ptm.idSkrining')
            // TODO: ganti 'tanggal_kunjungan' dengan nama kolom tanggal yang benar
            // di tabel simpus_kunjungan_ptm (cek migration-nya). sent_at di tabel
            // simpus_hipertensi kemungkinan timestamp kirim ke Satu Sehat, bukan
            // tanggal periksa, jadi TIDAK dipakai di sini.
            ->whereYear('simpus_kunjungan_ptm.tanggal_skrining', $year)
            ->select([
                'simpus_pasien.NIK as nik',
                'simpus_pasien.NAMA_LGKP as nama',
                'simpus_pasien.TGL_LHR as tgl_lahir',
                'simpus_pasien.jenis_klmin as jenis_kelamin', // TODO: cek nama kolom asli
                'simpus_pasien.ALAMAT as alamat',
                'simpus_pasien.PHONE as no_hp',                 // TODO: cek nama kolom asli, boleh null
                'simpus_kunjungan_ptm.tanggal_skrining as tanggal_kunjungan', // TODO: sesuaikan
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

                // simpus_hipertensi TIDAK punya kolom obat (jumlah_obat / jenis_obat).
                // Kalau data obat ada di tabel lain (mis. resep/terapi), join di sini
                // dan ganti 2 baris di bawah. Sementara dikosongkan.
                $row->jumlah_obat = null;
                $row->jenis_obat = null;

                // Belum tentu ada kolom tempat_layanan, default 'P'.
                $row->tempat_layanan = $row->tempat_layanan ?? 'P';

                return $row;
            });

        return $rows->groupBy('nik');
    }
}
