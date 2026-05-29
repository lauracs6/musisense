<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Album;
use Illuminate\Http\Request;
use App\Http\Resources\TrackResource;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $term = $request->query('query');

        if (empty($term)) {
            return response()->json(['tracks' => [], 'albums' => []]);
        }

        $tracks = Track::where('title', 'LIKE', "%$term%")
            ->orWhere('artist', 'LIKE', "%$term%")
            ->with('album')
            ->limit(10)
            ->get();

        $albums = Album::where('title', 'LIKE', "%$term%")
            ->with('artists')
            ->limit(6)
            ->get();

        return response()->json([
            'tracks' => TrackResource::collection($tracks),
            'albums' => $albums->map(function($album) {
                return [
                    'id' => $album->id,
                    'title' => $album->title,
                    'cover_url' => $album->cover ? asset('storage/' . $album->cover) : null,
                    'artist' => $album->artists->first()->name ?? 'Unknown',
                ];
            }),
        ]);
    }
}