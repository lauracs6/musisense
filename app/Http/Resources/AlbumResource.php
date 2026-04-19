<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'cover' => $this->cover,
            'type' => $this->type,

            'artist' => optional($this->mainArtist())->name,

            'tracks' => $this->whenLoaded('tracks', function () {
                return $this->tracks->map(function ($track) {
                    return [
                        'id' => $track->id,
                        'title' => $track->title,
                        'track_number' => $track->track_number,
                        'duration' => $track->duration,
                    ];
                })->sortBy('track_number')->values();
            }),
        ];
    }
}