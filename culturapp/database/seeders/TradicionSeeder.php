<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tradicion;

class TradicionSeeder extends Seeder
{
    public function run()
    {
        $tradiciones = [
            [
                'titulo' => 'Día de Muertos en Hidalgo',
                'descripcion' => 'Celebración tradicional para honrar a los difuntos con ofrendas, flores de cempasúchil y pan de muerto.',
                'historia' => 'Esta tradición prehispánica se ha mantenido viva en Hidalgo por generaciones, combinando elementos indígenas con la tradición católica.',
                'ubicacion' => 'Tula de Allende, Hidalgo',
                'latitud' => 20.0530,
                'longitud' => -99.3427,
                'fecha_celebracion' => '2024-11-02',
                'categoria_id' => 1,
                'user_id' => 1,
                'likes' => 25
            ],
            [
                'titulo' => 'Barbacoa Hidalguense',
                'descripcion' => 'Platillo tradicional preparado en horno de tierra con pencas de maguey, típico de la región.',
                'historia' => 'La barbacoa es una técnica culinaria prehispánica que se ha perfeccionado en Hidalgo, siendo reconocida mundialmente.',
                'ubicacion' => 'Actopan, Hidalgo',
                'latitud' => 20.2697,
                'longitud' => -98.9426,
                'categoria_id' => 2,
                'user_id' => 2,
                'likes' => 45
            ],
            [
                'titulo' => 'Textiles Otomíes',
                'descripcion' => 'Bordados tradicionales elaborados por las comunidades otomíes con técnicas ancestrales.',
                'historia' => 'Los bordados otomíes representan la cosmovisión indígena a través de colores y patrones únicos.',
                'ubicacion' => 'Valle del Mezquital, Hidalgo',
                'latitud' => 20.4833,
                'longitud' => -99.1833,
                'categoria_id' => 3,
                'user_id' => 3,
                'likes' => 18
            ]
        ];

        foreach ($tradiciones as $tradicion) {
            Tradicion::create($tradicion);
        }
    }
}