<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TradicionController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TestimonioController;
use App\Http\Controllers\SitioCulturalController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\Auth\AuthController;

// Rutas públicas (para todos los visitantes)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de tradiciones - TODAS LAS RUTAS
Route::get('/tradiciones', [TradicionController::class, 'index'])->name('tradiciones.index');
Route::get('/tradiciones/{tradicion}', [TradicionController::class, 'show'])->name('tradiciones.show');

// Rutas de eventos
Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

// Rutas de sitios culturales
Route::get('/sitios-culturales', [SitioCulturalController::class, 'index'])->name('sitios-culturales.index');
Route::get('/sitios-culturales/{sitioCultural}', [SitioCulturalController::class, 'show'])->name('sitios-culturales.show');

// Ruta del mapa
Route::get('/mapa', [MapaController::class, 'index'])->name('mapa.index');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    // Tradiciones - crear, editar, actualizar, eliminar
    Route::get('/tradiciones/create', [TradicionController::class, 'create'])->name('tradiciones.create');
    Route::post('/tradiciones', [TradicionController::class, 'store'])->name('tradiciones.store');
    Route::get('/tradiciones/{tradicion}/edit', [TradicionController::class, 'edit'])->name('tradiciones.edit');
    Route::put('/tradiciones/{tradicion}', [TradicionController::class, 'update'])->name('tradiciones.update');
    Route::delete('/tradiciones/{tradicion}', [TradicionController::class, 'destroy'])->name('tradiciones.destroy');
    
    // Eventos
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    
    // Sitios Culturales
    Route::get('/sitios-culturales/create', [SitioCulturalController::class, 'create'])->name('sitios-culturales.create');
    Route::post('/sitios-culturales', [SitioCulturalController::class, 'store'])->name('sitios-culturales.store');
    Route::get('/sitios-culturales/{sitioCultural}/edit', [SitioCulturalController::class, 'edit'])->name('sitios-culturales.edit');
    Route::put('/sitios-culturales/{sitioCultural}', [SitioCulturalController::class, 'update'])->name('sitios-culturales.update');
    Route::delete('/sitios-culturales/{sitioCultural}', [SitioCulturalController::class, 'destroy'])->name('sitios-culturales.destroy');
    
    // Like en tradiciones
    Route::post('/tradiciones/{tradicion}/like', [TradicionController::class, 'like'])->name('tradiciones.like');
});

// Rutas para administradores
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});