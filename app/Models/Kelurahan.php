<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'setup_kel';
    protected $primaryKey = 'NO_KEL';
    public $timestamps = false;

    protected $fillable = [
        'NO_KEL',
        'NAMA_KEL',
        'NO_KEC'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'NO_KEC', 'NO_KEC');
    }
}
