<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\RuangLayanan\SimpusTindakan;

class TindakanService {
    public function svTindakan($data)
    {
        // dd($data);
        return SimpusTindakan::create([
            'idPelayanan' => $data['idpelayanan'],
            'kdTindakan' => $data['kode_tindakan'],
            'nmTindakan' => $data['nama_tindakan'],
            'nmTindakanInd' => $data['nama_tindakan_ind'],
            'keterangan' => $data['keterangan'],
            'loketId' => $data['loketId'],
            'kdPoli' => $data['kdPoli'],
            'deskripsi' => 'icd9cm',
            'createdDate' => now(),
        ]);
    }
    public function getTindakanPelayanan($idPelayanan)
    {
        return SimpusTindakan::where('idPelayanan', $idPelayanan)
            ->leftJoin('simpus_poli_fktp as poli', 'poli.kdPoli', '=', 'simpus_tindakan.kdPoli')
            ->select('simpus_tindakan.*', 'poli.nmPoli')
            ->get();
    }
    public function hapusTindakan(int $id): array
    {
        $tindakan = SimpusTindakan::find($id);

        if (!$tindakan) {
            return [
                'success' => false,
                'message' => 'Data tindakan tidak ditemukan.',
            ];
        }

        $tindakan->delete();

        return [
            'success' => true,
            'message' => 'Data tindakan berhasil dihapus.',
        ];
    }
}