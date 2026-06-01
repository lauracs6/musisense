<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $mainArtist = $this->whenLoaded('artists', function () {
            return $this->artists->first(function ($artist) {
                return optional($artist->pivot)->role === 'main';
            });
        }, fn() => $this->mainArtist());

        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'year'   => $this->year,
            'cover'  => $this->cover ? asset('storage/' . $this->cover) : null,
            'type'   => $this->type,
            'status' => $this->status,

            'artist' => optional($mainArtist)->name,
            'artist_active' => $mainArtist ? $mainArtist->active : null,

            // ✅ Incluir información del género (si la relación está cargada)
            'genre' => $this->whenLoaded('genre', function() {
                return [
                    'id'     => $this->genre->id,
                    'name'   => $this->genre->name,
                    'status' => $this->genre->status,
                ];
            }),

            'artists' => $this->whenLoaded('artists', function () {
                return $this->artists->map(function ($artist) {
                    return [
                        'id'     => $artist->id,
                        'name'   => $artist->name,
                        'active' => (bool) $artist->active,
                    ];
                });
            }),

            'tracks' => $this->whenLoaded('tracks', function () {
                return $this->tracks->map(function ($track) {
                    return [
                        'id'           => $track->id,
                        'title'        => $track->title,
                        'artist'       => $track->artist,
                        'track_number' => $track->track_number,
                        'duration'     => $track->duration,
                        'status'       => $track->status,
                    ];
                })->sortBy('track_number')->values();
            }),
        ];
    }
}
