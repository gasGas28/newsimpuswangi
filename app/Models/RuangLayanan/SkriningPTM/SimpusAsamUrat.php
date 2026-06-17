<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
class SimpusAsamUrat extends Model
{
    protected $table = 'simpus_asam_urat';
    protected $primaryKey = 'id';

    protected $fillable = [
        'skriningID',
        'asam_urat',
        'kategori_asam_urat',
        'observation_id',
        'condition_id',
        'sent_at',
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusSkriningPTM::class);
    }
    //
}
