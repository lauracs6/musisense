<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'active'];

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'album_artist')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
