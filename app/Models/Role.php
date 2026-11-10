<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles'; // O el nombre de tu tabla de roles si es diferente

    protected $fillable = [
        'name',
        'description',
        'id_usuario',
    ];

    // <-- AGREGA ESTA RELACIÓN AQUÍ -->
    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}