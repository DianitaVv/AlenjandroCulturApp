<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::with('user')->activos();
        
        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%")
                  ->orWhere('ubicacion', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_inicio', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_inicio', '<=', $request->fecha_hasta . ' 23:59:59');
        }
        
        // Filtro por precio
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }
        
        if ($request->has('gratuito')) {
            $query->where('precio', 0);
        }
        
        // Ordenamiento
        $order = $request->get('order', 'fecha');
        switch($order) {
            case 'titulo':
                $query->orderBy('titulo', 'asc');
                break;
            case 'precio':
                $query->orderBy('precio', 'asc');
                break;
            default:
                $query->orderBy('fecha_inicio', 'asc');
        }
        
        $eventos = $query->paginate(12)->withQueryString();
        
        return view('eventos.index', compact('eventos'));
    }

    public function show(Evento $evento)
    {
        $evento->load('user');
        
        // Eventos relacionados
        $fechaInicio = Carbon::parse($evento->fecha_inicio);
        $fechaDesde = $fechaInicio->copy()->subDays(7);
        $fechaHasta = $fechaInicio->copy()->addDays(14);
        
        $relacionados = Evento::where('id', '!=', $evento->id)
            ->activos()
            ->where(function($query) use ($evento, $fechaDesde, $fechaHasta) {
                $ubicacionPrincipal = explode(',', $evento->ubicacion)[0];
                $query->where('ubicacion', 'LIKE', '%' . $ubicacionPrincipal . '%')
                      ->orWhereBetween('fecha_inicio', [$fechaDesde, $fechaHasta]);
            })
            ->limit(3)
            ->get();

        return view('eventos.show', compact('evento', 'relacionados'));
    }

    public function create()
    {
        // Verificación simple - solo usuarios autenticados
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar eventos.');
        }
        
        return view('eventos.create');
    }

    public function store(Request $request)
    {
        // Verificación simple - solo usuarios autenticados
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar eventos.');
        }
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'fecha_inicio' => 'required|date|after:now',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'ubicacion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'contacto' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'precio' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['imagen']);
        $data['user_id'] = Auth::id();
        $data['precio'] = $request->precio ?? 0;

        // Subir imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = ImageHelper::uploadImage(
                $request->file('imagen'), 
                'eventos'
            );
        }

        $evento = Evento::create($data);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Evento creado exitosamente.');
    }

    public function edit(Evento $evento)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $evento->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar este evento.');
        }
        
        return view('eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $evento->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar este evento.');
        }
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'ubicacion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'contacto' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'precio' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['imagen']);
        $data['precio'] = $request->precio ?? 0;

        // Actualizar imagen
        if ($request->hasFile('imagen')) {
            ImageHelper::deleteImage($evento->imagen);
            $data['imagen'] = ImageHelper::uploadImage(
                $request->file('imagen'), 
                'eventos'
            );
        }

        $evento->update($data);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Evento actualizado exitosamente.');
    }

    public function destroy(Evento $evento)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $evento->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para eliminar este evento.');
        }
        
        // Eliminar imagen
        ImageHelper::deleteImage($evento->imagen);
        
        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', 'Evento eliminado exitosamente.');
    }
}