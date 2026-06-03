<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $query = Playlist::with('user')->withCount('tracks')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('username', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $playlists = $query->orderBy('user_id')->paginate(9)->withQueryString();
        return view('admin.playlists.index', compact('playlists'));
    }

    // Show
    public function show(Playlist $playlist)
    {
        $playlist->load(['user', 'tracks']);
        return view('admin.playlists.show', compact('playlist'));
    }

    // Edit: only name and status (no track order)
    public function edit(Playlist $playlist)
    {
        // Cargamos las canciones con su posición actual, solo para mostrarlas (sin interfaz de orden)
        $playlist->load(['tracks' => function ($q) {
            $q->orderBy('playlist_track.position');
        }]);
        return view('admin.playlists.edit', compact('playlist'));
    }

    // Update: solo nombre y estado (sin gestión de orden de canciones)
    public function update(Request $request, Playlist $playlist)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:y,n',
        ]);

        $playlist->load('user');

        // No se puede activar una playlist si su usuario está desactivado
        if ($data['status'] === 'y' && $playlist->user->status === 'n') {
            return back()
                ->with('error', "You can't activate a playlist when its user is deactivated. Please activate the user first.")
                ->withInput();
        }

        $playlist->update([
            'name'   => $data['name'],
            'status' => $data['status'],
        ]);

        // 🔥 Eliminada la sincronización de tracks y orden (drag order)
        // El orden solo lo maneja el frontend del usuario mediante la API correspondiente.

        return redirect()
            ->route('admin.playlists.show', $playlist)
            ->with('success', 'Playlist updated successfully.');
    }
}
