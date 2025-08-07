<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tradicion;
use App\Models\Categoria;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TradicionController extends Controller
{
    public function index(Request $request)
    {
        $query = Tradicion::with('categoria', 'user')->activas();
        
        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%")
                  ->orWhere('ubicacion', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        // Ordenamiento
        $order = $request->get('order', 'recent');
        switch($order) {
            case 'popular':
                $query->orderBy('likes', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('titulo', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $tradiciones = $query->paginate(12)->withQueryString();
        $categorias = Categoria::all();
        
        return view('tradiciones.index', compact('tradiciones', 'categorias'));
    }

    public function show(Tradicion $tradicion)
    {
        $tradicion->load('categoria', 'user');
        
        // Tradiciones relacionadas
        $relacionadas = Tradicion::where('categoria_id', $tradicion->categoria_id)
            ->where('id', '!=', $tradicion->id)
            ->activas()
            ->limit(3)
            ->get();

        return view('tradiciones.show', compact('tradicion', 'relacionadas'));
    }

    public function create()
    {
        // Verificación simple - solo usuarios autenticados
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar tradiciones.');
        }
        
        $categorias = Categoria::all();
        return view('tradiciones.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // Verificación simple - solo usuarios autenticados
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar tradiciones.');
        }
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'ubicacion' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'fecha_celebracion' => 'nullable|date',
        ]);

        $data = $request->except(['imagen_principal', 'galeria_imagenes']);
        $data['user_id'] = Auth::id();

        // Subir imagen principal
        if ($request->hasFile('imagen_principal')) {
            $data['imagen_principal'] = ImageHelper::uploadImage(
                $request->file('imagen_principal'), 
                'tradiciones'
            );
        }

        // Subir galería de imágenes
        if ($request->hasFile('galeria_imagenes')) {
            $galeria = [];
            foreach ($request->file('galeria_imagenes') as $file) {
                $galeria[] = ImageHelper::uploadImage($file, 'tradiciones/galeria');
            }
            $data['galeria_imagenes'] = $galeria;
        }

        $tradicion = Tradicion::create($data);

        return redirect()->route('tradiciones.show', $tradicion)
            ->with('success', 'Tradición creada exitosamente.');
    }

    public function edit(Tradicion $tradicion)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $tradicion->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar esta tradición.');
        }
        
        $categorias = Categoria::all();
        return view('tradiciones.edit', compact('tradicion', 'categorias'));
    }

    public function update(Request $request, Tradicion $tradicion)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $tradicion->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para editar esta tradición.');
        }
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'ubicacion' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'fecha_celebracion' => 'nullable|date',
        ]);

        $data = $request->except(['imagen_principal', 'galeria_imagenes']);

        // Actualizar imagen principal
        if ($request->hasFile('imagen_principal')) {
            // Eliminar imagen anterior
            ImageHelper::deleteImage($tradicion->imagen_principal);
            
            $data['imagen_principal'] = ImageHelper::uploadImage(
                $request->file('imagen_principal'), 
                'tradiciones'
            );
        }

        // Actualizar galería
        if ($request->hasFile('galeria_imagenes')) {
            // Eliminar imágenes anteriores
            if ($tradicion->galeria_imagenes) {
                foreach ($tradicion->galeria_imagenes as $imagen) {
                    ImageHelper::deleteImage($imagen);
                }
            }
            
            $galeria = [];
            foreach ($request->file('galeria_imagenes') as $file) {
                $galeria[] = ImageHelper::uploadImage($file, 'tradiciones/galeria');
            }
            $data['galeria_imagenes'] = $galeria;
        }

        $tradicion->update($data);

        return redirect()->route('tradiciones.show', $tradicion)
            ->with('success', 'Tradición actualizada exitosamente.');
    }

    public function destroy(Tradicion $tradicion)
    {
        // Verificación simple - solo el creador o admins
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        
        if (auth()->user()->id !== $tradicion->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para eliminar esta tradición.');
        }
        
        // Eliminar imágenes
        ImageHelper::deleteImage($tradicion->imagen_principal);
        if ($tradicion->galeria_imagenes) {
            foreach ($tradicion->galeria_imagenes as $imagen) {
                ImageHelper::deleteImage($imagen);
            }
        }
        
        $tradicion->delete();

        return redirect()->route('tradiciones.index')
            ->with('success', 'Tradición eliminada exitosamente.');
    }

    public function like(Tradicion $tradicion)
    {
        $tradicion->increment('likes');
        return response()->json(['likes' => $tradicion->likes]);
    }
}