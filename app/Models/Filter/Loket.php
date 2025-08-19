<?php

namespace App\Models\Filter;

use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    protected $table = 'simpus_loket';
    protected $primaryKey = 'idLoket';

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasienId', 'ID');
    }

    public function anamnesa()
    {
        return $this->hasOne(Anamnesa::class, 'loketId', 'idLoket');
    }

    // public function obat()
    // {
    //     return $this->hasMany(Obat::class, 'loketId', 'idLoket');
    // }

    // public function kunjungan()
    // {
    //     return $this->hasOne(Kunjungan::class, 'pasien_id', 'pasienId');
    // }
}

