<?php

namespace App\Models\Filter;

use Illuminate\Database\Eloquent\Model;

class Anamnesa extends Model
{
    protected $table = 'simpus_anamnesa';
    protected $primaryKey = 'idAnamnesa';
    public $timestamps = false;

    public function loket()
    {
        return $this->belongsTo(Loket::class, 'loketId', 'idLoket');
    }
    //
}
