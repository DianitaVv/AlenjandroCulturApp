@extends('layouts.app')

@section('title', 'Tradiciones - CulturApp')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1><i class="fas fa-star-of-life text-primary"></i> Tradiciones Culturales</h1>
            <p class="text-muted">Explora y descubre las tradiciones de nuestra región</p>
        </div>
        @auth
        <div class="col-auto">
            <a href="{{ route('tradiciones.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Tradición
            </a>
        </div>
        @endauth
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('tradiciones.index') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Buscar tradiciones</label>
                                <input type="text" class="form-control" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Buscar por título, descripción o ubicación...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Categoría</label>
                                <select class="form-select" name="categoria_id">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" 
                                                {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ordenar por</label>
                                <select class="form-select" name="order">
                                    <option value="recent" {{ request('order') == 'recent' ? 'selected' : '' }}>
                                        Más recientes
                                    </option>
                                    <option value="popular" {{ request('order') == 'popular' ? 'selected' : '' }}>
                                        Más populares
                                    </option>
                                    <option value="alphabetical" {{ request('order') == 'alphabetical' ? 'selected' : '' }}>
                                        Alfabético
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    @if(request()->hasAny(['search', 'categoria_id', 'order']))
                                        <a href="{{ route('tradiciones.index') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-times"></i> Limpiar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    @if(request('search') || request('categoria_id'))
        <div class="mb-3">
            <p class="text-muted">
                {{ $tradiciones->total() }} resultado(s) encontrado(s)
                @if(request('search'))
                    para "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('categoria_id'))
                    en la categoría "<strong>{{ $categorias->find(request('categoria_id'))->nombre ?? '' }}</strong>"
                @endif
            </p>
        </div>
    @endif

    <!-- Lista de tradiciones -->
    <div class="row">
        @forelse($tradiciones as $tradicion)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-tradicion h-100 shadow-sm">
                @if($tradicion->imagen_principal)
                    <img src="{{ asset('storage/' . $tradicion->imagen_principal) }}" 
                         class="card-img-top" style="height: 250px; object-fit: cover;"
                         alt="{{ $tradicion->titulo }}">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                        <i class="{{ $tradicion->categoria->icono ?? 'fas fa-image' }} fa-4x text-white"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">{{ $tradicion->categoria->nombre }}</span>
                        <div class="text-muted">
                            <i class="fas fa-heart text-danger"></i> {{ $tradicion->likes }}
                        </div>
                    </div>
                    
                    <h5 class="card-title">{{ $tradicion->titulo }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($tradicion->descripcion, 120) }}</p>
                    
                    @if($tradicion->ubicacion)
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $tradicion->ubicacion }}
                        </p>
                    @endif
                    
                    @if($tradicion->fecha_celebracion)
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar"></i> {{ $tradicion->fecha_celebracion->format('d/m/Y') }}
                        </p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <small class="text-muted">
                            <i class="fas fa-user"></i> {{ $tradicion->user->name }}
                        </small>
                        <div class="btn-group" role="group">
                            <a href="{{ route('tradiciones.show', $tradicion) }}" class="btn btn-primary btn-sm">
                                Ver detalles
                            </a>
                            @can('update', $tradicion)
                                <a href="{{ route('tradiciones.edit', $tradicion) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>No se encontraron tradiciones</h4>
                @if(request()->hasAny(['search', 'categoria_id']))
                    <p class="text-muted">Intenta con otros términos de búsqueda</p>
                    <a href="{{ route('tradiciones.index') }}" class="btn btn-outline-primary">
                        Ver todas las tradiciones
                    </a>
                @else
                    <p class="text-muted">Sé el primero en compartir una tradición de tu región</p>
                    @auth
                        <a href="{{ route('tradiciones.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Tradición
                        </a>
                    @endauth
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($tradiciones->hasPages())
        <div class="d-flex justify-content-center">
            {{ $tradiciones->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection