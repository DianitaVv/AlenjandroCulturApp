<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonio extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'nombre_narrador',
        'edad_narrador',
        'audio_url',
        'video_url',
        'imagen',
        'tipo',
        'user_id',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para testimonios activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}