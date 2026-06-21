<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusKankerParu extends Model
{
    protected $table = 'simpus_kanker_paru';
    protected $primaryKey = 'id';
    protected $fillable = [
        'skriningID',
        'kuesioner1',
        'kuesioner2',
        'kuesioner3',
        'kuesioner4',
        'kuesioner5',
        'kuesioner6',
        'kuesioner7',
        'hasil_kuesioner',
    ];
    //
}
