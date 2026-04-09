<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $table = 'tracks';

    protected $fillable = [
        'title',
        'artist',
        'album_id',
        'track_number',
        'duration',
        'popularity',
        'file_path',
    ];

    protected $casts = [
        'track_number' => 'integer',
        'duration' => 'integer',
        'popularity' => 'integer',
        'album_id' => 'integer',
        'file_path' => 'string',
    ];

    /**
     * Relación: una canción pertenece a un álbum.
     */
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}