<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranLangsung extends Model
{
    protected $table = 'simpus_peng_langsung';
    protected $fillable = [
        'id_peng_langsung',
        'tanggal',
        'unitId',
        'pengMasterId',
        'keterangan',
        'obat_id',
        'kode_obat',
        'nama_obat',
        'jumlah',
        'createdDate',
        'createdBy',
        'modified_date',
        'modified_by'
    ];

    public $timestamps = false; // kalau tabel tidak pakai created_at & updated_at bawaan Laravel
}
