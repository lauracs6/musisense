<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::with(['albums' => function ($q) {
            $q->select('id', 'title', 'genre_id', 'status', 'year');
        }])->orderBy('id');

        // búsqueda
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // filtro estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $genres = $query->get();

        return view('admin.genres.index', compact('genres'));
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'status' => 'required|in:y,n',
        ]);

        $genre->update($data);

        // cascada al desactivar
        if ($data['status'] === 'n') {
            $genre->albums()->update(['status' => 'n']);
        }

        return redirect()
            ->route('admin.genres.index')
            ->with('success', 'Genre updated');
    }    
}