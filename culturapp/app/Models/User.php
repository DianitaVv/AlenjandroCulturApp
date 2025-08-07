<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    // Constantes para roles
    const ROLE_ADMIN = 'admin';
    const ROLE_COLABORADOR = 'colaborador';
    const ROLE_USUARIO = 'usuario';

    // Métodos para verificar roles
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isColaborador()
    {
        return $this->role === self::ROLE_COLABORADOR;
    }

    public function isUsuario()
    {
        return $this->role === self::ROLE_USUARIO;
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function canManageContent()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_COLABORADOR]);
    }

    // Método para obtener el nombre del rol formateado
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_COLABORADOR => 'Colaborador Cultural',
            self::ROLE_USUARIO => 'Usuario',
            default => 'Usuario'
        };
    }

    // Método para obtener el color del badge del rol
    public function getRoleBadgeClassAttribute()
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'bg-danger',
            self::ROLE_COLABORADOR => 'bg-success',
            self::ROLE_USUARIO => 'bg-primary',
            default => 'bg-secondary'
        };
    }

    // Relaciones
    public function tradiciones()
    {
        return $this->hasMany(Tradicion::class);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    public function testimonios()
    {
        return $this->hasMany(Testimonio::class);
    }

    public function sitiosCulturales()
    {
        return $this->hasMany(SitioCultural::class);
    }

    // Scope para usuarios activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}