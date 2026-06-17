<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;

class SimpusHipertensi extends Model
{
    protected $table = 'simpus_hipertensi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'sistolik',
        'tekanan_diastolik',
        'kategori_tekanan_darah',
        'suhu',
        'nadi',
        'pernapasan',
        'condition_id',
        'sent_at',
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusSkriningPTM::class);
    }
    //
}
