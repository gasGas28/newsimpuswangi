<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;

class GangguanPenglihatan extends Model
{
    protected $table = 'simpus_gangguan_penglihatan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'visus_od',
        'visus_os',
        'pinhole_od',
        'pinhole_os',
        'anterior_od',
        'anterior_os',
        'shadow_os',
        'shadow_od',
        'refleks_od',
        'refleks_os',
        'glaukoma_os',
        'glaukoma_od',
        'retinopati_os',
        'retinopati_od',
    ];
    //
    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusSkriningPTM::class);
    }
}
