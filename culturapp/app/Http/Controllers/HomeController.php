<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tradicion;
use App\Models\Evento;
use App\Models\Testimonio;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener datos para la página principal
        $tradicionesRecientes = Tradicion::with('categoria', 'user')
            ->activas()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $eventosProximos = Evento::activos()
            ->proximos()
            ->orderBy('fecha_inicio', 'asc')
            ->limit(4)
            ->get();

        $testimoniosRecientes = Testimonio::with('user')
            ->activos()
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $categorias = Categoria::all();

        return view('home', compact(
            'tradicionesRecientes',
            'eventosProximos', 
            'testimoniosRecientes',
            'categorias'
        ));
    }
}