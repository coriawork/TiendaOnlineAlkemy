<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'correo' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password123'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Usuario $usuario): void {
            $usuario->carrito()->firstOrCreate();
        });
    }

    public function conContraseña(string $contraseña): static
    {
        return $this->state(fn(): array => [
            'password' => Hash::make($contraseña),
        ]);
    }
}
