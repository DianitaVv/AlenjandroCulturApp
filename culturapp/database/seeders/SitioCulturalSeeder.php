<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SitioCultural;

class SitioCulturalSeeder extends Seeder
{
    public function run(): void
    {
        $sitios = [
            [
                'nombre' => 'Zona Arqueológica de Tula',
                'descripcion' => 'Importante sitio arqueológico de la cultura tolteca, conocido por sus famosos Atlantes de Tula, esculturas monumentales que representan guerreros toltecas.',
                'historia' => 'Tula fue la capital del imperio tolteca entre los años 900 y 1200 d.C. Los Atlantes son columnas esculpidas que sostenían el techo del Templo de Tlahuizcalpantecuhtli.',
                'latitud' => 20.0611,
                'longitud' => -99.3425,
                'direccion' => 'Zona Arqueológica de Tula, Tula de Allende, Hidalgo',
                'tipo' => 'zona_arqueologica',
                'horarios' => 'Lunes a Domingo 9:00 - 17:00',
                'user_id' => 1,
            ],
            [
                'nombre' => 'Catedral de Tula',
                'descripcion' => 'Hermosa catedral colonial construida en el siglo XVI, dedicada a San José. Representa la arquitectura religiosa típica de la época colonial en Hidalgo.',
                'historia' => 'Construida por los franciscanos en el siglo XVI sobre un templo prehispánico. Ha sido testigo de importantes eventos históricos de la región.',
                'latitud' => 20.0530,
                'longitud' => -99.3427,
                'direccion' => 'Plaza de Armas, Centro, Tula de Allende, Hidalgo',
                'tipo' => 'iglesia',
                'horarios' => 'Lunes a Domingo 6:00 - 20:00',
                'user_id' => 2,
            ],
            [
                'nombre' => 'Museo Nacional del Transporte',
                'descripcion' => 'Museo dedicado a la historia del transporte en México, ubicado en una antigua estación de ferrocarril. Exhibe locomotoras históricas y vagones antiguos.',
                'historia' => 'Instalado en la antigua estación del Ferrocarril Central Mexicano, construida a finales del siglo XIX. Preserva la memoria ferroviaria de la región.',
                'latitud' => 20.0542,
                'longitud' => -99.3401,
                'direccion' => 'Av. 16 de Enero s/n, Centro, Tula de Allende, Hidalgo',
                'tipo' => 'museo',
                'horarios' => 'Martes a Domingo 10:00 - 17:00',
                'user_id' => 3,
            ],
            [
                'nombre' => 'Plaza de Armas de Tula',
                'descripcion' => 'Plaza principal del centro histórico de Tula, rodeada de edificios coloniales y modernos. Centro de la vida social y cultural de la ciudad.',
                'historia' => 'Plaza fundada junto con la ciudad colonial de Tula. Ha sido el corazón de la vida cívica y religiosa desde el siglo XVI.',
                'latitud' => 20.0525,
                'longitud' => -99.3430,
                'direccion' => 'Centro Histórico, Tula de Allende, Hidalgo',
                'tipo' => 'plaza',
                'horarios' => 'Acceso libre las 24 horas',
                'user_id' => 4,
            ],
            [
                'nombre' => 'Mercado Benito Juárez',
                'descripcion' => 'Mercado tradicional donde se pueden encontrar productos locales, artesanías, comida típica y especialmente la famosa barbacoa hidalguense.',
                'historia' => 'Mercado establecido en el siglo XX como centro comercial de la región. Conserva las tradiciones gastronómicas y artesanales locales.',
                'latitud' => 20.0515,
                'longitud' => -99.3435,
                'direccion' => 'Calle Benito Juárez, Centro, Tula de Allende, Hidalgo',
                'tipo' => 'mercado',
                'horarios' => 'Lunes a Domingo 6:00 - 18:00',
                'user_id' => 1,
            ],
            [
                'nombre' => 'Centro Cultural Quetzalcóatl',
                'descripcion' => 'Moderno centro cultural que alberga exposiciones de arte, talleres culturales y eventos artísticos. Importante espacio para la difusión cultural local.',
                'historia' => 'Inaugurado en el siglo XXI como parte del proyecto de revitalización cultural de Tula. Su nombre honra a la deidad tolteca Quetzalcóatl.',
                'latitud' => 20.0545,
                'longitud' => -99.3415,
                'direccion' => 'Av. Quetzalcóatl, Col. Centro, Tula de Allende, Hidalgo',
                'tipo' => 'centro_historico',
                'horarios' => 'Martes a Domingo 9:00 - 19:00',
                'user_id' => 2,
            ],
            [
                'nombre' => 'Palacio Municipal de Tula',
                'descripcion' => 'Edificio de gobierno municipal con arquitectura neoclásica del siglo XIX. Sede del ayuntamiento y punto de referencia del centro histórico.',
                'historia' => 'Construido en el siglo XIX como sede del gobierno municipal. Ha sido testigo de la evolución política y social de Tula.',
                'latitud' => 20.0528,
                'longitud' => -99.3425,
                'direccion' => 'Plaza de Armas s/n, Centro, Tula de Allende, Hidalgo',
                'tipo' => 'edificio_historico',
                'horarios' => 'Lunes a Viernes 8:00 - 16:00',
                'user_id' => 3,
            ],
            [
                'nombre' => 'Monumento a los Atlantes',
                'descripcion' => 'Réplica moderna de los famosos Atlantes de Tula ubicada en el centro de la ciudad. Símbolo representativo de la identidad tolteca de Tula.',
                'historia' => 'Monumento moderno que replica las esculturas prehispánicas. Representa el orgullo de la herencia tolteca en la ciudad actual.',
                'latitud' => 20.0535,
                'longitud' => -99.3420,
                'direccion' => 'Av. Quetzalcóatl, Centro, Tula de Allende, Hidalgo',
                'tipo' => 'monumento',
                'horarios' => 'Acceso libre las 24 horas',
                'user_id' => 4,
            ]
        ];

        foreach ($sitios as $sitio) {
            SitioCultural::create($sitio);
        }
    }
}