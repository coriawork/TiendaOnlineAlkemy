<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Categoria::factory()->count(3)->create();

        $categorias->each(function (Categoria $categoria): void {
            Producto::factory()
                ->count(3)
                ->for($categoria, 'categoria')
                ->create();
        });

        Usuario::factory()->count(3)->create();
    }
}
