<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SitioCultural;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;

class SitioCulturalController extends Controller
{
    public function index(Request $request)
    {
        $query = SitioCultural::with('user')->activos();
        
        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%")
                  ->orWhere('direccion', 'LIKE', "%{$search}%")
                  ->orWhere('tipo', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        // Ordenamiento
        $order = $request->get('order', 'recent');
        switch($order) {
            case 'alphabetical':
                $query->orderBy('nombre', 'asc');
                break;
            case 'tipo':
                $query->orderBy('tipo', 'asc')->orderBy('nombre', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $sitios = $query->paginate(12)->withQueryString();
        $tipos = SitioCultural::select('tipo')->distinct()->pluck('tipo');
        
        return view('sitios-culturales.index', compact('sitios', 'tipos'));
    }

    public function show(SitioCultural $sitioCultural)
    {
        $sitioCultural->load('user');
        
        // Sitios relacionados por tipo
        $relacionados = SitioCultural::where('tipo', $sitioCultural->tipo)
            ->where('id', '!=', $sitioCultural->id)
            ->activos()
            ->limit(3)
            ->get();

        return view('sitios-culturales.show', compact('sitioCultural', 'relacionados'));
    }

    public function create()
    {
        // Verificación simple - solo usuarios autenticados
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar sitios culturales.');
        }
        
        return view('sitios-culturales.create');
    }

    public function store(Request $request)
    {
        // Verificación simple - solo usuarios autenticados
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar sitios culturales.');
        }
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'historia' => 'nullable|string',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'direccion' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'horarios' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['imagen', 'galeria_imagenes']);
        $data['user_id'] = Auth::id();

        // Subir imagen principal
        if ($request->hasFile('imagen')) {
            $data['imagen'] = ImageHelper::uploadImage(
                $request->file('imagen'), 
                'sitios-culturales'
            );
        }

        // Subir galería de imágenes
        if ($request->hasFile('galeria_imagenes')) {
            $galeria = [];
            foreach ($request->file('galeria_imagenes') as $file) {
                $galeria[] = ImageHelper::uploadImage($file, 'sitios-culturales/galeria');
            }
            $data['galeria_imagenes'] = $galeria;
        }

        $sitio = SitioCultural::create($data);

        return redirect()->route('sitios-culturales.show', $sitio)
            ->with('success', 'Sitio cultural creado exitosamente.');
    }

    public function edit(SitioCultural $sitioCultural)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $sitioCultural->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar este sitio cultural.');
        }
        
        return view('sitios-culturales.edit', compact('sitioCultural'));
    }

    public function update(Request $request, SitioCultural $sitioCultural)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $sitioCultural->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar este sitio cultural.');
        }
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'historia' => 'nullable|string',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'direccion' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'horarios' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['imagen', 'galeria_imagenes']);

        // Actualizar imagen principal
        if ($request->hasFile('imagen')) {
            ImageHelper::deleteImage($sitioCultural->imagen);
            $data['imagen'] = ImageHelper::uploadImage(
                $request->file('imagen'), 
                'sitios-culturales'
            );
        }

        // Actualizar galería
        if ($request->hasFile('galeria_imagenes')) {
            if ($sitioCultural->galeria_imagenes) {
                foreach ($sitioCultural->galeria_imagenes as $imagen) {
                    ImageHelper::deleteImage($imagen);
                }
            }
            
            $galeria = [];
            foreach ($request->file('galeria_imagenes') as $file) {
                $galeria[] = ImageHelper::uploadImage($file, 'sitios-culturales/galeria');
            }
            $data['galeria_imagenes'] = $galeria;
        }

        $sitioCultural->update($data);

        return redirect()->route('sitios-culturales.show', $sitioCultural)
            ->with('success', 'Sitio cultural actualizado exitosamente.');
    }

    public function destroy(SitioCultural $sitioCultural)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $sitioCultural->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para eliminar este sitio cultural.');
        }
        
        // Eliminar imágenes
        ImageHelper::deleteImage($sitioCultural->imagen);
        if ($sitioCultural->galeria_imagenes) {
            foreach ($sitioCultural->galeria_imagenes as $imagen) {
                ImageHelper::deleteImage($imagen);
            }
        }
        
        $sitioCultural->delete();

        return redirect()->route('sitios-culturales.index')
            ->with('success', 'Sitio cultural eliminado exitosamente.');
    }
}