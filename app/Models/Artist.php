<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'status'];

    public function getActiveAttribute()
    {
        return $this->status === 'y';
    }

    public function setActiveAttribute($value)
    {
        $this->status = $value ? 'y' : 'n';
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'album_artist')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
