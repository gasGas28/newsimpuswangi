<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusPemeriksaanPTM;

class SimpusDiabetes extends Model
{
    protected $table = 'simpus_diabetes';
    protected $primariKey = 'id';

    protected $fillable = [
        'pemeriksaan_ptm_id',
        'gula_darah_puasa',
        'gula_darah_2_jam_pp',
        'gula_darah_sewaktu',
        'hba1c',
        'kategori_gula_darah_puasa',
        'kategori_gula_darah_2_jam_pp',
        'kategori_gula_darah_sewaktu',
        'kategori_hba1c',
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusPemeriksaanPTM::class);
    }
    //
}
