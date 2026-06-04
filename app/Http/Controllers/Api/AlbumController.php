<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::with(['artists', 'genre'])
            ->orderBy('year', 'asc')
            ->get();

        return AlbumResource::collection($albums);
    }

    public function show(Album $album)
    {
        $album->load([
            'tracks' => function ($query) {
                $query->orderBy('track_number');
            },
            'artists',
            'genre',
        ]);

        return new AlbumResource($album);
    }
}
