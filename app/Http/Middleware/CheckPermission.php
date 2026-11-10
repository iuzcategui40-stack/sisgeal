<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $modulo  Ej: 'sesiones', 'tiposactos'
     * @param  string  $accion  Ej: 'view', 'create', 'edit', 'delete'
     */
    public function handle(Request $request, Closure $next, string $modulo, string $accion): Response
    {
        $user = $request->user();
        #dd($user);
        // 1. Verificar si el usuario está autenticado y tiene un rol asignado
      /*  dd([
        'usuario' => $user->nombre,
        'role_id' => $user->rol_id ?? 'No definido',
        'modulo_buscado' => $modulo,
        'accion_buscada' => $accion,
        'tiene_permiso_metodo' => $user->tienePermiso($modulo, $accion)
    ]);*/
        if (!$user || !$user->rol_id) {
            abort(403, 'No tienes un rol asignado o no has iniciado sesión.');
        }

        // 2. Buscar si el rol tiene el permiso específico para este módulo
        $permiso = RolePermission::where('role_id', $user->rol_id)
                                 ->where('module', $modulo)
                                 ->first();

        if (!$permiso) {
            abort(403, 'No tienes autorización para acceder a este módulo.');
        }

        // 3. Evaluar la acción solicitada usando la columna correspondiente
        $tieneAcceso = match($accion) {
            'view'   => $permiso->can_view,
            'create' => $permiso->can_create,
            'edit'   => $permiso->can_edit,
            'delete' => $permiso->can_delete,
            default  => false,
        };

        if (!$tieneAcceso) {
            abort(403, 'No cuentas con permisos para realizar esta acción (' . $accion . ') en el módulo: ' . $modulo);
        }

        return $next($request);
    }
}
