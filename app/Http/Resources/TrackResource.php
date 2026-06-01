<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $album = $this->album;

        // Resolvemos el artista principal desde la relación ya cargada
        $mainArtist = null;
        if ($album) {
            if ($album->relationLoaded('artists')) {
                $mainArtist = $album->artists->first(function ($artist) {
                    return optional($artist->pivot)->role === 'main';
                });
            } else {
                $mainArtist = $album->mainArtist();
            }
        }

        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'artist'      => $this->artist,
            'track_number' => $this->track_number,
            'duration'    => $this->duration,
            'status'      => $this->status,

            // URL absoluta del stream (usando la ruta nombrada)
            'fileurl' => route('tracks.stream', ['track' => $this->id]),

            'album' => $album ? [
                'id'     => $album->id,
                'title'  => $album->title,
                'cover'  => $album->cover ? asset('storage/' . $album->cover) : null,
                'status' => $album->status,
                'artist_active' => $mainArtist ? (bool) $mainArtist->active : null,
            ] : null,
        ];
    }
}
