<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusKolorektal extends Model
{
    protected $table = 'simpus_kolorektal';
    protected $primaryKey = 'id';
    protected $fillable = [
        'skriningID',
        'question1',
        'question2',
        'result',
        'colok_dbr',
        'darah_samar',
    ];
    //
}
