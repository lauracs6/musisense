<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Playlist;
use App\Models\Track;

class PlaylistTrackSeeder extends Seeder
{
    public function run(): void
    {
        $totalTracks = Track::count();

        if ($totalTracks === 0) {
            return;
        }

        $tracksToTake = min(10, $totalTracks);

        foreach (Playlist::all() as $playlist) {

            $randomTrackIds = Track::inRandomOrder()
                ->take($tracksToTake)
                ->pluck('id')
                ->unique()
                ->toArray();

            $syncData = [];
            foreach ($randomTrackIds as $index => $trackId) {
                $syncData[$trackId] = [
                    'position'   => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($syncData)) {
                $playlist->tracks()->sync($syncData);
            }
        }
    }
}
