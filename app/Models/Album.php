<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['title', 'release_date', 'album_type'];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)->withPivot('role');
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
