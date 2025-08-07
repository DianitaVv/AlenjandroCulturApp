@extends('layouts.app')

@section('title', 'Agregar Sitio Cultural - CulturApp')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sitios-culturales.index') }}">Sitios Culturales</a></li>
            <li class="breadcrumb-item active">Agregar Sitio Cultural</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-plus text-primary"></i> Agregar Nuevo Sitio Cultural</h4>
                    <p class="text-muted mb-0 mt-1">Comparte un lugar con valor histórico y cultural</p>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('sitios-culturales.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Información básica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Información Básica</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre del Sitio *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <select class="form-select @error('tipo') is-invalid @enderror" 
                                        id="tipo" name="tipo" required>
                                    <option value="">Selecciona el tipo</option>
                                    <option value="museo" {{ old('tipo') == 'museo' ? 'selected' : '' }}>Museo</option>
                                    <option value="iglesia" {{ old('tipo') == 'iglesia' ? 'selected' : '' }}>Iglesia</option>
                                    <option value="plaza" {{ old('tipo') == 'plaza' ? 'selected' : '' }}>Plaza</option>
                                    <option value="monumento" {{ old('tipo') == 'monumento' ? 'selected' : '' }}>Monumento</option>
                                    <option value="mercado" {{ old('tipo') == 'mercado' ? 'selected' : '' }}>Mercado</option>
                                    <option value="centro_historico" {{ old('tipo') == 'centro_historico' ? 'selected' : '' }}>Centro Histórico</option>
                                    <option value="zona_arqueologica" {{ old('tipo') == 'zona_arqueologica' ? 'selected' : '' }}>Zona Arqueológica</option>
                                    <option value="edificio_historico" {{ old('tipo') == 'edificio_historico' ? 'selected' : '' }}>Edificio Histórico</option>
                                    <option value="otro" {{ old('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="historia" class="form-label">Historia (Opcional)</label>
                                <textarea class="form-control" id="historia" name="historia" rows="3">{{ old('historia') }}</textarea>
                                <div class="form-text">Cuenta la historia o importancia cultural de este lugar</div>
                            </div>
                        </div>
                        
                        <!-- Ubicación -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Ubicación *</h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="direccion" class="form-label">Dirección Completa *</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror" 
                                       id="direccion" name="direccion" value="{{ old('direccion') }}" 
                                       placeholder="Ej: Calle 5 de Mayo #123, Centro, Tula de Allende, Hidalgo" required>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="latitud" class="form-label">Latitud *</label>
                                <input type="number" step="any" class="form-control @error('latitud') is-invalid @enderror" 
                                       id="latitud" name="latitud" value="{{ old('latitud') }}" 
                                       placeholder="20.0530" required>
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Coordenadas para mostrar en el mapa</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="longitud" class="form-label">Longitud *</label>
                                <input type="number" step="any" class="form-control @error('longitud') is-invalid @enderror" 
                                       id="longitud" name="longitud" value="{{ old('longitud') }}" 
                                       placeholder="-99.3427" required>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Coordenadas para mostrar en el mapa</div>
                            </div>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Información Adicional</h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="horarios" class="form-label">Horarios de Visita</label>
                                <input type="text" class="form-control" id="horarios" name="horarios" 
                                       value="{{ old('horarios') }}" 
                                       placeholder="Ej: Lunes a Viernes 9:00 - 17:00, Sábados 10:00 - 14:00">
                            </div>
                        </div>
                        
                        <!-- Multimedia -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Imágenes (Opcional)</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="imagen" class="form-label">Imagen Principal</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                <div class="form-text">Formatos: JPG, PNG, GIF (máx. 2MB)</div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="galeria_imagenes" class="form-label">Galería de Imágenes</label>
                                <input type="file" class="form-control" id="galeria_imagenes" name="galeria_imagenes[]" 
                                       accept="image/*" multiple>
                                <div class="form-text">Puedes seleccionar múltiples imágenes. Formatos: JPG, PNG, GIF (máx. 2MB c/u)</div>
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sitios-culturales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Sitio Cultural
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection