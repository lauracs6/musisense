<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name', 'status'];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }
}
