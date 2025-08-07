<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'ubicacion',
        'latitud',
        'longitud',
        'imagen',
        'contacto',
        'telefono',
        'precio',
        'user_id',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'precio' => 'decimal:2',
        'activo' => 'boolean'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para eventos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para eventos próximos
    public function scopeProximos($query)
    {
        return $query->where('fecha_inicio', '>=', now());
    }
}