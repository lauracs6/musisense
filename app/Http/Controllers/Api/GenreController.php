<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    /**
     * Listado de géneros con sus álbumes
     */
    public function index(): JsonResponse
    {
        $genres = Genre::with(['albums' => function ($query) {
                $query->orderBy('title');
            }])
            ->where('status', 'y') 
            ->orderBy('name')
            ->get();

        return response()->json($genres);
    }

    /**
     * Mostrar un género concreto con sus álbumes
     */
    public function show(Genre $genre): JsonResponse
    {
        // opcional: evitar géneros inactivos
        if ($genre->status !== 'y') {
            return response()->json([
                'message' => 'Género no disponible'
            ], 404);
        }

        $genre->load(['albums' => function ($query) {
            $query->orderBy('title');
        }]);

        return response()->json($genre);
    }
}