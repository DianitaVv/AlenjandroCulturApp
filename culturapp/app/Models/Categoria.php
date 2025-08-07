<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono'
    ];

    // Relación con tradiciones
    public function tradiciones()
    {
        return $this->hasMany(Tradicion::class);
    }
}