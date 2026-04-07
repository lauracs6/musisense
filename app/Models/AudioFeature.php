<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioFeature extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'track_id';
    public $incrementing = false;

    protected $fillable = [
        'track_id',
        'danceability',
        'energy',
        'tempo',
        'loudness',
        'valence',
        'acousticness',
        'instrumentalness'
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
