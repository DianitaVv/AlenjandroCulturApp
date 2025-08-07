@extends('layouts.app')

@section('title', 'Registrarse - CulturApp')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> Registrarse</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" 
                                       id="password-confirm" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono (Opcional)</label>
                                <input type="tel" class="form-control" 
                                       id="telefono" name="telefono" value="{{ old('telefono') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Tipo de Usuario</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                    <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>
                                        Usuario Normal
                                    </option>
                                    <option value="colaborador" {{ old('role') == 'colaborador' ? 'selected' : '' }}>
                                        Colaborador Cultural
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biografía (Opcional)</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" 
                                      placeholder="Cuéntanos un poco sobre ti...">{{ old('bio') }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">¿Ya tienes cuenta? 
                                <a href="{{ route('login') }}">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection