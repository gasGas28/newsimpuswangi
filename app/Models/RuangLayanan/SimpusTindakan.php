<?php

namespace App\Models\RuangLayanan;

use Illuminate\Database\Eloquent\Model;

class SimpusTindakan extends Model
{
    protected $table = 'simpus_tindakan';
    protected $primaryKey = 'idTindakan';
    public $timestamps = false;

    protected $fillable = [
        'permohonanId',
        'pemeriksaanId',
        'kdTindakan',
        'nmTindakan',
        'nmTindakanInd',
        'peraturan',
        'harga',
        'bayar',
        'kdPoli',
        'kdPoliLab',
        'loketId',
        'idPelayanan',
        'keterangan',
        'ketGigi',
        'nilaiLab',
        'statusNilaiKritis',
        'createdDate',
        'createdBy',
        'modifiedDate',
        'modifiedBy',
        'tglTindakan',
        'kategori',
        'deskripsi',
        'id_procedure'
    ];
}

