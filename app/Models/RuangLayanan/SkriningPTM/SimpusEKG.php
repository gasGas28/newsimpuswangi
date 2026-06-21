<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusEKG extends Model
{
    protected $table = 'simpus_ekg';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'hr',
        'irama',
        'axis',
        'segmen_st',
        'qrs',
        'kesimpulan_ekg',
    ];
    //
}
