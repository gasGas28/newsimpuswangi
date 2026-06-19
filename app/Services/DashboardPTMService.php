<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


class DashboardPTMService
{
    public function getDataPasien()
    {
        $DataPasien = DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_pelayanan as pel', 'l.idLoket', '=', 'pel.loketId')
            ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('unit_profiles as up', 'up.unit_id', '=', 'l.unitId')
            ->leftJoin('simpus_skrining_ptm as skrining', 'pel.idpelayanan', '=', 'skrining.idPelayanan')
            ->leftJoin('simpus_kunjungan_ptm as kunjungan', 'kunjungan.idSkrining', '=', 'skrining.idSkrining')
            ->leftJoin('master_dokter as dokter', 'dokter.ihs_nakes', '=', 'kunjungan.id_petugas')
            ->leftJoin('faktor_risiko_ptm as frisiko', 'skrining.idSkrining', '=', 'frisiko.skriningID')
            ->leftJoin('simpus_hipertensi as hipertensi', 'skrining.idSkrining', '=', 'hipertensi.skriningID')
            ->leftJoin('simpus_diabetes as diabetes', 'skrining.idSkrining', '=', 'diabetes.skriningID')
            ->leftJoin('simpus_obesitas as obesitas', 'skrining.idSkrining', '=', 'obesitas.skriningID')
            ->leftJoin('simpus_asam_urat as asam_urat', 'skrining.idSkrining', '=', 'asam_urat.skriningID')
            ->leftJoin('simpus_profil_lipid as profil_lipid', 'skrining.idSkrining', '=', 'profil_lipid.skriningID')


            ->leftJoin('setup_kel as kel', function ($join) {
                $join->on('p.NO_KEL', '=', 'kel.NO_KEL')
                    ->on('p.NO_KEC', '=', 'kel.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kel.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kel.NO_PROP');
            })

            ->leftJoin('setup_kec as kec', function ($join) {
                $join->on('p.NO_KEC', '=', 'kec.NO_KEC')
                    ->on('p.NO_KAB', '=', 'kec.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kec.NO_PROP');
            })

            ->leftJoin('setup_kab as kab', function ($join) {
                $join->on('p.NO_KAB', '=', 'kab.NO_KAB')
                    ->on('p.NO_PROP', '=', 'kab.NO_PROP');
            })

            ->leftJoin('setup_prop as prop', 'p.NO_PROP', '=', 'prop.NO_PROP')


            ->where('l.kdPoli', '006')

            ->select(
                'p.ID',
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'p.IHS_NUMBER',
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'p.jenis_klmin',
                'l.kdPoli',
                'l.umur',
                'l.umur_bulan',
                'l.umur_hari',
                'l.idLoket',
                'l.tglKunjungan',
                'l.kunjBaru',
                'l.idLoket',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli',
                'pel.idpelayanan',
                'pel.sudahDilayani',
                'pel.startTime',
                'pel.progressTime',
                'up.nama_unit',
                'dokter.nmDokter',
                'kunjungan.*',
                'skrining.*',
                'frisiko.*',
                'hipertensi.*',
                'diabetes.*',
                'obesitas.*',
                'asam_urat.*',
                'profil_lipid.*',
            )
            ->get();

        return $DataPasien;
    }
}
