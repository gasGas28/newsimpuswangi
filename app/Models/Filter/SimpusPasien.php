<?php

namespace App\Models\Filter;

use Illuminate\Database\Eloquent\Model;

class SimpusPasien extends Model
{
    protected $table = 'simpus_pasien';
    protected $primaryKey = 'ID';

    public function loket(){
        return $this->hasMany(SimpusLoket::class, 'pasienId', 'ID');
    }
    //
}
