<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusSkriningPTM extends Model
{
    protected $table = 'simpus_skrining_ptm';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idSkrining',
        'idPelayanan',
        'idLoket',
        'status',
        'sync_status',
        'sync_time',
        'error_message'
     ];
    //
}
