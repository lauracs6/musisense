<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title', 'year', 'cover', 'type', 'status'
    ];

    // Relación muchos a muchos con Artist
    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'album_artist')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Método auxiliar para obtener el artista principal
    public function mainArtist()
    {
        return $this->artists()->wherePivot('role', 'main')->first();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'album_genre')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Método auxiliar para obtener el género principal
    public function mainGenre()
    {
        return $this->genres()->wherePivot('role', 'main')->first();
    }
}
