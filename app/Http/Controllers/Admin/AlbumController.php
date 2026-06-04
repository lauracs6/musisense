<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Genre;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $query = Album::with(['artists', 'genre']);

        // Search: by title, artist or year
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('year', 'like', "%$search%")
                  ->orWhereHas('artists', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
    $query->where('type', $request->type);
}

        $albums = $query
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.albums.index', compact('albums'));
    }


    // Show
    public function show(Album $album)
    {
        $album->load(['artists', 'genre', 'tracks']);

        return view('admin.albums.show', compact('album'));
    }

    // Edit: all
    public function edit(Album $album)
    {
        $genres = Genre::all();

        return view('admin.albums.edit', compact('album', 'genres'));
    }

    // Update
    public function update(Request $request, Album $album)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer',
            'type' => 'required|string',
            'genre_id' => 'required|exists:genres,id',
            'status' => 'required|in:y,n',
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path;
        }

        $album->update($data);

        // Deactivate/activate tracks if deactivate/activate album
        if ($data['status'] === 'n') {
            $album->tracks()->update(['status' => 'n']);
        }

        if ($data['status'] === 'y') {
            $album->tracks()->update(['status' => 'y']);
        }

        return redirect()
            ->route('admin.albums.show', $album)
            ->with('success', 'Album updated');
    }
}
