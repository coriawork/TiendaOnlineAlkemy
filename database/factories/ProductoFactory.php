<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->sentence(),
            'precio' => fake()->randomFloat(2, 10, 100000),
            'stock' => fake()->numberBetween(1, 50),
            'imagen' => null,
        ];
    }

    public function sinStock(): static
    {
        return $this->state(fn(): array => [
            'stock' => 0,
        ]);
    }

    public function conStock(int $stock): static
    {
        return $this->state(fn(): array => [
            'stock' => $stock,
        ]);
    }
}
