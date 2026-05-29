<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        $this->command->call('music:import', [
            'folder' => 'C:\Users\Laura\Music'
        ]);

        $this->call(PlaylistSeeder::class);
        $this->call(PlaylistTrackSeeder::class);
    }
}
