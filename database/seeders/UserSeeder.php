<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔑 Obtener roles una sola vez
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $userRoleId  = Role::where('name', 'user')->value('id');

        // 👑 Usuario admin
        User::create([
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role_id'  => $adminRoleId,
            'status'   => 'y',
        ]);

        // 📂 Cargar JSON
        $jsonData = File::get(database_path('seeders/data/users.json'));
        $data = json_decode($jsonData, true);

        foreach ($data['users'] as $user) {

            User::create([
                // Username único (puedes usar email o generar uno)
                'username' => strtolower($user['username'] ?? explode('@', $user['email'])[0]),

                // Email en minúsculas
                'email'    => strtolower($user['email']),

                // Password hasheada
                'password' => Hash::make($user['password']),

                // Rol por defecto
                'role_id'  => $userRoleId,

                // Estado activo
                'status'   => 'y',
            ]);
        }
    }
}