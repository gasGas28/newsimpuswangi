<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusHipertensi;
use App\Models\RuangLayanan\SkriningPTM\SimpusDiabetes;
use App\Models\RuangLayanan\SkriningPTM\SimpusObesitas;
use App\Models\RuangLayanan\SkriningPTM\SimpusProfilLipid;
use App\Models\RuangLayanan\SkriningPTM\SimpusAsamUrat;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;

class SimpusPemeriksaanPTM extends Model
{
    protected $table = 'simpus_pemeriksaan_ptm';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idSkrining',
    ];

    // SimpusPemeriksaanPTM.php

    public function hipertensi()
    {
        return $this->hasOne(SimpusHipertensi::class, 'pemeriksaan_ptm_id');
    }

    public function diabetes()
    {
        return $this->hasOne(SimpusDiabetes::class, 'pemeriksaan_ptm_id');
    }

    public function obesitas()
    {
        return $this->hasOne(SimpusObesitas::class, 'pemeriksaan_ptm_id');
    }

    public function profilLipid()
    {
        return $this->hasOne(SimpusProfilLipid::class, 'pemeriksaan_ptm_id');
    }

    public function asamUrat()
    {
        return $this->hasOne(SimpusAsamUrat::class, 'pemeriksaan_ptm_id');
    }

    public function skrining()
    {
        return $this->belongsTo(
            SimpusSkriningPTM::class,
            'idSkrining'
        );
    }
    //
}
