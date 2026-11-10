<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id',
        'user_id', // <-- ASEGÚRATE DE QUE ESTÉ AQUÍ
        'module',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}