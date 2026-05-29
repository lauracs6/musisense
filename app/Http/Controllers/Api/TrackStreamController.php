<?php

namespace App\Http\Controllers\Api;

use App\Models\Track;
use App\Http\Controllers\Controller;

class TrackStreamController extends Controller
{
    public function stream(Track $track)
    {
        $filePath = $track->file_path;

        // Si la ruta guardada ya empieza por C:\, la usamos tal cual
        if (str_starts_with($filePath, 'C:\\')) {
            $path = $filePath;
        } else {
            // Si no, buscamos en el storage de Laravel
            $path = storage_path('app/public/' . $filePath);
        }

        if (!file_exists($path)) {
            return response()->json([
                'error' => 'Archivo no encontrado',
                'path_intentado' => $path
            ], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'audio/mpeg', // O 'audio/x-m4a' si son .m4a
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
