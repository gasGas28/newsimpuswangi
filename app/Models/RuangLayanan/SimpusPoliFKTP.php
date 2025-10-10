<?php

namespace App\Models\RuangLayanan;

use Illuminate\Database\Eloquent\Model;

class SimpusPoliFKTP extends Model
{
    protected $table = 'simpus_poli_fktp';
    protected $primaryKey = 'id';
    public function SimpusDataDiagnosa()
    {
        return $this->hasMany(SimpusDataDiagnosa::class, 'kdPoli', 'kdPoli');
    }
}
