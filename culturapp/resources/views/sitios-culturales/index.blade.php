@extends('layouts.app')

@section('title', 'Sitios Culturales - CulturApp')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1><i class="fas fa-landmark text-primary"></i> Sitios Culturales</h1>
            <p class="text-muted">Descubre los lugares con valor histórico y cultural de la región</p>
        </div>
        @auth
        <div class="col-auto">
            <a href="{{ route('sitios-culturales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Sitio Cultural
            </a>
        </div>
        @endauth
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('sitios-culturales.index') }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Buscar sitios</label>
                                <input type="text" class="form-control" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Buscar por nombre, descripción...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-select" name="tipo">
                                    <option value="">Todos los tipos</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo }}" 
                                                {{ request('tipo') == $tipo ? 'selected' : '' }}>
                                            {{ ucfirst($tipo) }}
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
                                    <option value="alphabetical" {{ request('order') == 'alphabetical' ? 'selected' : '' }}>
                                        Alfabético
                                    </option>
                                    <option value="tipo" {{ request('order') == 'tipo' ? 'selected' : '' }}>
                                        Por tipo
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                    @if(request()->hasAny(['search', 'tipo', 'order']))
                                        <a href="{{ route('sitios-culturales.index') }}" class="btn btn-outline-secondary btn-sm">
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
    @if(request()->hasAny(['search', 'tipo']))
        <div class="mb-3">
            <p class="text-muted">
                {{ $sitios->total() }} sitio(s) encontrado(s)
                @if(request('search'))
                    para "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>
    @endif

    <!-- Lista de sitios -->
    <div class="row">
        @forelse($sitios as $sitio)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-tradicion h-100 shadow-sm">
                @if($sitio->imagen)
                    <img src="{{ asset('storage/' . $sitio->imagen) }}" 
                         class="card-img-top" style="height: 250px; object-fit: cover;"
                         alt="{{ $sitio->nombre }}">
                @else
                    <div class="card-img-top bg-warning d-flex align-items-center justify-content-center" style="height: 250px;">
                        <i class="fas fa-landmark fa-4x text-white"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-warning text-dark">{{ ucfirst($sitio->tipo) }}</span>
                    </div>
                    
                    <h5 class="card-title">{{ $sitio->nombre }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($sitio->descripcion, 120) }}</p>
                    
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt"></i> {{ $sitio->direccion }}
                    </p>
                    
                    @if($sitio->horarios)
                        <p class="text-muted mb-2">
                            <i class="fas fa-clock"></i> {{ $sitio->horarios }}
                        </p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <small class="text-muted">
                            <i class="fas fa-user"></i> {{ $sitio->user->name }}
                        </small>
                        <div class="btn-group" role="group">
                            <a href="{{ route('sitios-culturales.show', $sitio) }}" class="btn btn-primary btn-sm">
                                Ver detalles
                            </a>
                            @can('update', $sitio)
                                <a href="{{ route('sitios-culturales.edit', $sitio) }}" class="btn btn-outline-secondary btn-sm">
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
                <i class="fas fa-landmark fa-3x text-muted mb-3"></i>
                <h4>No se encontraron sitios culturales</h4>
                @if(request()->hasAny(['search', 'tipo']))
                    <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                    <a href="{{ route('sitios-culturales.index') }}" class="btn btn-outline-primary">
                        Ver todos los sitios
                    </a>
                @else
                    <p class="text-muted">Sé el primero en agregar un sitio cultural</p>
                    @auth
                        <a href="{{ route('sitios-culturales.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Sitio Cultural
                        </a>
                    @endauth
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($sitios->hasPages())
        <div class="d-flex justify-content-center">
            {{ $sitios->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection