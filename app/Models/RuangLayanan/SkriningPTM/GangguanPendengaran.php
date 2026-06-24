<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;
use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;

class GangguanPendengaran extends Model
{
    protected $table = 'simpus_gangguan_pendengaran';
    protected $primaryKey = 'id';
    protected $fillable = [
        'skriningID',
        'tuli_kiri',
        'tuli_kanan',
        'omsk_kiri',
        'omsk_kanan',
        'serumen_kiri',
        'serumen_kanan',
        'presbi_kiri',
        'presbi_kanan',
        'bisik_kiri',
        'bisik_kanan',
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusSkriningPTM::class);
    }
    //
}
