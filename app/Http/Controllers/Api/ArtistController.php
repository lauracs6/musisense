<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Listado de todos los artistas (activos e inactivos)
     */
    public function index()
    {
        $artists = Artist::orderBy('name')->get();
        return ArtistResource::collection($artists);
    }

    /**
     * Mostrar un artista con sus álbumes (opcional)
     */
    public function show(Artist $artist)
    {
        // Opcional: cargar álbumes si los necesitas en el frontend
        $artist->load('albums');
        return new ArtistResource($artist);
    }
}
