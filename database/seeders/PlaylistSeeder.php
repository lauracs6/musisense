<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Playlist;

class PlaylistSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/playlists.json');

        if (!file_exists($path)) {
            $this->command->error("JSON file not found: $path");
            return;
        }

        $json = json_decode(file_get_contents($path), true);

        foreach ($json['playlists'] as $item) {
            Playlist::create([
                'name' => $item['name'],
                'user_id' => $item['user_id'],
                'description' => $item['description'] ?? null,
                'status' => $item['status'] ?? 'y',
            ]);
        }

        $this->command->info('Playlists imported from JSON');
    }
}