<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    // Módulos definidos en tu menú del sistema
    private $modulosDisponibles = [
        'inicio'              => 'Inicio',
        'tiposactos'          => 'Parametrización - Tipo Acto',
        'actoslegislativos'   => 'Registro - Actos Legislativos',
        'sesiones'            => 'Registro - Diario de Debates',
        'usuarios'            => 'Seguridad - Usuarios',
        'roles'               => 'Seguridad - Roles y Permisos'
    ];

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $modulos = $this->modulosDisponibles;
        return view('roles.create', compact('modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name',
            'description' => 'required|string|max:50',
            'permisos'    => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $rol = new Role();
            $rol->name = $request->name;
            $rol->description = $request->description;
            $rol->user_id = Auth::id();
            $rol->save();

           if ($request->has('permisos')) {
    foreach ($request->permisos as $modulo => $acciones) {
        $permiso = new RolePermission();
        $permiso->role_id    = $rol->id;
        $permiso->user_id    = Auth::id(); // Asigna directamente el usuario autenticado
        $permiso->module     = $modulo;
        $permiso->can_view   = isset($acciones['view']) ? 1 : 0;
        $permiso->can_create = isset($acciones['create']) ? 1 : 0;
        $permiso->can_edit   = isset($acciones['edit']) ? 1 : 0;
        $permiso->can_delete = isset($acciones['delete']) ? 1 : 0;
        $permiso->save();
    }
            }

            DB::commit();
            return redirect()->route('roles.index')->with('mensaje', 'Rol y permisos registrados exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('mensajeError', 'Error al registrar el rol: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $rol = Role::findOrFail($id);
        $modulos = $this->modulosDisponibles;
        $permisosActuales = RolePermission::where('role_id', $rol->id)->get()->keyBy('module');

        return view('roles.edit', compact('rol', 'modulos', 'permisosActuales'));
    }

    public function update(Request $request, $id)
    {
        $rol = Role::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name,' . $rol->id,
            'description' => 'required|string|max:50',
            'permisos'    => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $rol->name = $request->name;
            $rol->description = $request->description;
            $rol->save();

            // Limpiamos permisos anteriores y asignamos los nuevos
            RolePermission::where('role_id', $rol->id)->delete();
            #dd(Auth::user()->id);
           if ($request->has('permisos')) {
    foreach ($request->permisos as $modulo => $acciones) {
        $permiso = new RolePermission();
        $permiso->role_id    = $rol->id;
        $permiso->user_id    = Auth::id(); // Asigna directamente el usuario autenticado
        $permiso->module     = $modulo;
        $permiso->can_view   = isset($acciones['view']) ? 1 : 0;
        $permiso->can_create = isset($acciones['create']) ? 1 : 0;
        $permiso->can_edit   = isset($acciones['edit']) ? 1 : 0;
        $permiso->can_delete = isset($acciones['delete']) ? 1 : 0;
        $permiso->save();
    }
}

            DB::commit();
            return redirect()->route('roles.index')->with('mensaje', 'Rol y permisos actualizados exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('mensajeError', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $rol = Role::findOrFail($id);
            $rol->delete();
            return redirect()->route('roles.index')->with('mensaje', 'Rol eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('mensajeError', 'No se puede eliminar el rol porque está vinculado a usuarios.');
        }
    }
}