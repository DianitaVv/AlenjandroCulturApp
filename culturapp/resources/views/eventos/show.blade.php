@extends('layouts.app')

@section('title', $evento->titulo . ' - CulturApp')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos</a></li>
            <li class="breadcrumb-item active">{{ $evento->titulo }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                @if($evento->imagen)
                    <img src="{{ asset('storage/' . $evento->imagen) }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <!-- Header con acciones -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="card-title">{{ $evento->titulo }}</h1>
                        </div>
                        <div class="btn-group" role="group">
                            @can('update', $evento)
                                <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            @endcan
                            @can('delete', $evento)
                                <form method="POST" action="{{ route('eventos.destroy', $evento) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('¿Estás seguro de eliminar este evento?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    
                    <!-- Información del evento -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar"></i> Fecha y Hora</h6>
                            <p class="mb-2">{{ $evento->fecha_inicio->format('d/m/Y H:i') }}</p>
                            @if($evento->fecha_fin)
                                <p class="text-muted">Termina: {{ $evento->fecha_fin->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h6><i class="fas fa-map-marker-alt"></i> Ubicación</h6>
                            <p>{{ $evento->ubicacion }}</p>
                        </div>
                        
                        @if($evento->precio > 0)
                        <div class="col-md-6">
                            <h6><i class="fas fa-dollar-sign"></i> Precio</h6>
                            <p>${{ number_format($evento->precio, 2) }} MXN</p>
                        </div>
                        @else
                        <div class="col-md-6">
                            <h6><i class="fas fa-gift"></i> Precio</h6>
                            <span class="badge bg-success fs-6">Evento Gratuito</span>
                        </div>
                        @endif
                        
                        @if($evento->contacto)
                        <div class="col-md-6">
                            <h6><i class="fas fa-envelope"></i> Contacto</h6>
                            <p><a href="mailto:{{ $evento->contacto }}">{{ $evento->contacto }}</a></p>
                        </div>
                        @endif
                        
                        @if($evento->telefono)
                        <div class="col-md-6">
                            <h6><i class="fas fa-phone"></i> Teléfono</h6>
                            <p><a href="tel:{{ $evento->telefono }}">{{ $evento->telefono }}</a></p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Descripción -->
                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p>{{ $evento->descripcion }}</p>
                    </div>
                    
                    <!-- Organizador -->
                    <div class="border-top pt-3">
                        <small class="text-muted">
                            Organizado por <strong>{{ $evento->user->name }}</strong> 
                            el {{ $evento->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Acciones rápidas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="addToCalendar()">
                            <i class="fas fa-calendar-plus"></i> Agregar a mi calendario
                        </button>
                        <button class="btn btn-outline-primary" onclick="shareEvent()">
                            <i class="fas fa-share"></i> Compartir evento
                        </button>
                        @if($evento->latitud && $evento->longitud)
                        <a href="{{ route('mapa.index') }}?lat={{ $evento->latitud }}&lng={{ $evento->longitud }}" class="btn btn-outline-success">
                            <i class="fas fa-map-marked-alt"></i> Ver en mapa
                        </a>
                        @endif
                        @if($evento->contacto)
                        <a href="mailto:{{ $evento->contacto }}" class="btn btn-outline-info">
                            <i class="fas fa-envelope"></i> Contactar organizador
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Eventos relacionados -->
            @if(isset($relacionados) && $relacionados->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Eventos Relacionados</h5>
                </div>
                <div class="card-body">
                    @foreach($relacionados as $relacionado)
                    <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="flex-shrink-0">
                            @if($relacionado->imagen)
                                <img src="{{ asset('storage/' . $relacionado->imagen) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-calendar text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="{{ route('eventos.show', $relacionado) }}" class="text-decoration-none">
                                    {{ $relacionado->titulo }}
                                </a>
                            </h6>
                            <p class="text-muted mb-1 small">{{ Str::limit($relacionado->descripcion, 60) }}</p>
                            <small class="text-primary">
                                <i class="fas fa-calendar"></i> {{ $relacionado->fecha_inicio->format('d/m/Y') }}
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
function addToCalendar() {
    const title = "{{ $evento->titulo }}";
    const description = "{{ str_replace(["\n", "\r"], " ", $evento->descripcion) }}";
    const location = "{{ $evento->ubicacion }}";
    const startDate = "{{ $evento->fecha_inicio->format('Ymd\THis') }}";
    const endDate = "{{ $evento->fecha_fin ? $evento->fecha_fin->format('Ymd\THis') : $evento->fecha_inicio->addHours(2)->format('Ymd\THis') }}";
    
    const googleUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${startDate}/${endDate}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;
    
    window.open(googleUrl, '_blank');
}

function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: "{{ $evento->titulo }}",
            text: "{{ $evento->descripcion }}",
            url: window.location.href
        });
    } else {
        // Fallback para navegadores que no soportan Web Share API
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('¡Enlace copiado al portapapeles!');
        });
    }
}
</script>
@endsection
@endsection