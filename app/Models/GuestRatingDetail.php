<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestRatingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'song_id',
        'score_diberikan',
    ];

    public function session()
    {
        return $this->belongsTo(GuestRatingSession::class, 'session_id');
    }

    public function song()
    {
        return $this->belongsTo(Song::class, 'song_id');
    }
}
