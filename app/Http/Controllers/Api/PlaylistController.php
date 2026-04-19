<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;

class PlaylistController extends Controller
{
    // 🔹 GET /api/playlists
    public function index()
    {
        $playlists = Playlist::with('user')
            ->withCount('tracks')
            ->get()
            ->map(function ($playlist) {
                return [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'user' => $playlist->user->username ?? null,
                    'tracks_count' => $playlist->tracks_count,
                ];
            });

        return response()->json([
            'data' => $playlists
        ]);
    }

    // 🔹 GET /api/playlists/{id}
    public function show(Playlist $playlist)
    {
        $playlist->load(['user', 'tracks']);

        $tracks = $playlist->tracks
            ->sortBy('pivot.position') 
            ->values()
            ->map(function ($track, $index) {
                return [
                    'position' => $track->pivot->position ?? ($index + 1),
                    'title' => $track->title,
                    'artist' => $track->artist,
                ];
            });

        return response()->json([
            'data' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'user' => $playlist->user->username ?? null,
                'tracks' => $tracks,
            ]
        ]);
    }
}