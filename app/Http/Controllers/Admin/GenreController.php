<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $query = Genre::withCount('albums')->orderBy('id');

        // Search: by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $genres = $query->get();

        return view('admin.genres.index', compact('genres'));
    }

    // Show
    public function show(Genre $genre)
    {
        $genre->load(['albums' => function ($q) {
            $q->withCount('tracks');
        }]);

        return view('admin.genres.show', compact('genre'));
    }

    // Edit: name or status
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    // Update
    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'status' => 'required|in:y,n',
        ]);

        $genre->update($data);

        // Deactivate/activate albums if deactivate/activate genre
        if ($data['status'] === 'n') {
            $genre->albums()->update(['status' => 'n']);
        }

        if ($data['status'] === 'y') {
            $genre->albums()->update(['status' => 'y']);
        }

        return redirect()
            ->route('admin.genres.index')
            ->with('success', 'Genre updated');
    }
}