<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class KunjunganPTM extends Model
{
    protected $table = 'simpus_kunjungan_ptm';
    protected $primaryKey = 'id';
    //
    protected $fillable = [
        'idSkrining',
        'idPelayanan',
        'idLoket',
        'nik_pasien',
        'tanggal_skrining',
        'id_petugas',
        'fasyankes',
        'jenis_kunjungan',
        'keluhan_utama'
    ];
}
