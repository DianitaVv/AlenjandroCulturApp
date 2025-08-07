<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tradicion;
use App\Models\Evento;
use App\Models\SitioCultural;

class MapaController extends Controller
{
    public function index()
    {
        // Obtener todos los puntos de interés con coordenadas
        $tradiciones = Tradicion::whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->activas()
            ->with('categoria')
            ->get();

        $eventos = Evento::whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->activos()
            ->get();

        $sitiosCulturales = SitioCultural::whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->activos()
            ->get();

        return view('mapa.index', compact('tradiciones', 'eventos', 'sitiosCulturales'));
    }
}