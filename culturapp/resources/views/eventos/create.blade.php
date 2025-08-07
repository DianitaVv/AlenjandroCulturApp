@extends('layouts.app')

@section('title', 'Agregar Evento - CulturApp')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos</a></li>
            <li class="breadcrumb-item active">Agregar Evento</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus text-primary"></i> Agregar Nuevo Evento</h4>
                    <p class="text-muted mb-0 mt-1">Comparte un evento cultural de tu región</p>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Información básica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Información Básica</h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="titulo" class="form-label">Título del Evento *</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                                @error('titulo')
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
                        </div>
                        
                        <!-- Fechas -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Fechas y Horarios</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio *</label>
                                <input type="datetime-local" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                       id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label">Fecha y Hora de Fin (Opcional)</label>
                                <input type="datetime-local" class="form-control" 
                                       id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                            </div>
                        </div>
                        
                        <!-- Ubicación -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Ubicación</h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="ubicacion" class="form-label">Ubicación *</label>
                                <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" 
                                       id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" 
                                       placeholder="Ej: Plaza Principal, Tula de Allende, Hidalgo" required>
                                @error('ubicacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="latitud" class="form-label">Latitud (Opcional)</label>
                                <input type="number" step="any" class="form-control" id="latitud" name="latitud" 
                                       value="{{ old('latitud') }}" placeholder="20.0530">
                                <div class="form-text">Para mostrar el evento en el mapa</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="longitud" class="form-label">Longitud (Opcional)</label>
                                <input type="number" step="any" class="form-control" id="longitud" name="longitud" 
                                       value="{{ old('longitud') }}" placeholder="-99.3427">
                                <div class="form-text">Para mostrar el evento en el mapa</div>
                            </div>
                        </div>
                        
                        <!-- Contacto y precio -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Información de Contacto</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contacto" class="form-label">Email de Contacto</label>
                                <input type="email" class="form-control" id="contacto" name="contacto" 
                                       value="{{ old('contacto') }}" placeholder="contacto@ejemplo.com">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="{{ old('telefono') }}" placeholder="771-123-4567">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label">Precio (MXN)</label>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" 
                                       value="{{ old('precio', 0) }}" min="0">
                                <div class="form-text">Deja en 0 si es gratuito</div>
                            </div>
                        </div>
                        
                        <!-- Imagen -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Imagen del Evento (Opcional)</h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="imagen" class="form-label">Imagen Principal</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                <div class="form-text">Formatos: JPG, PNG, GIF (máx. 2MB)</div>
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('eventos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection