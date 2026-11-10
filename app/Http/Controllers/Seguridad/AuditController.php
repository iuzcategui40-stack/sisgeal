<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit; // O ajusta según la ruta de tu modelo de auditoría

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:audits,view'); // Descomenta si usas Spatie Permissions
    }

    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $evento = $request->get('evento'); // created, updated, deleted
        $fecha_inicio = $request->get('fecha_inicio');
        $fecha_fin = $request->get('fecha_fin');

        $audits = Audit::with('user') // Asumiendo que la tabla audits tiene relación con el usuario
            ->when($buscar, function ($query, $buscar) {
                return $query->where('event', 'LIKE', "%{$buscar}%")
                             ->orWhere('auditable_type', 'LIKE', "%{$buscar}%")
                             ->orWhere('ip_address', 'LIKE', "%{$buscar}%");
            })
            ->when($evento, function ($query, $evento) {
                return $query->where('event', $evento);
            })
            ->when($fecha_inicio && $fecha_fin, function ($query) use ($fecha_inicio, $fecha_fin) {
                return $query->whereBetween('created_at', [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59']);
            })
            ->latest()
            ->paginate(15)
            ->appends($request->all());

        return view('Seguridad.audits.index', compact('audits', 'buscar', 'evento', 'fecha_inicio', 'fecha_fin'));
    }

    public function show($id)
    {
        $audit = Audit::with('user')->findOrFail($id);
        return view('Seguridad.audits.show', compact('audit'));
    }
}