<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Playlist;
use App\Models\Track;

class PlaylistTrackSeeder extends Seeder
{
    public function run(): void
    {
        // Contamos cuántas canciones reales se guardaron desde el JSON en el paso anterior
        $totalTracks = Track::count();

        // Si el JSON no insertó canciones por algún error de lectura, evitamos que rompa el comando
        if ($totalTracks === 0) {
            return;
        }

        // Determinamos cuántas canciones podemos meter por playlist (máximo 10, o el total si hay menos de 10)
        $tracksToTake = min(10, $totalTracks);

        foreach (Playlist::all() as $playlist) {

            // Obtenemos los IDs aleatorios asegurando que sean únicos
            $randomTrackIds = Track::inRandomOrder()
                ->take($tracksToTake)
                ->pluck('id')
                ->unique()
                ->toArray();

            // Preparamos la inserción masiva (Bulk Insert) con posiciones secuenciales corregidas
            $syncData = [];
            foreach ($randomTrackIds as $index => $trackId) {
                $syncData[$trackId] = [
                    'position'   => $index + 1, // 1, 2, 3... secuencial sin repetirse el 1
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // ⚡ CORREGIDO: Usamos sync() en lugar de attach() para que procese el mapeo correctamente
            if (!empty($syncData)) {
                $playlist->tracks()->sync($syncData);
            }
        }
    }
}
