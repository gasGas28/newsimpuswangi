<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusStatusPTM extends Model
{
    protected $table = 'simpus_status_ptm';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'cara_keluar',
        'kondisi_pasien',
        'jadwal_kontrol',
        'rujukan',
        'transportasi',
        'service_request_id'
    ];
    //
}
