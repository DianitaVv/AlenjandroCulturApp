<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tradicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'historia',
        'imagen_principal',
        'galeria_imagenes',
        'video_url',
        'audio_url',
        'ubicacion',
        'latitud',
        'longitud',
        'fecha_celebracion',
        'categoria_id',
        'user_id',
        'activo',
        'likes'
    ];

    protected $casts = [
        'galeria_imagenes' => 'array',
        'fecha_celebracion' => 'date',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para tradiciones activas
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}