@extends('layouts.app')

@section('title', 'Inicio - CulturApp')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-4">Preserva Nuestras Tradiciones</h1>
        <p class="lead mb-4">Conectando generaciones a través del patrimonio cultural de nuestra región</p>
        <a href="{{ route('tradiciones.index') }}" class="btn btn-light btn-lg me-3">
            <i class="fas fa-search"></i> Explorar Tradiciones
        </a>
        <a href="{{ route('mapa.index') }}" class="btn btn-outline-light btn-lg">
            <i class="fas fa-map-marked-alt"></i> Ver Mapa Cultural
        </a>
    </div>
</section>

<!-- Categorías -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Explora por Categorías</h2>
        <div class="row">
            @foreach($categorias as $categoria)
            <div class="col-md-2 col-sm-4 col-6 mb-4">
                <a href="{{ route('tradiciones.index', ['categoria_id' => $categoria->id]) }}" class="text-decoration-none">
                    <div class="card text-center h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="{{ $categoria->icono }} categoria-icon mb-3"></i>
                            <h6 class="card-title">{{ $categoria->nombre }}</h6>
                            <small class="text-muted">{{ $categoria->descripcion }}</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tradiciones Recientes -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2>Tradiciones Recientes</h2>
                <p class="text-muted">Descubre las últimas tradiciones compartidas por nuestra comunidad</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('tradiciones.index') }}" class="btn btn-primary">Ver todas</a>
            </div>
        </div>
        
        <div class="row">
            @foreach($tradicionesRecientes as $tradicion)
            <div class="col-md-4 mb-4">
                <div class="card card-tradicion h-100 shadow-sm">
                    @if($tradicion->imagen_principal)
                        <img src="{{ asset('storage/' . $tradicion->imagen_principal) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="{{ $tradicion->categoria->icono }} fa-3x text-white"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary">{{ $tradicion->categoria->nombre }}</span>
                            <small class="text-muted">
                                <i class="fas fa-heart text-danger"></i> {{ $tradicion->likes }}
                            </small>
                        </div>
                        <h5 class="card-title">{{ $tradicion->titulo }}</h5>
                        <p class="card-text">{{ Str::limit($tradicion->descripcion, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> {{ $tradicion->user->name }}
                            </small>
                            <a href="{{ route('tradiciones.show', $tradicion) }}" class="btn btn-outline-primary btn-sm">
                                Leer más
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Eventos Próximos -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h2>Eventos Próximos</h2>
                <p class="text-muted">No te pierdas estos eventos culturales</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('eventos.index') }}" class="btn btn-primary">Ver todos</a>
            </div>
        </div>
        
        <div class="row">
            @foreach($eventosProximos as $evento)
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="bg-primary text-white rounded p-3">
                                    <div class="h4 mb-0">{{ $evento->fecha_inicio->format('d') }}</div>
                                    <small>{{ $evento->fecha_inicio->format('M') }}</small>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h5 class="card-title">{{ $evento->titulo }}</h5>
                                <p class="card-text mb-2">{{ Str::limit($evento->descripcion, 80) }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $evento->ubicacion }}
                                </small>
                                <div class="mt-2">
                                    <a href="{{ route('eventos.show', $evento) }}" class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection