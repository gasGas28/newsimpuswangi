<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SimpusTindakan;

class PelayananPTMService
{
    public function getData($id, $idPoli)
    {
        return DB::table('simpus_loket as l')
            ->join('simpus_pasien as p', 'l.pasienId', '=', 'p.ID')
            ->join('simpus_pelayanan as pel', 'l.idLoket', '=', 'pel.loketId')
            ->join('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'l.kdPoli')
            ->leftJoin('unit_profiles as up', 'up.unit_id', '=','l.unitId')

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

            ->where('l.kdPoli', $idPoli)
            ->where('l.idLoket', $id)

            ->select(
                'p.ID',
                'p.NO_MR',
                'p.NAMA_LGKP',
                'p.NIK',
                'p.IHS_NUMBER',
                'kel.nama_kel',
                'kec.nama_kec',
                'kab.nama_kab',
                'prop.nama_prop',
                'poli.nmPoli',
                'l.kdPoli',
                'p.alamat',
                'p.no_rt',
                'p.no_rw',
                'p.jenis_klmin',
                'l.umur',
                'l.umur_bulan',
                'l.umur_hari',
                'l.tglKunjungan',
                'l.idLoket',
                'l.kunjBaru',
                'pel.idpelayanan',
                'pel.sudahDilayani',
                'pel.startTime',
                'pel.progressTime',
                'up.nama_unit',
            )
            ->first();
    }

    public function getMasterData()
    {
        $SimpusTindakan = SimpusTindakan::select('kdTindakan', 'nmTindakan', 'nmTindakanInd')
            ->where('deskripsi', 'icd9cm')
            ->groupBy('kdTindakan', 'nmTindakan', 'nmTindakanInd')
            ->get();
        // dd($SimpusTindakan);
        return [
            'tindakan' => $SimpusTindakan,
        ];
    }
    public function updateStatusPelayanan($idPelayanan, $status)
    {
        DB::table('simpus_pelayanan')
            ->where('idpelayanan', $idPelayanan)
            ->update([
                'sudahDilayani' => $status,
                'startTime' => now(),
            ]);
    }   
}
