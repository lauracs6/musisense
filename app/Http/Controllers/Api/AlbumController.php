<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;

class AlbumController extends Controller
{
    /**
     * Listado de álbumes
     */
    public function index()
    {
        $albums = Album::with('artists')
            ->orderBy('year', 'asc')
            ->get();

        return AlbumResource::collection($albums);
    }

    /**
     * Mostrar un álbum con sus canciones
     */
    public function show(Album $album)
    {
        $album->load([
            'tracks' => function ($query) {
                $query->orderBy('track_number');
            },
            'artists'
        ]);

        return new AlbumResource($album);
    }
}