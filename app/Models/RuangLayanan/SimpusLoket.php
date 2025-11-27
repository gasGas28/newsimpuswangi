<?php

namespace App\Models\RuangLayanan;

use Illuminate\Database\Eloquent\Model;

class SimpusLoket extends Model
{
    
    protected $table = 'simpus_loket';
    protected $primaryKey = 'idLoket';

     public function SimpusPasien(){
        return $this->belongsTo(SimpusPasien::class, 'pasienId', 'ID');
    }
}
