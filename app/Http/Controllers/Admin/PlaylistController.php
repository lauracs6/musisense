<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $query = Playlist::with('user')->withCount('tracks')->orderBy('id', 'asc');

        // SEARCH (nombre playlist o usuario)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('username', 'like', "%$search%");
                  });
            });
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $playlists = $query
            ->orderBy('user_id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.playlists.index', compact('playlists'));
    }

    // SHOW
    public function show(Playlist $playlist)
    {
        $playlist->load(['user', 'tracks']);

        return view('admin.playlists.show', compact('playlist'));
    }

    // EDIT (solo status)
    public function edit(Playlist $playlist)
    {
        return view('admin.playlists.edit', compact('playlist'));
    }

    // UPDATE (solo activar/desactivar)
    public function update(Request $request, Playlist $playlist)
    {
        $data = $request->validate([
            'status' => 'required|in:y,n',
        ]);

        // si usuario está inactivo, no permitir activar playlist
        if ($data['status'] === 'y' && $playlist->user->status === 'n') {
            return back()->with('error', 'Cannot activate playlist of inactive user');
        }

        $playlist->update($data);

        return redirect()
            ->route('admin.playlists.show', $playlist)
            ->with('success', 'Playlist updated');
    }
}