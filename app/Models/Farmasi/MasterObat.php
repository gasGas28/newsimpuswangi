<?php

namespace App\Models\Farmasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterObat extends Model
{
     use HasFactory;

    protected $table = 'simpus_master_obat'; // nama tabel
    protected $primaryKey = 'obat_id';
    public $timestamps = false;

    protected $fillable = [
        'obat_id',
        'kode_obat',
        'nama',
        'satuan',
        'jenis',
        'golongan',
        'sumber',
        'tahun',
        'harga',
        'isi',
        'rek',
        'aktif',
        'created_date'
    ];
}
