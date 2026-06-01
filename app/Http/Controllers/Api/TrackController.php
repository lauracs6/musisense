<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrackResource;
use App\Models\Track;

class TrackController extends Controller
{
    /**
     * Listado de tracks
     */
    public function index()
    {
        $tracks = Track::with(['album', 'album.artists'])
            ->orderBy('track_number')
            ->get();

        return TrackResource::collection($tracks);
    }

    /**
     * Mostrar un track concreto
     */
    public function show(Track $track)
    {
        $track->load(['album', 'album.artists']);

        return new TrackResource($track);
    }
}
