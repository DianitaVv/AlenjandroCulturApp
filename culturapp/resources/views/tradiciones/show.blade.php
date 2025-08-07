@extends('layouts.app')

@section('title', $tradicion->titulo . ' - CulturApp')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tradiciones.index') }}">Tradiciones</a></li>
            <li class="breadcrumb-item active">{{ $tradicion->titulo }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Contenido principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                @if($tradicion->imagen_principal)
                    <img src="{{ asset('storage/' . $tradicion->imagen_principal) }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="{{ $tradicion->categoria->icono }} fa-5x text-white"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary fs-6">{{ $tradicion->categoria->nombre }}</span>
                        <div class="d-flex align-items-center">
                            <button onclick="likePost({{ $tradicion->id }})" class="btn btn-outline-danger btn-sm me-2">
                                <i class="fas fa-heart"></i> <span id="likes-count">{{ $tradicion->likes }}</span>
                            </button>
                            @can('update', $tradicion)
                                <a href="{{ route('tradiciones.edit', $tradicion) }}" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            @endcan
                            @can('delete', $tradicion)
                                <form method="POST" action="{{ route('tradiciones.destroy', $tradicion) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta tradición?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    
                    <h1 class="card-title mb-3">{{ $tradicion->titulo }}</h1>
                    
                    <!-- Meta información -->
                    <div class="row mb-4">
                        @if($tradicion->ubicacion)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> {{ $tradicion->ubicacion }}
                            </small>
                        </div>
                        @endif
                        
                        @if($tradicion->fecha_celebracion)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $tradicion->fecha_celebracion->format('d/m/Y') }}
                            </small>
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> Compartido por {{ $tradicion->user->name }}
                            </small>
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $tradicion->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="mb-4">
                        <h4>Descripción</h4>
                        <p class="lead">{{ $tradicion->descripcion }}</p>
                    </div>
                    
                    <!-- Historia -->
                    @if($tradicion->historia)
                    <div class="mb-4">
                        <h4>Historia</h4>
                        <p>{{ $tradicion->historia }}</p>
                    </div>
                    @endif

                    <!-- Galería de imágenes -->
                    @if($tradicion->galeria_imagenes && count($tradicion->galeria_imagenes) > 0)
                    <div class="mb-4">
                        <h4>Galería de Imágenes</h4>
                        <div class="row">
                            @foreach($tradicion->galeria_imagenes as $imagen)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $imagen) }}" class="img-fluid rounded shadow-sm" alt="Galería">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Multimedia -->
                    @if($tradicion->video_url || $tradicion->audio_url)
                    <div class="mb-4">
                        <h4>Contenido Multimedia</h4>
                        <div class="row">
                            @if($tradicion->video_url)
                            <div class="col-md-6 mb-3">
                                <h6>Video</h6>
                                <video controls class="w-100" style="max-height: 300px;">
                                    <source src="{{ $tradicion->video_url }}" type="video/mp4">
                                    Tu navegador no soporta videos.
                                </video>
                            </div>
                            @endif
                            
                            @if($tradicion->audio_url)
                            <div class="col-md-6 mb-3">
                                <h6>Audio</h6>
                                <audio controls class="w-100">
                                    <source src="{{ $tradicion->audio_url }}" type="audio/mpeg">
                                    Tu navegador no soporta audio.
                                </audio>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Acciones -->
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-share"></i> Compartir
                        </button>
                        @if($tradicion->latitud && $tradicion->longitud)
                        <a href="{{ route('mapa.index') }}?lat={{ $tradicion->latitud }}&lng={{ $tradicion->longitud }}" class="btn btn-outline-success">
                            <i class="fas fa-map-marked-alt"></i> Ver en mapa
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tradiciones relacionadas -->
            @if($relacionadas->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Tradiciones Relacionadas</h5>
                </div>
                <div class="card-body">
                    @foreach($relacionadas as $relacionada)
                    <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="flex-shrink-0">
                            @if($relacionada->imagen_principal)
                                <img src="{{ asset('storage/' . $relacionada->imagen_principal) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="{{ $relacionada->categoria->icono }} text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="{{ route('tradiciones.show', $relacionada) }}" class="text-decoration-none">
                                    {{ $relacionada->titulo }}
                                </a>
                            </h6>
                            <p class="text-muted mb-0 small">{{ Str::limit($relacionada->descripcion, 60) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
function likePost(tradicionId) {
    fetch(`/tradiciones/${tradicionId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('likes-count').textContent = data.likes;
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
@endsection