<?php

namespace App\Services\SkriningPTM;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\RuangLayanan\SimpusResepObat;


class RegisterHTService
{
    protected array $kategoriNormal = ['Normal', 'Prahipertensi'];

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
                'simpus_kunjungan_ptm.idPelayanan as idPelayanan',
                'simpus_hipertensi.sistolik as sistolik',
                'simpus_hipertensi.tekanan_diastolik as diastolik',
                'simpus_hipertensi.kategori_tekanan_darah as kategori_tekanan_darah',
                'simpus_hipertensi.suhu as suhu',
                'simpus_hipertensi.nadi as nadi',
                'simpus_hipertensi.pernapasan as pernapasan',
            ])
            ->orderBy('simpus_kunjungan_ptm.tanggal_skrining')
            ->get();

        // Ambil semua resep obat untuk seluruh idPelayanan yang ada di $rows, sekali query saja
        $idPelayananList = $rows->pluck('idPelayanan')->filter()->unique()->values();

        $obatByPelayanan = SimpusResepObat::select(
            'simpus_resep_obat.*',
            'simpus_resep_detail.obat_id',
            'simpus_resep_detail.JUMLAH as jumlah',
            'simpus_master_obat.NAMA',
            'simpus_master_obat.SATUAN',
        )
            ->join('simpus_resep_detail', 'simpus_resep_obat.id_resep', '=', 'simpus_resep_detail.resep_id')
            ->join('simpus_master_obat', 'simpus_resep_detail.obat_id', '=', 'simpus_master_obat.OBAT_ID')
            ->whereIn('pelayananId', $idPelayananList)
            ->get()
            ->groupBy('pelayananId');

        $rows = $rows->map(function ($row) use ($obatByPelayanan) {
            $row->vital = ($row->sistolik && $row->diastolik)
                ? "{$row->sistolik}/{$row->diastolik}"
                : '-';
            $row->jenis_kelamin = match ((int) $row->jenis_kelamin) {
                1 => 'Laki-laki',
                2 => 'Perempuan',
                default => '-',
            };

            $resepObat = $obatByPelayanan->get($row->idPelayanan, collect());

            $row->jumlah_obat = $resepObat->isNotEmpty()
                ? $resepObat->pluck('jumlah')
                : null;
            $row->jenis_obat = $resepObat->isNotEmpty()
                ? $resepObat->pluck('NAMA')->implode(', ')
                : null;

            $row->tempat_layanan = 'P';

            return $row;
        });

        return $rows->groupBy('nik');
    }
}