<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'anime_name',
        'judul_lagu',
        'penyanyi',
        'tipe',
        'score',
        'link_video',
        'tahun_rilis',
        'peringkat',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function guestRatingDetails()
    {
        return $this->hasMany(GuestRatingDetail::class);
    }
}
