@extends('layouts.app')

@section('title', $sitioCultural->nombre . ' - CulturApp')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sitios-culturales.index') }}">Sitios Culturales</a></li>
            <li class="breadcrumb-item active">{{ $sitioCultural->nombre }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Contenido principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                @if($sitioCultural->imagen)
                    <img src="{{ asset('storage/' . $sitioCultural->imagen) }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-warning d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-landmark fa-5x text-white"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <!-- Header con acciones -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-warning text-dark fs-6">{{ ucfirst($sitioCultural->tipo) }}</span>
                            <h1 class="card-title mt-2">{{ $sitioCultural->nombre }}</h1>
                        </div>
                        <div class="btn-group" role="group">
                            @if(auth()->check() && (auth()->user()->id === $sitioCultural->user_id || auth()->user()->isAdmin()))
                                <a href="{{ route('sitios-culturales.edit', $sitioCultural) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form method="POST" action="{{ route('sitios-culturales.destroy', $sitioCultural) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('¿Estás seguro de eliminar este sitio cultural?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Meta información -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> {{ $sitioCultural->direccion }}
                            </small>
                        </div>
                        
                        @if($sitioCultural->horarios)
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $sitioCultural->horarios }}
                            </small>
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> Compartido por {{ $sitioCultural->user->name }}
                            </small>
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $sitioCultural->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="mb-4">
                        <h4>Descripción</h4>
                        <p class="lead">{{ $sitioCultural->descripcion }}</p>
                    </div>
                    
                    <!-- Historia -->
                    @if($sitioCultural->historia)
                    <div class="mb-4">
                        <h4>Historia</h4>
                        <p>{{ $sitioCultural->historia }}</p>
                    </div>
                    @endif

                    <!-- Galería de imágenes -->
                    @if($sitioCultural->galeria_imagenes && count($sitioCultural->galeria_imagenes) > 0)
                    <div class="mb-4">
                        <h4>Galería de Imágenes</h4>
                        <div class="row">
                            @foreach($sitioCultural->galeria_imagenes as $imagen)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/' . $imagen) }}" class="img-fluid rounded shadow-sm" alt="Galería">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Acciones -->
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary" onclick="shareLocation()">
                            <i class="fas fa-share"></i> Compartir
                        </button>
                        @if($sitioCultural->latitud && $sitioCultural->longitud)
                        <a href="{{ route('mapa.index') }}?lat={{ $sitioCultural->latitud }}&lng={{ $sitioCultural->longitud }}" class="btn btn-outline-success">
                            <i class="fas fa-map-marked-alt"></i> Ver en mapa
                        </a>
                        @endif
                        <button class="btn btn-outline-info" onclick="getDirections()">
                            <i class="fas fa-route"></i> Cómo llegar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Información rápida -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Tipo:</strong> {{ ucfirst(str_replace('_', ' ', $sitioCultural->tipo)) }}
                    </div>
                    @if($sitioCultural->horarios)
                    <div class="mb-3">
                        <strong>Horarios:</strong><br>
                        {{ $sitioCultural->horarios }}
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Ubicación:</strong><br>
                        {{ $sitioCultural->direccion }}
                    </div>
                    @if($sitioCultural->latitud && $sitioCultural->longitud)
                    <div class="mb-3">
                        <strong>Coordenadas:</strong><br>
                        {{ $sitioCultural->latitud }}, {{ $sitioCultural->longitud }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sitios relacionados -->
            @if(isset($relacionados) && $relacionados->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Sitios Similares</h5>
                </div>
                <div class="card-body">
                    @foreach($relacionados as $relacionado)
                    <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="flex-shrink-0">
                            @if($relacionado->imagen)
                                <img src="{{ asset('storage/' . $relacionado->imagen) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-warning rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-landmark text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="{{ route('sitios-culturales.show', $relacionado) }}" class="text-decoration-none">
                                    {{ $relacionado->nombre }}
                                </a>
                            </h6>
                            <p class="text-muted mb-1 small">{{ Str::limit($relacionado->descripcion, 60) }}</p>
                            <small class="text-warning">
                                <i class="fas fa-tag"></i> {{ ucfirst($relacionado->tipo) }}
                            </small>
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
function shareLocation() {
    if (navigator.share) {
        navigator.share({
            title: "{{ $sitioCultural->nombre }}",
            text: "{{ $sitioCultural->descripcion }}",
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('¡Enlace copiado al portapapeles!');
        });
    }
}

function getDirections() {
    @if($sitioCultural->latitud && $sitioCultural->longitud)
        const lat = {{ $sitioCultural->latitud }};
        const lng = {{ $sitioCultural->longitud }};
        const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
        window.open(googleMapsUrl, '_blank');
    @else
        const address = encodeURIComponent("{{ $sitioCultural->direccion }}");
        const googleMapsUrl = `https://www.google.com/maps/search/${address}`;
        window.open(googleMapsUrl, '_blank');
    @endif
}
</script>
@endsection
@endsection