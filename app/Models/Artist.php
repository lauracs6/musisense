<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'country', 'debut_year'];

    public function albums()
    {
        return $this->belongsToMany(Album::class)->withPivot('role');
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class)->withPivot('role');
    }
}
