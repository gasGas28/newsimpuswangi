<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusKolorektal extends Model
{
    protected $table = 'simpus_kolorektal';
    protected $primaryKey = 'id';
    protected $fillable = [
        'skriningID',
        'kuesioner1',
        'kuesioner2',
        'hasil_kuesioner',
        'colok_dbr',
        'darah_samar',
    ];
    //
}
