<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;
use App\Models\RuangLayanan\SkriningPTM\SimpusDiabetes;
use App\Models\RuangLayanan\SkriningPTM\SimpusObesitas;
use App\Models\RuangLayanan\SkriningPTM\SimpusProfilLipid;
use App\Models\RuangLayanan\SkriningPTM\SimpusAsamUrat;

class SimpusSkriningPTM extends Model
{
    protected $table = 'simpus_skrining_ptm';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idSkrining',
        'idPelayanan',
        'encounter_id',
        'patient_id',
        'status',
        'sync_status',
        'sync_time',
        'error_message'
    ];

    public function hipertensi()
    {
        return $this->hasOne(SimpusHipertensi::class, 'idSkrining');
    }

    public function diabetes()
    {
        return $this->hasOne(SimpusDiabetes::class, 'idSkrining');
    }

    public function obesitas()
    {
        return $this->hasOne(SimpusObesitas::class, 'idSkrining');
    }

    public function profilLipid()
    {
        return $this->hasOne(SimpusProfilLipid::class, 'idSkrining');
    }

    public function asamUrat()
    {
        return $this->hasOne(SimpusAsamUrat::class, 'idSkrining');
    }
    //
}
