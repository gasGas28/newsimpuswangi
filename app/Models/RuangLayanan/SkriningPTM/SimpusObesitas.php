<?php

namespace App\Models\RuangLayanan\SkriningPTM;


use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusPemeriksaanPTM;
class SimpusObesitas extends Model
{
    protected $table = 'simpus_obesitas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pemeriksaan_ptm_id',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'lingkar_pinggang',
        'interpretasi_ptm',
        'interpretasi_lp',
    ];

    public function pemeriksaanPTM(){
        return $this->belongsTo(SimpusPemeriksaanPTM::class);
    }
    //
}
