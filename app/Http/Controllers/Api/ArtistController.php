<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    // Index
    public function index()
    {
        $artists = Artist::orderBy('name')->get();
        return ArtistResource::collection($artists);
    }

    // Show
    public function show(Artist $artist)    {

        $artist->load('albums');
        return new ArtistResource($artist);
    }
}
