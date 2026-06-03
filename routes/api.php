<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\TrackController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\TrackStreamController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Resources\UserResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (solo login y registro) ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/tracks/{track}/stream', [TrackStreamController::class, 'stream'])->name('tracks.stream');

// --- PROTECTED ROUTES (Requiere autenticación y usuario activo) ---
Route::middleware(['auth:sanctum', 'active'])->group(function () {

    // Contenido principal
    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{genre}', [GenreController::class, 'show']);
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::get('/artists/{artist}', [ArtistController::class, 'show']);
    Route::get('/albums', [AlbumController::class, 'index']);
    Route::get('/albums/{album}', [AlbumController::class, 'show']);
    Route::get('/tracks', [TrackController::class, 'index']);
    Route::get('/tracks/{track}', [TrackController::class, 'show']);
    Route::get('/search', SearchController::class);


    // Playlist Routes
    Route::get('/playlists', [PlaylistController::class, 'index']);
    Route::post('/playlists', [PlaylistController::class, 'store']);
    Route::get('/playlists/{playlist}', [PlaylistController::class, 'show']);
    Route::post('/playlists/{playlist}/tracks', [PlaylistController::class, 'addTrack']);
    Route::delete('/playlists/{playlist}/tracks/{track}', [PlaylistController::class, 'removeTrack']);
    Route::put('/playlists/{playlist}', [PlaylistController::class, 'update']);
    Route::post('/playlists/{playlist}/reorder', [PlaylistController::class, 'reorderTracks']);
    Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy']);

    // User Profile
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user()->load('role'));
    });
    Route::put('/user', function (UserUpdateRequest $request) {
        return app(UserController::class)->update($request, $request->user());
    });
    Route::delete('/user', function (UserDestroyRequest $request) {
        return app(UserController::class)->destroy($request, $request->user());
    });
    Route::put('/user/password', function (UserPasswordUpdateRequest $request) {
        return app(UserController::class)->updatePassword($request);
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- ADMIN ROUTES ---
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});
