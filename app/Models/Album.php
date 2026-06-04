<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title', 'year', 'cover', 'type', 'status', 'genre_id'
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'album_artist')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function mainArtist()
    {
        return $this->artists()->wherePivot('role', 'main')->first();
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
