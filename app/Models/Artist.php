<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'status'];

    // Accessor para obtener 'active' booleano a partir de 'status'
    public function getActiveAttribute()
    {
        return $this->status === 'y';
    }

    // Mutator para asignar 'status' desde booleano (opcional)
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
