@extends('layouts.app')

@section('title', 'Editar Tradición - CulturApp')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tradiciones.index') }}">Tradiciones</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tradiciones.show', $tradicion) }}">{{ $tradicion->titulo }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-edit text-primary"></i> Editar Tradición</h4>
                    <p class="text-muted mb-0 mt-1">Actualiza la información de esta tradición cultural</p>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('tradiciones.update', $tradicion) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información básica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Información Básica</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" name="titulo" value="{{ old('titulo', $tradicion->titulo) }}" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="categoria_id" class="form-label">Categoría *</label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                        id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" 
                                                {{ old('categoria_id', $tradicion->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $tradicion->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="historia" class="form-label">Historia (Opcional)</label>
                                <textarea class="form-control" id="historia" name="historia" rows="3">{{ old('historia', $tradicion->historia) }}</textarea>
                                <div class="form-text">Cuenta la historia o el origen de esta tradición</div>
                            </div>
                        </div>
                        
                        <!-- Imágenes actuales -->
                        @if($tradicion->imagen_principal)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Imagen Actual</h5>
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('storage/' . $tradicion->imagen_principal) }}" 
                                     class="img-fluid rounded" alt="Imagen actual">
                                <p class="text-muted mt-2">Imagen principal actual</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Ubicación -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Ubicación</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" 
                                       value="{{ old('ubicacion', $tradicion->ubicacion) }}" 
                                       placeholder="Ej: Tula de Allende, Hidalgo">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_celebracion" class="form-label">Fecha de Celebración</label>
                                <input type="date" class="form-control" id="fecha_celebracion" name="fecha_celebracion" 
                                       value="{{ old('fecha_celebracion', $tradicion->fecha_celebracion?->format('Y-m-d')) }}">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="latitud" class="form-label">Latitud</label>
                                <input type="number" step="any" class="form-control" id="latitud" name="latitud" 
                                       value="{{ old('latitud', $tradicion->latitud) }}" placeholder="20.0530">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="longitud" class="form-label">Longitud</label>
                                <input type="number" step="any" class="form-control" id="longitud" name="longitud" 
                                       value="{{ old('longitud', $tradicion->longitud) }}" placeholder="-99.3427">
                            </div>
                        </div>
                        
                        <!-- Multimedia -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Actualizar Multimedia (Opcional)</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="imagen_principal" class="form-label">Nueva Imagen Principal</label>
                                <input type="file" class="form-control" id="imagen_principal" name="imagen_principal" accept="image/*">
                                <div class="form-text">Formatos: JPG, PNG, GIF (máx. 2MB). Dejalo vacío para mantener la imagen actual.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="video_url" class="form-label">URL de Video</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                       value="{{ old('video_url', $tradicion->video_url) }}" placeholder="https://...">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="audio_url" class="form-label">URL de Audio</label>
                                <input type="url" class="form-control" id="audio_url" name="audio_url" 
                                       value="{{ old('audio_url', $tradicion->audio_url) }}" placeholder="https://...">
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tradiciones.show', $tradicion) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Tradición
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection