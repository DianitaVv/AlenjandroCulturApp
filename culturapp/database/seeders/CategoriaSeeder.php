<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            [
                'nombre' => 'Festividades',
                'descripcion' => 'Celebraciones y fiestas tradicionales de la región',
                'icono' => 'fas fa-calendar-alt'
            ],
            [
                'nombre' => 'Gastronomía',
                'descripcion' => 'Platillos típicos y recetas tradicionales',
                'icono' => 'fas fa-utensils'
            ],
            [
                'nombre' => 'Artesanías',
                'descripcion' => 'Trabajos manuales y técnicas artesanales tradicionales',
                'icono' => 'fas fa-hammer'
            ],
            [
                'nombre' => 'Música y Danza',
                'descripcion' => 'Expresiones musicales y dancísticas locales',
                'icono' => 'fas fa-music'
            ],
            [
                'nombre' => 'Leyendas y Tradiciones',
                'descripcion' => 'Historias, mitos y costumbres ancestrales',
                'icono' => 'fas fa-book-open'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}