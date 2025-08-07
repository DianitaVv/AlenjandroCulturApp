@extends('layouts.app')

@section('title', 'Eventos - CulturApp')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1><i class="fas fa-calendar-alt text-primary"></i> Eventos Culturales</h1>
            <p class="text-muted">Descubre los próximos eventos de tu región</p>
        </div>
        @auth
        <div class="col-auto">
            <a href="{{ route('eventos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Evento
            </a>
        </div>
        @endauth
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('eventos.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Buscar eventos</label>
                                <input type="text" class="form-control" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Buscar por título, descripción...">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control" name="fecha_desde" 
                                       value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Hasta</label>
                                <input type="date" class="form-control" name="fecha_hasta" 
                                       value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Precio máx.</label>
                                <input type="number" class="form-control" name="precio_max" 
                                       value="{{ request('precio_max') }}" placeholder="500">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Ordenar por</label>
                                <select class="form-select" name="order">
                                    <option value="fecha" {{ request('order') == 'fecha' ? 'selected' : '' }}>
                                        Fecha
                                    </option>
                                    <option value="titulo" {{ request('order') == 'titulo' ? 'selected' : '' }}>
                                        Título
                                    </option>
                                    <option value="precio" {{ request('order') == 'precio' ? 'selected' : '' }}>
                                        Precio
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="gratuito" 
                                           {{ request('gratuito') ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Solo eventos gratuitos
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-9 text-end">
                                @if(request()->hasAny(['search', 'fecha_desde', 'fecha_hasta', 'precio_max', 'gratuito', 'order']))
                                    <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> Limpiar filtros
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    @if(request()->hasAny(['search', 'fecha_desde', 'fecha_hasta', 'precio_max', 'gratuito']))
        <div class="mb-3">
            <p class="text-muted">
                {{ $eventos->total() }} evento(s) encontrado(s)
                @if(request('search'))
                    para "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>
    @endif

    <!-- Lista de eventos -->
    <div class="row">
        @forelse($eventos as $evento)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                @if($evento->imagen)
                    <img src="{{ asset('storage/' . $evento->imagen) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="row mb-3">
                        <div class="col-md-3 text-center">
                            <div class="bg-primary text-white rounded p-3">
                                <div class="h4 mb-0">{{ $evento->fecha_inicio->format('d') }}</div>
                                <small>{{ $evento->fecha_inicio->format('M Y') }}</small>
                                <div class="mt-1">
                                    <small>{{ $evento->fecha_inicio->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="card-title">{{ $evento->titulo }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($evento->descripcion, 120) }}</p>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $evento->ubicacion }}
                                </small>
                            </div>
                            
                            @if($evento->precio > 0)
                            <div class="mb-2">
                                <span class="badge bg-info">
                                    <i class="fas fa-dollar-sign"></i> ${{ number_format($evento->precio, 2) }}
                                </span>
                            </div>
                            @else
                            <div class="mb-2">
                                <span class="badge bg-success">Evento Gratuito</span>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> {{ $evento->user->name }}
                                </small>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('eventos.show', $evento) }}" class="btn btn-primary btn-sm">
                                        Ver detalles
                                    </a>
                                    @can('update', $evento)
                                        <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4>No se encontraron eventos</h4>
                @if(request()->hasAny(['search', 'fecha_desde', 'fecha_hasta', 'precio_max', 'gratuito']))
                    <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                    <a href="{{ route('eventos.index') }}" class="btn btn-outline-primary">
                        Ver todos los eventos
                    </a>
                @else
                    <p class="text-muted">Sé el primero en organizar un evento cultural</p>
                    @auth
                        <a href="{{ route('eventos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Evento
                        </a>
                    @endauth
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($eventos->hasPages())
        <div class="d-flex justify-content-center">
            {{ $eventos->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection