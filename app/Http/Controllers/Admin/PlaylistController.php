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

        // Search: by name or username
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('username', 'like', "%$search%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $playlists = $query
            ->orderBy('user_id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.playlists.index', compact('playlists'));
    }

    // Show
    public function show(Playlist $playlist)
    {
        $playlist->load(['user', 'tracks']);

        return view('admin.playlists.show', compact('playlist'));
    }

    // Edit: name, status and track order
    public function edit(Playlist $playlist)
    {
        $playlist->load(['tracks' => function ($q) {
            $q->orderBy('playlist_track.position');
        }]);

        return view('admin.playlists.edit', compact('playlist'));
    }

    // Update
    public function update(Request $request, Playlist $playlist)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|in:y,n',
        'tracks' => 'array',
        'tracks.*' => 'exists:tracks,id',
    ]);
    
    $playlist->load('user');

    // Can't activate track if user is deactivated
    if ($data['status'] === 'y' && $playlist->user->status === 'n') {
        return back()
            ->with('error', "You can't activate a playlist when its user is deactivated. Please activate the user first.")
            ->withInput();
    }

    $playlist->update([
        'name' => $data['name'],
        'status' => $data['status'],
    ]);

    // Rracks + order
    if (isset($data['tracks'])) {
        $sync = [];

        foreach ($data['tracks'] as $index => $trackId) {
            $sync[$trackId] = ['position' => $index + 1];
        }

        $playlist->tracks()->sync($sync);
    }

    return redirect()
        ->route('admin.playlists.show', $playlist)
        ->with('success', 'Playlist updated');
}
}