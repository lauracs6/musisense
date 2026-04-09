<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'status',
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Para autenticación
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * 🔗 RELACIONES
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function listeningHistory()
    {
        return $this->hasMany(ListeningHistory::class);
    }

    /**
     * Helpers
     */

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }
}