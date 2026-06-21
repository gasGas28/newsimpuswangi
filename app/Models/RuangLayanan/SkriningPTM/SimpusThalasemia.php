<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusThalasemia extends Model
{
    protected $table = 'simpus_thalasemia';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'hemoglobin',
        'mcv',
        'mch',
        'eritrosit',
        'rdw',
    ];
    //
}
