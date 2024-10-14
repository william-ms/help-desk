<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->create([
            'name' => "Administrador MedMais",
            'email' => "admin@medmaistickets.com.br",
            'first_login' => 1,
            'email_verified_at' => now(),
        ]);
    }

    public function user()
    {
        return $this->create([
            'name' => "Usuário MedMais",
            'email' => "user@medmaistickets.com.br",
            'email_verified_at' => now(),
        ]);
    }
}
