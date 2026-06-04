<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    // Index de playlists del usuario autenticado
    public function index()
    {
        $user = Auth::user();

        $playlists = $user->playlists()
            ->get()
            ->map(function ($playlist) use ($user) {
                return [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'user' => $user->username ?? $user->name,
                    'description' => $playlist->description,
                    'tracks_count' => $playlist->tracks()->count(), // Conteo real físico en la BD
                ];
            });

        return response()->json([
            'data' => $playlists
        ]);
    }

    // Show de playlist de usuario autenticado
    public function show(Playlist $playlist)
    {
        $playlist->load(['user', 'tracks' => function ($q) {
            $q->orderBy('playlist_track.position');
        }]);

        return response()->json([
            'data' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'description' => $playlist->description,
                'status' => $playlist->status,
                'user' => $playlist->user->username, // ← cambio aquí
                'tracks' => $playlist->tracks->map(function ($track) {
                    return [
                        'id' => $track->id,
                        'title' => $track->title,
                        'artist' => $track->artist,
                        'duration' => $track->duration,
                        'status' => $track->status,
                        'album' => [
                            'id' => $track->album->id ?? null,
                            'title' => $track->album->title ?? null,
                            'cover' => $track->album->cover ? asset('storage/' . $track->album->cover) : null,
                            'status' => $track->album->status ?? null,
                            'artist_active' => optional($track->album->mainArtist())->active ?? false,
                        ],
                    ];
                }),
            ]
        ]);
    }

    // Store de playlist de usuario autenticado
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist = Auth::user()->playlists()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'status' => 'y',
        ]);

        $playlist->load('user');

        return response()->json([
            'data' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'user' => $playlist->user->username, // también aquí
                'tracks_count' => 0,
            ]
        ], 201);
    }

    // Función para agregar una canción a la playlist
    public function addTrack(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'track_id' => 'required|exists:tracks,id'
        ]);

        $lastPosition = $playlist->tracks()->max('position') ?? 0;

        $playlist->tracks()->syncWithoutDetaching([
            $request->track_id => ['position' => $lastPosition + 1]
        ]);

        return response()->json(['message' => 'Track added to playlist']);
    }

    // Función para eliminar una canción de la playlist
    public function removeTrack(Playlist $playlist, Track $track)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $playlist->tracks()->detach($track->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Track removed from playlist'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Actualizar playlist de usuario autenticado
    public function update(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $playlist->update($data);

        return response()->json([
            'message' => 'Playlist updated successfully',
            'data' => $playlist
        ]);
    }

    // Función para reordenar las canciones de la playlist
    public function reorderTracks(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate(['track_ids' => 'required|array']);

        foreach ($request->track_ids as $index => $trackId) {
            $playlist->tracks()->updateExistingPivot($trackId, ['position' => $index + 1]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    // Eliminar playlist de usuario autenticado
    public function destroy(Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $playlist->tracks()->detach();
        $playlist->delete();

        return response()->json(['message' => 'Deleted playlist successfully']);
    }
}
