<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name', 'status'];

    // Un género tiene muchos álbumes
    public function albums()
    {
        return $this->hasMany(Album::class);
    }
}