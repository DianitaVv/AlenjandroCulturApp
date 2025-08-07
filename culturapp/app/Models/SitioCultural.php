<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitioCultural extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'historia',
        'latitud',
        'longitud',
        'direccion',
        'imagen',
        'galeria_imagenes',
        'tipo',
        'horarios',
        'user_id',
        'activo'
    ];

    protected $casts = [
        'galeria_imagenes' => 'array',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'activo' => 'boolean'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para sitios activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}