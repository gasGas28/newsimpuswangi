<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusPemeriksaanPTM;
class SimpusProfilLipid extends Model
{
    protected $table = 'simpus_profil_lipid';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pemeriksaan_ptm_id',
        'kolesterol_total',
        'hdl',
        'ldl',
        'trigliserida',
        'interpretasi_kolesterol_total',
        'interpretasi_hdl',
        'interpretasi_ldl',
        'interpretasi_trigliserida'
    ];

    public function pemeriksaanPTM(){
        return $this->belongsTo(SimpusPemeriksaanPTM::class);
    }

}
