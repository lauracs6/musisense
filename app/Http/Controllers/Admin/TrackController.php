<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Album;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $query = Track::with(['album', 'album.artists']);

        // Search: by title, artist or album
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('album', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%$search%");
                  })
                  ->orWhereHas('album.artists', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%$search%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tracks = $query
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.tracks.index', compact('tracks'));
    }

    // Show
    public function show(Track $track)
    {
        $track->load(['album', 'album.artists']);

        return view('admin.tracks.show', [
            'track' => $track->fresh()
        ]);
    }

    // Edit: name or status
    public function edit(Track $track)
    {
        $albums = Album::all(); // opcional si quieres mostrarlo como readonly

        return view('admin.tracks.edit', compact('track', 'albums'));
    }
    
    // Update
    public function update(Request $request, Track $track)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:y,n',
        ]);

        $album = Album::where('id', $track->album_id)->first();

        // Deactivate/activate track if deactivate/activate album
        if ($data['status'] === 'y' && $album && $album->status === 'n') {
            return back()
                ->with('error', "You can't activate a track when its album is deactivated. Please activate album first.")
                ->withInput();
        }

        $track->update($data);

        return redirect()
            ->route('admin.tracks.show', $track->id);
    }
}