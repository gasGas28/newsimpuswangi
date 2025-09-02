<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'setup_kec';
    protected $primaryKey = 'NO_KEC';
    public $timestamps = false;

    protected $fillable = [
        'NO_KEC',
        'NAMA_KEC'
    ];

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'NO_KEC', 'NO_KEC');
    }
}
