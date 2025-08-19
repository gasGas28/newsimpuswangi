<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterObat extends Model
{
     use HasFactory;

    protected $table = 'master_obat'; // nama tabel

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'satuan_obat',
        'stok'
    ];
}
