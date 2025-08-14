<?php

namespace App\Models\Filter;

use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    protected $table = 'simpus_loket';
    protected $primaryKey = 'idLoket'; // sesuaikan dengan nama kolom PK
    public $timestamps = false; // kalau tidak ada kolom created_at & updated_at

    public function anamnesa()
    {
        return $this->hasOne(Anamnesa::class, 'loketId', 'idLoket');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasienId', 'ID');
    }
    //
}
