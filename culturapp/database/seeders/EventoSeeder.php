<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use Carbon\Carbon;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = [
            [
                'titulo' => 'Festival del Día de Muertos 2025',
                'descripcion' => 'Gran festival tradicional con ofrendas, música en vivo, danza folclórica y exposición de arte local. Una celebración que honra nuestras tradiciones ancestrales con la participación de toda la comunidad.',
                'fecha_inicio' => Carbon::parse('2025-11-01 16:00:00'),
                'fecha_fin' => Carbon::parse('2025-11-02 22:00:00'),
                'ubicacion' => 'Plaza Principal, Tula de Allende, Hidalgo',
                'latitud' => 20.0530,
                'longitud' => -99.3427,
                'contacto' => 'cultura@tula.gob.mx',
                'telefono' => '773-732-0001',
                'precio' => 0,
                'user_id' => 1,
            ],
            [
                'titulo' => 'Feria de la Barbacoa Hidalguense',
                'descripcion' => 'Degusta la mejor barbacoa de la región preparada por los maestros taqueros locales. Incluye concurso de cocina tradicional, música de mariachi y venta de productos artesanales.',
                'fecha_inicio' => Carbon::parse('2025-08-15 10:00:00'),
                'fecha_fin' => Carbon::parse('2025-08-17 20:00:00'),
                'ubicacion' => 'Explanada Municipal, Actopan, Hidalgo',
                'latitud' => 20.2697,
                'longitud' => -98.9426,
                'contacto' => 'turismo@actopan.gob.mx',
                'telefono' => '772-727-0123',
                'precio' => 50,
                'user_id' => 2,
            ],
            [
                'titulo' => 'Exposición de Textiles Otomíes',
                'descripcion' => 'Muestra de bordados tradicionales otomíes con talleres prácticos para aprender las técnicas ancestrales de tejido y bordado. Participan artesanas de diversas comunidades del Valle del Mezquital.',
                'fecha_inicio' => Carbon::parse('2025-09-20 09:00:00'),
                'fecha_fin' => Carbon::parse('2025-09-25 18:00:00'),
                'ubicacion' => 'Casa de Cultura, Ixmiquilpan, Hidalgo',
                'latitud' => 20.4833,
                'longitud' => -99.1833,
                'contacto' => 'cultura@ixmiquilpan.gob.mx',
                'telefono' => '759-972-0234',
                'precio' => 30,
                'user_id' => 3,
            ],
            [
                'titulo' => 'Concierto de Música Tradicional',
                'descripcion' => 'Presentación de grupos folclóricos locales interpretando música tradicional hidalguense. Entrada libre para toda la familia. Incluye grupos de diferentes municipios de la región.',
                'fecha_inicio' => Carbon::parse('2025-07-25 19:00:00'),
                'fecha_fin' => Carbon::parse('2025-07-25 22:00:00'),
                'ubicacion' => 'Teatro Principal, Pachuca, Hidalgo',
                'latitud' => 20.1011,
                'longitud' => -98.7591,
                'contacto' => 'eventos@pachuca.gob.mx',
                'telefono' => '771-717-0456',
                'precio' => 0,
                'user_id' => 4,
            ],
            [
                'titulo' => 'Taller de Alfarería Tradicional',
                'descripcion' => 'Aprende las técnicas tradicionales de alfarería con maestros artesanos. Incluye materiales y pieza terminada para llevar a casa. Cupos limitados, previa inscripción.',
                'fecha_inicio' => Carbon::parse('2025-08-10 10:00:00'),
                'fecha_fin' => Carbon::parse('2025-08-10 16:00:00'),
                'ubicacion' => 'Centro Artesanal, Huichapan, Hidalgo',
                'latitud' => 20.3756,
                'longitud' => -99.6511,
                'contacto' => 'artesanias@huichapan.mx',
                'telefono' => '761-782-0345',
                'precio' => 150,
                'user_id' => 2,
            ],
            [
                'titulo' => 'Festival Gastronómico del Pulque',
                'descripcion' => 'Celebración de la bebida tradicional mexicana con degustaciones, catas dirigidas y platillos que maridan con el pulque. Evento para mayores de edad.',
                'fecha_inicio' => Carbon::parse('2025-09-14 12:00:00'),
                'fecha_fin' => Carbon::parse('2025-09-15 20:00:00'),
                'ubicacion' => 'Hacienda San Antonio, Zempoala, Hidalgo',
                'latitud' => 19.9167,
                'longitud' => -98.6667,
                'contacto' => 'pulque@zempoala.mx',
                'telefono' => '771-282-0789',
                'precio' => 120,
                'user_id' => 1,
            ],
            [
                'titulo' => 'Encuentro de Danzas Tradicionales',
                'descripcion' => 'Grupos de danza de todo el estado se reúnen para mostrar las tradiciones dancísticas de Hidalgo. Incluye talleres para niños y jóvenes.',
                'fecha_inicio' => Carbon::parse('2025-10-12 16:00:00'),
                'fecha_fin' => Carbon::parse('2025-10-12 21:00:00'),
                'ubicacion' => 'Plaza de Armas, Tulancingo, Hidalgo',
                'latitud' => 20.0833,
                'longitud' => -98.3667,
                'contacto' => 'danza@tulancingo.gob.mx',
                'telefono' => '775-753-0567',
                'precio' => 0,
                'user_id' => 3,
            ],
            [
                'titulo' => 'Mercado de Artesanías Navideñas',
                'descripcion' => 'Venta de artesanías locales perfectas para regalos navideños. Incluye talleres familiares y actividades para niños. Productos únicos hechos por artesanos locales.',
                'fecha_inicio' => Carbon::parse('2025-12-15 10:00:00'),
                'fecha_fin' => Carbon::parse('2025-12-23 20:00:00'),
                'ubicacion' => 'Zócalo, Mineral del Monte, Hidalgo',
                'latitud' => 20.1406,
                'longitud' => -98.6711,
                'contacto' => 'turismo@mineraldelmonte.mx',
                'telefono' => '771-797-0234',
                'precio' => 0,
                'user_id' => 4,
            ]
        ];

        foreach ($eventos as $evento) {
            Evento::create($evento);
        }
    }
}