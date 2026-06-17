<?php

namespace App\Models\RuangLayanan\SkriningPTM;


use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
class SimpusObesitas extends Model
{
    protected $table = 'simpus_obesitas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'lingkar_pinggang',
        'interpretasi_ptm',
        'interpretasi_lp',
        'observation_id',
        'condition_imt_id',
        'condition_lp_id',
        'sent_at',
    ];

    public function pemeriksaanPTM(){
        return $this->belongsTo(SimpusSkriningPTM::class);
    }
    //
}
