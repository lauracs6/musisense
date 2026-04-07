<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de administración (solo accesibles a admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Listado de usuarios
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');

    // Editar usuario
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');

    // Actualizar usuario
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');

    // Activar / Desactivar usuario
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
});

require __DIR__.'/auth.php';