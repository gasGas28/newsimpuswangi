<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SatuSehatLog extends Model
{
    protected $table = 'satu_sehat_log';
    public $timestamps = false;

    protected $fillable = [
        'idPelayanan',
        'tanggal',
        'puskId',
        'resource',
        'idResponse',
        'method',
        'kirim',
        'terima',
        'userId',
    ];
    //
}
