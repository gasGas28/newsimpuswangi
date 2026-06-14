<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusPelayananPTM;

class SimpusHipertensi extends Model
{
    protected $table = 'simpus_hipertensi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pemeriksaan_ptm_id',
        'sistolik',
        'tekanan_diastolik',
        'kategori_tekanan_darah',
        'suhu',
        'nadi',
        'pernapasan'
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusPelayananPTM::class);
    }
    //
}
