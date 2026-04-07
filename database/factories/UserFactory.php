<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        // Asegurarse de que exista un rol 'user'
        $userRole = Role::firstOrCreate(['name' => 'user']);

        return [
            'username' => $this->faker->unique()->userName(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // contraseña común para todos los falsos
            'role_id' => $userRole->id,
            'country' => $this->faker->country(),
            'birth_date' => $this->faker->date('Y-m-d', '2005-01-01'),
            'active' => 1,
        ];
    }
}