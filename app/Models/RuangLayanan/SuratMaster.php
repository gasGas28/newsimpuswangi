<?php

namespace App\Models\RuangLayanan;

use Illuminate\Database\Eloquent\Model;

class SuratMaster extends Model
{
    protected $table = 'surat_master';
    protected $primaryKey = 'D_JNS_SURAT';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'SURAT',
    ];
}
