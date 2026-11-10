<?php

namespace App\Http\Controllers\Parametrizar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sesiones;
use App\Models\Diario_debates;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; 
use App\Traits\Auditoria;
use Illuminate\Support\Facades\Auth;
use App\Exports\SesionesExport;
use Maatwebsite\Excel\Facades\Excel;

class SesionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
   {
       $this->middleware('auth');
       $this->middleware(function ($request, $next) {
       $this->user = Auth::user();
        return $next($request);
       });
   }

    public function index(Request $request)
    {
       
        $query = Sesiones::with('diarioDebates');

    // 1. Filtros sobre la tabla principal 'sesiones'
    if ($request->filled('naturaleza')) {
        $query->where('naturaleza', 'LIKE', '%' . $request->input('naturaleza') . '%');
    }

    if ($request->filled('fecha')) {
        $query->whereDate('fecha', $request->input('fecha'));
    }

    // 2. Filtros sobre la tabla relacionada 'diario_debates'
    if ($request->filled('sumario_cuenta')) {
        $query->whereHas('diarioDebates', function ($q) use ($request) {
            $q->where('sumario_cuenta', 'LIKE', '%' . $request->input('sumario_cuenta') . '%');
        });
    }

    if ($request->filled('sumario_orden_dia')) {
        $query->whereHas('diarioDebates', function ($q) use ($request) {
            $q->where('sumario_orden_dia', 'LIKE', '%' . $request->input('sumario_orden_dia') . '%');
        });
    }

    if ($request->filled('notas')) {
        $query->whereHas('diarioDebates', function ($q) use ($request) {
            $q->where('notas', 'LIKE', '%' . $request->input('notas') . '%');
        });
    }

    // Paginación manteniendo los parámetros de búsqueda en los enlaces de la paginación
    $sesiones = $query->orderBy('fecha', 'desc')->paginate(10);
        return view('Parametrizar.Sesiones.index', compact('sesiones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Parametrizar.Sesiones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // 1. Validación de campos de la Sesión y del array 'diario' (mínimo 1 fila)
    $request->validate([
        'naturaleza'            => 'required|string|max:255',
        'fecha'                 => 'required|date',
        'diario'                => 'required|array|min:1',
        'diario.*.nro_sumario'  => 'required|string|max:100',
        'diario.*.sumario_cuenta' => 'required|string', // Cambiado a nullable según tu vista
        'diario.*.sumario_orden_dia' => 'nullable|string',
        'diario.*.notas'        => 'nullable|string',
    ], [
        'diario.required' => 'Debe registrar al menos una fila en el detalle del diario de debates.',
        'diario.min'      => 'Debe registrar al menos una fila en el detalle del diario de debates.',
        'diario.*.nro_sumario.required' => 'El número de sumario es obligatorio en todas las filas.',
    ]);

    DB::beginTransaction();  
    try {
        // Verificar si ya existe la sesión
        $tipos = Sesiones::where("naturaleza", $request->naturaleza)
                          ->where('fecha', $request->fecha)
                          ->first();
                          
        if ($tipos != null) {
            DB::rollBack();
            return back()->withInput()->with('mensajeError', 'Diario Ya Registrado');
        }

        // Crear la Sesión
        $sesion = new Sesiones;
        $sesion->naturaleza = $request->naturaleza;
        $sesion->tipo = $request->tipo;
        $sesion->tomo = $request->tomo;
        $sesion->fecha = $request->fecha;
        $sesion->user_id = Auth()->id();
        $sesion->save();

        // Registrar los detalles del diario de debates

       
            #dd($request->input('diario'));
            foreach ($request->input('diario') as $item) {
                $diario = new Diario_debates();
                $diario->sesion_id           = $sesion->id;
                $diario->nro_sumario         = $item['nro_sumario'];
                $diario->sumario_cuenta      = $item['sumario_cuenta'] ?? null;
                $diario->sumario_orden_dia   = $item['sumario_orden_dia'] ?? null;
                $diario->notas               = $item['notas'] ?? null;
                $diario->user_id             = Auth()->id();
                $diario->save();
            }
        

        DB::commit();
        return redirect()->route('sesiones.index')
                         ->with('mensaje', 'El diario de debates ha sido registrado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('mensajeError', 'Error al registrar la sesión: ' . $e->getMessage());
    }
}
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sesion = Sesiones::with('diariodebates')->findOrFail($id);

    return view('Parametrizar.Sesiones.show', compact('sesion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sesion = Sesiones::findOrFail($id);
    return view('Parametrizar.Sesiones.edit', compact('sesion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
   public function update(Request $request, $id)
{
    $request->validate([
        'naturaleza'                => 'required|string|max:255',
        'fecha'                      => 'required|date',
        'diario'                     => 'required|array|min:1',
        'diario.*.nro_sumario'       => 'required|string|max:100',
        'diario.*.sumario_cuenta'    => 'required|string',
        'diario.*.sumario_orden_dia' => 'nullable|string',
        'diario.*.notas'             => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        $sesion = Sesiones::findOrFail($id);
        $sesion->naturaleza = $request->input('naturaleza');
        $sesion->fecha      = $request->input('fecha');
        $sesion->save();

        $idsEnviados = [];

        foreach ($request->input('diario') as $item) {
            // Evaluamos de forma segura si el ítem trae un ID válido
            $idDiario = isset($item['id']) && !empty($item['id']) ? $item['id'] : null;

            if ($idDiario) {
                // Si existe el ID, buscamos el registro existente
                $diario = Diario_debates::where('sesion_id', $sesion->id)->find($idDiario);
            } else {
                // Si NO hay ID (o viene vacío), creamos una instancia totalmente nueva
                $diario = new Diario_debates();
            }

            // Si por alguna razón no lo encuentra pero venía un ID viejo, creamos uno nuevo como fallback
            if (!$diario) {
                $diario = new Diario_debates();
            }

            $diario->sesion_id         = $sesion->id;
            $diario->nro_sumario       = $item['nro_sumario'];
            $diario->sumario_cuenta    = $item['sumario_cuenta'] ?? null;
            $diario->sumario_orden_dia = $item['sumario_orden_dia'] ?? null;
            $diario->notas             = $item['notas'] ?? null;
            $diario->user_id           = auth()->id();
            $diario->save();

            $idsEnviados[] = $diario->id;
        }

        // Si deseas eliminar en la BD los diarios que el usuario eliminó en la vista:
        Diario_debates::where('sesion_id', $sesion->id)
            ->whereNotIn('id', $idsEnviados)
            ->delete();

        DB::commit();

        return redirect()->route('sesiones.index')->with('success', 'La sesión y sus diarios se actualizaron correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('mensajeError', 'Error al actualizar: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $acto = Sesiones::findOrFail($id);
       $acto->destroy($id);

    return redirect()->route('sesiones.index')
        ->with('success', 'La sesión ha sido eliminado.');
    }


    public function exportarExcel(Request $request)
{
    return Excel::download(new SesionesExport($request->all()), 'diario_de_debates_' . date('Ymd_His') . '.xlsx');
}
}
