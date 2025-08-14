<?php

namespace App\Models\Filter;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'simpus_pasien';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    public function lokets()
    {
        return $this->hasMany(Loket::class, 'pasienId', 'ID');
    }
    //
}
