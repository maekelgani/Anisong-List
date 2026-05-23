<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestRatingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_guest',
        'tipe_rate',
        'limit_top',
        'rata_rata_score',
        'komentar_guest',
    ];

    public function details()
    {
        return $this->hasMany(GuestRatingDetail::class, 'session_id');
    }
}
