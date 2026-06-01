<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::orderBy('name')->paginate(20);
        return view('admin.artists.index', compact('artists'));
    }

    public function show(Artist $artist)
    {
        return view('admin.artists.show', compact('artist'));
    }

    public function edit(Artist $artist)
    {
        return view('admin.artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:artists,name,' . $artist->id,
            'status' => 'required|in:y,n',
        ]);

        $oldName = $artist->name;
        $newName = $request->name;

        $artist->update($request->only('name', 'status'));

        // Si el nombre cambió, actualizar todas las canciones asociadas
        if ($oldName !== $newName) {
            // Obtener todos los tracks de los álbumes de este artista
            $albumIds = $artist->albums()->pluck('albums.id');
            \App\Models\Track::whereIn('album_id', $albumIds)
                ->where('artist', $oldName)
                ->update(['artist' => $newName]);
        }

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artista actualizado correctamente.');
    }

    public function toggle(Artist $artist)
    {
        $artist->status = $artist->status === 'y' ? 'n' : 'y';
        $artist->save();

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist status changed successfully.');
    }
}
