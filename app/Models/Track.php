<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'title',
        'album_id',
        'duration_ms',
        'track_number',
        'explicit',
        'popularity'
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class)->withPivot('role');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function audioFeatures()
    {
        return $this->hasOne(AudioFeature::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)
                    ->withPivot('position', 'added_at');
    }

    public function listeningHistory()
    {
        return $this->hasMany(ListeningHistory::class);
    }
}
