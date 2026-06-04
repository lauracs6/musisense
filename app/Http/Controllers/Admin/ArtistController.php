<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $query = Artist::withCount('albums');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $artists = $query->orderBy('name')->paginate(20);
        return view('admin.artists.index', compact('artists'));
    }

    public function show(Artist $artist)
    {
        $artist->load('albums.tracks');
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
            $albumIds = $artist->albums()->pluck('albums.id');
            \App\Models\Track::whereIn('album_id', $albumIds)
                ->where('artist', $oldName)
                ->update(['artist' => $newName]);
        }

        // Activar/desactivar álbumes donde este artista es principal
        $mainAlbums = $artist->albums()->wherePivot('role', 'main')->get();

        foreach ($mainAlbums as $album) {
            // Si se desactiva el artista, desactivamos el álbum
            if ($request->status === 'n') {
                $album->status = 'n';
            } else {
                // Si se reactiva el artista, el álbum también
                $album->status = 'y';
            }
            $album->save();
        }

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist updated successfully.');
    }

    public function toggle(Artist $artist)
    {
        $artist->status = $artist->status === 'y' ? 'n' : 'y';
        $artist->save();

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist status changed successfully.');
    }
}
