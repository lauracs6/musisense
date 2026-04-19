<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Album;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    // =========================
    // INDEX + FILTROS
    // =========================
    public function index(Request $request)
    {
        $query = Track::with(['album', 'album.artists']);

        // SEARCH (title o artist o album)
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

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tracks = $query
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.tracks.index', compact('tracks'));
    }

    // =========================
    // SHOW
    // =========================
    public function show(Track $track)
    {
        $track->load(['album', 'album.artists']);

        return view('admin.tracks.show', compact('track'));
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Track $track)
    {
        $albums = Album::all();
        $artists = $albums->pluck('artists')->flatten()->unique('id');

        return view('admin.tracks.edit', compact('track', 'albums', 'artists'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, Track $track)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album_id' => 'required|exists:albums,id',
            'track_number' => 'nullable|integer',
            'duration' => 'nullable|integer',
            'status' => 'required|in:y,n',
        ]);

        $track->update($data);

        return redirect()
            ->route('admin.tracks.show', $track)
            ->with('success', 'Track updated');
    }
}