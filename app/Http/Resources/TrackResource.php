<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'track_number' => $this->track_number,
            'duration' => $this->duration,            
            'file_url' => route('tracks.stream', ['track' => $this->id]),

            'album' => [
                'id' => $this->album->id,
                'title' => $this->album->title,
                'cover' => $this->album->cover ? asset('storage/' . $this->album->cover) : null,
            ],
        ];
    }
}