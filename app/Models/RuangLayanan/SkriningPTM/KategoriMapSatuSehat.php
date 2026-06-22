<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class KategoriMapSatuSehat extends Model
{
    protected $table = 'kategorimap_satusehat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kategori',
        'jenis_pemeriksaan',
        'kode_kategori',
        'display_kategori',
    ];
    //
}
