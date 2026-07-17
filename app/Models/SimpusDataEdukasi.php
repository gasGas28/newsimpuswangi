<?php

namespace App\Models;

use App\Models\RuangLayanan\SkriningPTM\SimpusSkriningPTM;
use Illuminate\Database\Eloquent\Model;

class SimpusDataEdukasi extends Model
{
    protected $table = 'simpus_data_edukasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pelayananID',
        'skriningID',
        'kode_snomed',
        'keterangan',
        'procedureId',
    ];

    public function pemeriksaanPTM()
    {
        return $this->belongsTo(SimpusSkriningPTM::class);
    }
}
