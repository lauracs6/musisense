<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // 2. Cargar usuarios desde JSON (todos con rol customer)
        $jsonPath = database_path('seeders/data/users.json');
        if (File::exists($jsonPath)) {
            $json = File::get($jsonPath);
            $data = json_decode($json, true);

            if (isset($data['users']) && !empty($data['users'])) {
                foreach ($data['users'] as $userData) {
                    User::updateOrCreate(
                        ['email' => $userData['email']],
                        [
                            'username' => $userData['username'],
                            'password' => Hash::make($userData['password']),
                            'role_id' => $customerRole->id,
                            'status' => 'y',
                            'email_verified_at' => null,
                        ]
                    );
                }
            }
        }

        // 3. Crear usuario administrador (si no existe)
        User::updateOrCreate(
            ['email' => 'admin@musisense.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'status' => 'y',
                'email_verified_at' => null,
            ]
        );
    }
}