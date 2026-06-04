<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    // Index
    public function index(): JsonResponse
    {
        $genres = Genre::with(['albums' => function ($query) {
                $query->orderBy('title');
            }])
            ->orderBy('name')
            ->get();

        return response()->json($genres);
    }

    // Show
    public function show(Genre $genre): JsonResponse
    {
        if ($genre->status !== 'y') {
            return response()->json([
                'message' => 'Genre not found.'
            ], 404);
        }

        $genre->load([
            'albums' => function ($query) {
                $query->orderBy('title');
            },
            'albums.artists'
        ]);

        return response()->json($genre);
    }
}
