<?php

namespace App\Models\RuangLayanan;

use Illuminate\Database\Eloquent\Model;

class SimpusPasien extends Model
{

    protected $table = 'simpus_pasien';
    protected $primaryKey = 'id';
    public function SimpusLoket()
    {
        return $this->hasMany(SimpusLoket::class, 'pasienId', 'ID');
    }

    public function SetupKel()
    {
        return $this->belongsTo(SetupKel::class, 'NO_KEL', 'NO_KEL');
    }
     public function SetupKec()
    {
        return $this->belongsTo(SetupKec::class, 'NO_KEC', 'NO_KEC');
    }
     public function SetupKab()
    {
        return $this->belongsTo(SetupKab::class, 'NO_KAB', 'NO_KAB');
    }
     public function SetupProp()
    {
        return $this->belongsTo(SetupProp::class, 'NO_PROP', 'NO_PROP');
    }
}
