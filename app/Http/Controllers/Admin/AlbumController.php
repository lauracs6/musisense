<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Genre;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    // =========================
    // INDEX + FILTROS
    // =========================
    public function index(Request $request)
    {
        $query = Album::with(['artists', 'genre']);

        // SEARCH (title o artist)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('artists', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $albums = $query
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.albums.index', compact('albums'));
    }

    // =========================
    // SHOW
    // =========================
    public function show(Album $album)
    {
        $album->load(['artists', 'genre', 'tracks']);

        return view('admin.albums.show', compact('album'));
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Album $album)
    {
        $genres = Genre::all();

        return view('admin.albums.edit', compact('album', 'genres'));
    }

    // =========================
    // UPDATE
    // =========================
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

        // SUBIR NUEVA COVER
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path;
        }

        $album->update($data);

        return redirect()
            ->route('admin.albums.show', $album)
            ->with('success', 'Album updated');
    }
}