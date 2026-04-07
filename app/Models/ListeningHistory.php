<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'track_id',
        'played_at',
        'device',
        'ms_played'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
