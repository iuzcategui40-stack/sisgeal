<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
public function role()
{
    return $this->belongsTo(Role::class, 'rol_id'); // O 'id_rol' según tu BD
}

    public function tienePermiso($modulo, $accion)
{
    // Cambia $this->role_id por el nombre real de tu columna, ej: $this->id_rol o $this->perfil_id
    $roleId = $this->rol_id ?? $this->id_rol ?? $this->perfil_id; 

    if (!$roleId) return false;

    $permiso = \App\Models\RolePermission::where('role_id', $roleId)
                             ->where('module', $modulo)
                             ->first();

    if (!$permiso) return false;

    return match($accion) {
        'view'   => (bool) $permiso->can_view,
        'create' => (bool) $permiso->can_create,
        'edit'   => (bool) $permiso->can_edit,
        'delete' => (bool) $permiso->can_delete,
        default  => false,
    };
}

}
