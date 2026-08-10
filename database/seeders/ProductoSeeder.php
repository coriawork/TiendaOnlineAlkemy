<?php

namespace Database\Seeders;

use App\Models\categoria;
use App\Models\producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Electrónica' => 'Dispositivos y accesorios tecnológicos.',
            'Hogar' => 'Artículos para casa y cocina.',
            'Deportes' => 'Productos para entrenamiento y actividad física.',
        ];

        foreach ($categorias as $nombre => $descripcion) {
            categoria::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion]
            );
        }

        $productos = [
            [
                'categoria' => 'Electrónica',
                'nombre' => 'Auriculares Bluetooth',
                'descripcion' => 'Auriculares inalámbricos con cancelación de ruido.',
                'precio' => 59.90,
                'stock' => 35,
                'imagen' => 'productos/auriculares-bluetooth.jpg',
            ],
            [
                'categoria' => 'Hogar',
                'nombre' => 'Licuadora 1.5L',
                'descripcion' => 'Licuadora de 5 velocidades con vaso de vidrio.',
                'precio' => 42.50,
                'stock' => 20,
                'imagen' => 'productos/licuadora-15l.jpg',
            ],
            [
                'categoria' => 'Deportes',
                'nombre' => 'Mancuernas Ajustables',
                'descripcion' => 'Par de mancuernas con ajuste de peso rápido.',
                'precio' => 89.00,
                'stock' => 12,
                'imagen' => 'productos/mancuernas-ajustables.jpg',
            ],
            [
                'categoria' => 'Electrónica',
                'nombre' => 'Teclado Mecánico',
                'descripcion' => 'Teclado mecánico RGB con switches azules.',
                'precio' => 74.99,
                'stock' => 18,
                'imagen' => 'productos/teclado-mecanico.jpg',
            ],
            [
                'categoria' => 'Hogar',
                'nombre' => 'Sartén Antiadherente',
                'descripcion' => 'Sartén de 28 cm con recubrimiento antiadherente.',
                'precio' => 24.90,
                'stock' => 50,
                'imagen' => null,
            ],
        ];

        foreach ($productos as $item) {
            $categoria = categoria::where('nombre', $item['categoria'])->first();

            if (! $categoria) {
                continue;
            }

            producto::updateOrCreate(
                ['nombre' => $item['nombre']],
                [
                    'categoria_id' => $categoria->id,
                    'descripcion' => $item['descripcion'],
                    'precio' => $item['precio'],
                    'stock' => $item['stock'],
                    'imagen' => $item['imagen'],
                ]
            );
        }
    }
}
