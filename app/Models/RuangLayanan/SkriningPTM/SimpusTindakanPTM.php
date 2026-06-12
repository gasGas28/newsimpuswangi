<?php

namespace App\Models\RuangLayanan\SkriningPTM;

use Illuminate\Database\Eloquent\Model;

class SimpusTindakanPTM extends Model
{
    protected $table = 'simpus_tindakan_ptm';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idSkrining',
        'kode_tindakan',
        'nama_tindakan',
        'keterangan'
     ];
    //  
}
