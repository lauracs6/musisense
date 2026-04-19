<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Playlist;
use App\Models\Track;

class PlaylistTrackSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Playlist::all() as $playlist) {

            $tracks = Track::inRandomOrder()
                ->limit(10)
                ->pluck('id');

            $tracks = Track::inRandomOrder()->take(10)->get();

            foreach ($tracks as $index => $track) {
                $playlist->tracks()->attach($track->id, [
                    'position' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
