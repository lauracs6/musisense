<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// RUTAS PÚBLICAS
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/login', function (LoginRequest $request) {
    $request->authenticate();
    $user = Auth::user();
    $token = $user->createToken('api')->plainTextToken;
    return response()->json([
        'token' => $token,
        'user' => new UserResource($user->load('role')),
    ]);
});

// RUTAS PROTEGIDAS (Sanctum o API Key)
Route::middleware(['api.key'])->group(function () {

    // Perfil del usuario autenticado
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

    Route::post('/logout', function (Request $request) {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logout OK']);
    });

    // RUTAS DE ADMINISTRACIÓN
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});