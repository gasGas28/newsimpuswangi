<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusKankerIva extends Model
{
    protected $table = 'simpus_kanker_iva';

    protected $fillable = [
        'skriningID',
        'inspekulo',
        'iva',
        'hpv_dna',
        'sadanis',
        'usg',
        'krioterapi',
        'thermal',
        'tca',
        'rujuk_serviks',
    ];

    protected $casts = [
        'krioterapi' => 'boolean',
        'thermal' => 'boolean',
        'tca' => 'boolean',
        'rujuk_serviks' => 'boolean',
    ];
    //
}
