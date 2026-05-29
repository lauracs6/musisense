<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Llena una playlist con 10 canciones aleatorias de forma permanente si está vacía.
     */
    private function autoPopulateIfEmpty(Playlist $playlist)
    {
        if ($playlist->tracks()->exists()) {
            return;
        }

        $randomTrackIds = Track::inRandomOrder()->take(10)->pluck('id')->toArray();

        if (!empty($randomTrackIds)) {
            $syncData = [];
            foreach ($randomTrackIds as $index => $trackId) {
                // Cada track ID tiene su propia posición secuencial (1, 2, 3...)
                $syncData[$trackId] = ['position' => $index + 1];
            }

            // sync() procesa el array asociativo manteniendo los datos individuales del pivote
            $playlist->tracks()->sync($syncData);
        }
    }


    // GET /api/playlists (Only current user playlists)
    public function index()
    {
        $user = Auth::user();

        $playlists = $user->playlists()
            ->get()
            ->map(function ($playlist) use ($user) {

                // Si por algún motivo quedó en 0 en la BD, la rellenamos al listar
                $this->autoPopulateIfEmpty($playlist);

                return [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'user' => $user->username ?? $user->name,
                    'description' => $playlist->description,
                    'tracks_count' => $playlist->tracks()->count(), // Conteo real directo de base de datos
                ];
            });

        return response()->json([
            'data' => $playlists
        ]);
    }

    // GET /api/playlists/{id}
    public function show(Playlist $playlist)
    {
        // Aseguramos que tenga canciones antes de cargar relaciones
        $this->autoPopulateIfEmpty($playlist);

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

    // POST /api/playlists (Create)
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

        // Rellenamos inmediatamente la playlist con 10 canciones al ser creada
        $this->autoPopulateIfEmpty($playlist);

        return response()->json([
            'data' => [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'tracks_count' => 10
            ]
        ], 201);
    }

    // POST /api/playlists/{playlist}/tracks (Add song)
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

    // DELETE /api/playlists/{playlist}/tracks/{track} (Remove song)
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

    // PUT /api/playlists/{playlist}
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

    // POST /api/playlists/{playlist}/reorder
    public function reorderTracks(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate(['track_ids' => 'required|array']);

        foreach ($request->track_ids as $index => $trackId) {
            $playlist->tracks()->updateExistingPivot($trackId, ['position' => $index + 1]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    // DELETE /api/playlists/{playlist}
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
