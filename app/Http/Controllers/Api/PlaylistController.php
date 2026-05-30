<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    // GET /api/playlists (Muestra solo las playlists del usuario autenticado)
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

    // GET /api/playlists/{id} (Detalle de una playlist específica)
    public function show(Playlist $playlist)
    {
        $playlist->load(['user', 'tracks.album']);

        $tracks = $playlist->tracks
            ->sortBy('pivot.position')
            ->values()
            ->map(function ($track, $index) {
                return [
                    'id' => $track->id,
                    'title' => $track->title,
                    'artist' => $track->artist,
                    'duration' => (int) $track->duration,
                    'position' => $track->pivot->position ?? ($index + 1),
                    'album' => $track->album
                ];
            });

        return response()->json([
            'data' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'user' => $playlist->user->username ?? $playlist->user->name,
                'description' => $playlist->description,
                'tracks' => $tracks,
            ]
        ]);
    }

    // POST /api/playlists (Crear una nueva playlist desde el Front)
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

        return response()->json([
            'data' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'tracks_count' => 0 // 🚀 AQUÍ ESTÁ EL CAMBIO: Nace completamente vacía con 0 temas
            ]
        ], 201);
    }

    // POST /api/playlists/{playlist}/tracks (Añadir canción manual)
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

    // DELETE /api/playlists/{playlist}/tracks/{track} (Eliminar canción manual)
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

    // PUT /api/playlists/{playlist} (Editar datos)
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

    // POST /api/playlists/{playlist}/reorder (Reordenar canciones por Drag & Drop)
    public function reorderTracks(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate(['track_ids' => 'required|array']);

        foreach ($request->track_ids as $index => $trackId) {
            $playlist->tracks()->updateExistingPivot($trackId, ['position' => $index + 1]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    // DELETE /api/playlists/{playlist} (Borrar Playlist)
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
