<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SatusehatSubmission extends Model
{
    use HasFactory;

    protected $table = 'satusehat_submissions';
    protected $guarded = [];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
