<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusPelayananPTM;
class SimpusAsamUrat extends Model
{
    protected $table = 'simpus_asam_urat';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pemeriksaan_ptm_id',
        'asam_urat',
        'kategori_asam_urat',
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusPelayananPTM::class);
    }
    //
}
