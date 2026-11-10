<?php

namespace App\Http\Controllers\Parametrizar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActosLegislativos;
use App\Models\TipoActos;
use App\Models\Discusiones;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon; 
use App\Traits\Auditoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActosLegislativosExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ActosLegislativosController extends Controller
{
    // Declaración explícita de la propiedad para evitar advertencias de PHP
    protected $user;

    /**
     * Constructor del controlador con middlewares.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Obtener todos los tipos de actos para llenar el select del filtro
        $tipos = TipoActos::all();

        // 2. Construir la consulta base usando Eloquent con sus relaciones
        $query = ActosLegislativos::with('tipoacto')->latest();

        // 3. Aplicar Filtro: Palabra Clave (Buscar)
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                  ->orWhere('codigo', 'LIKE', "%{$buscar}%")
                  ->orWhere('resumen', 'LIKE', "%{$buscar}%");
            });
        }

        // 4. Aplicar Filtro: Tipo de Acto
        if ($request->filled('tipo_acto')) {
            $query->where('tipoacto_id', $request->input('tipo_acto'));
        }

        // 5. Aplicar Filtro: Estatus
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->input('estatus'));
        }

        // 6. Aplicar Filtro: Fecha Iniciativa
        if ($request->filled('fecha_iniciativa')) {
            $query->whereDate('fecha_iniciativa', $request->input('fecha_iniciativa'));
        }

        // 7. Aplicar Filtro: Fecha Aprobación
        if ($request->filled('fecha_aprobacion')) {
            $query->whereDate('fecha_aprobacion', $request->input('fecha_aprobacion'));
        }

        // Paginar los resultados manteniendo el tamaño compacto (10 por página)
        $actos = $query->paginate(10);
        
        return view('Parametrizar.actos.index', compact('actos', 'tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoActos::orderBy("descripcion", "asc")->get();
        return view('Parametrizar.actos.create', compact('tipos'));
    }

  public function crear()
    {
        $tipos = TipoActos::orderBy("descripcion", "asc")->get();
        return view('Parametrizar.actos.create', compact('tipos'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'titulo'            => 'required|string',
                'tipo_acto'         => 'required|integer',
                'codigo_expediente' => 'required|string|max:20',
                'fecha_aprobacion'  => 'required|date',
                'estatus'           => 'required',
            ]);

            $actos = ActosLegislativos::where("titulo", $request->titulo)->first();
            
            if ($actos != NULL) {
                DB::rollBack();
                Session::flash('mensajeError', 'Acto Ya Registrado');
                return back()->withInput();
            }

            if ($request->hasFile('archivo_pdf')) {
                $nombre = "acto".$request->tipo_acto;
                $archivo_nombre = Str::slug($nombre, '-');
                $archivo_nombre = $archivo_nombre.'-'.Carbon::now()->format('YmdHis');       
                $name = $archivo_nombre.'.'.$request->archivo_pdf->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('doc/actos', new File($request->archivo_pdf), $name, 'public');
            } else {
                $name = "";
            } 

            $actos = new ActosLegislativos;
            $actos->titulo                = $request->titulo;
            $actos->tipoacto_id           = $request->tipo_acto;
            $actos->codigo                = $request->codigo_expediente;
            $actos->resumen               = $request->resumen;
            $actos->fecha_iniciativa      = $request->fecha_iniciativa;
            $actos->fecha_aprobacion      = $request->fecha_aprobacion;
            $actos->fecha_distribucion    = $request->fecha_distribucion;
            $actos->gaceta_oficial        = $request->nro_gaceta; 
            $actos->fecha_gaceta_oficial  = $request->fecha_gaceta; 
            $actos->estatus               = $request->estatus;
            $actos->enviado               = $request->enviado;
             $actos->fecha_sancion    = $request->fecha_sancion;
            $actos->url_archivo           = $name;
            $actos->user_id               = auth()->id();
            $actos->save();

            if (isset($request->discusiones)) {
                $this->creardiscusiones($actos, $request->discusiones, $request);  
            }
            
            DB::commit();

            return redirect()->route('actoslegislativos.index')
                ->with('success', 'El Acto Legislativo ha sido registrado exitosamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('mensajeError', 'Error al registrar el tipo de Acto: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Crear discusiones asociadas a un acto legislativo.
     */
    public function creardiscusiones($actos, $zdiscusiones, $request)
    {
        foreach ($zdiscusiones as $index => $disc) {
            if (empty($disc['fecha']) || empty($disc['tipo'])) {
                continue;
            }

            $name = "";

            if ($request->hasFile("discusiones.{$index}.documento")) {
                $archivoCargado = $request->file("discusiones.{$index}.documento");
                $nombre = "discusion-acto-".$actos->id;
                $archivo_nombre = Str::slug($nombre, '-').'-'.Carbon::now()->format('YmdHis').'-'.$index;       
                $name = $archivo_nombre.'.'.$archivoCargado->getClientOriginalExtension();
                
                Storage::disk('public')->putFileAs('doc/actos/discusiones', new File($archivoCargado), $name, 'public');
            }

            $discusiones = new Discusiones; 
            $discusiones->actolegislativo_id = $actos->id;
            $discusiones->fecha              = $disc["fecha"];
            $discusiones->aprobado           = $disc["aprobado"] ?? null; 
            $discusiones->tipo               = $disc["tipo"];
            $discusiones->observaciones      = $disc["observaciones"] ?? null;
            $discusiones->url_archivo        = $name;
            $discusiones->user_id            = auth()->id();
            $discusiones->save();
        }

        return 1;
    }

  public function show($id)
    {
        $acto = ActosLegislativos::with('discusiones')->findOrFail($id);
        $tipos = TipoActos::all();
        return view('Parametrizar.actos.show', compact('acto', 'tipos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $acto = ActosLegislativos::with('discusiones')->findOrFail($id);
        $tipos = TipoActos::all();
        return view('Parametrizar.actos.edit', compact('acto', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $acto = ActosLegislativos::findOrFail($id);
        $request->validate([
            'titulo'            => 'required|string',
            'tipo_acto'         => 'required|integer',
            'codigo_expediente' => 'required|string|max:20',
            'estatus'           => 'required',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('archivo_pdf')){
                if (!empty($acto->url_archivo)) {
                    Storage::disk('public')->delete('doc/actos/' . $acto->url_archivo);
                }
                
                $nombre = "acto".$request->tipo_acto;
                $archivo_nombre = Str::slug($nombre, '-').'-'.Carbon::now()->format('YmdHis');       
                $name = $archivo_nombre.'.'.$request->archivo_pdf->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('doc/actos', new File($request->archivo_pdf), $name, 'public');
                $acto->url_archivo = $name;
            }

            $acto->titulo                = $request->titulo;
            $acto->tipoacto_id           = $request->tipo_acto;
            $acto->codigo                = $request->codigo_expediente;
            $acto->resumen               = $request->resumen;
            $acto->fecha_iniciativa      = $request->fecha_iniciativa;
            $acto->fecha_aprobacion      = $request->fecha_aprobacion;
            $acto->gaceta_oficial        = $request->nro_gaceta;
            $acto->fecha_gaceta_oficial  = $request->fecha_gaceta;
            $acto->estatus               = $request->estatus;
            $acto->enviado               = $request->enviado;
            $acto->fecha_distribucion    = $request->fecha_distribucion;
             $acto->fecha_sancion    = $request->fecha_sancion;
            $acto->save();

            $discusionesPreservadasIds = [];

            if (isset($request->discusiones) && is_array($request->discusiones)) {
                foreach ($request->discusiones as $index => $disc) {
                    if (empty($disc['fecha']) || empty($disc['tipo'])) {
                        continue;
                    }

                    if (isset($disc['id']) && !empty($disc['id'])) {
                        $discusion = Discusiones::findOrFail($disc['id']);
                        $discusionesPreservadasIds[] = $discusion->id;
                    } else {
                        $discusion = new Discusiones;
                        $discusion->actolegislativo_id = $acto->id;
                    }

                    if ($request->hasFile("discusiones.{$index}.documento")) {
                        if (!empty($discusion->url_archivo)) {
                            Storage::disk('public')->delete('doc/actos/discusiones/' . $discusion->url_archivo);
                        }

                        $archivoCargado = $request->file("discusiones.{$index}.documento");
                        $nombreDisc = "discusion-acto-".$acto->id;
                        $archivo_nombre_disc = Str::slug($nombreDisc, '-').'-'.Carbon::now()->format('YmdHis').'-'.$index;       
                        $nameDisc = $archivo_nombre_disc.'.'.$archivoCargado->getClientOriginalExtension();
                        
                        Storage::disk('public')->putFileAs('doc/actos/discusiones', new File($archivoCargado), $nameDisc, 'public');
                        $discusion->url_archivo = $nameDisc;
                    }

                    $discusion->fecha         = $disc["fecha"];
                    $discusion->aprobado      = $disc["aprobado"] ?? null;
                    $discusion->tipo          = $disc["tipo"];
                    $discusion->observaciones = $disc["observaciones"] ?? null;
                    $discusion->user_id       = auth()->id();
                    $discusion->save();
                    
                    if (!isset($disc['id'])) {
                        $discusionesPreservadasIds[] = $discusion->id;
                    }
                }

                Discusiones::where('actolegislativo_id', $acto->id)
                    ->whereNotIn('id', $discusionesPreservadasIds)
                    ->each(function ($discBorrar) {
                        if (!empty($discBorrar->url_archivo)) {
                            Storage::disk('public')->delete('doc/actos/discusiones/' . $discBorrar->url_archivo);
                        }
                        $discBorrar->delete();
                    });
            } else {
                Discusiones::where('actolegislativo_id', $acto->id)->each(function ($discBorrar) {
                    if (!empty($discBorrar->url_archivo)) {
                        Storage::disk('public')->delete('doc/actos/discusiones/' . $discBorrar->url_archivo);
                    }
                    $discBorrar->delete();
                });
            }

            DB::commit();
            
            return redirect()->route('actoslegislativos.index')
                             ->with('success', 'El Acto Legislativo y su historial se actualizaron de manera exitosa.');

        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('mensajeError', 'Error crítico al actualizar el Acto: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $acto = ActosLegislativos::findOrFail($id);

        DB::beginTransaction();
        try {
            $discusiones = $acto->discusiones; 

            foreach ($discusiones as $discusion) {
                if (!empty($discusion->url_archivo)) {
                    Storage::disk('public')->delete('doc/actos/discusiones/' . $discusion->url_archivo);
                }
                $discusion->delete();
            }

            if (!empty($acto->url_archivo)) {
                Storage::disk('public')->delete('doc/actos/' . $acto->url_archivo);
            }

            $acto->delete();
            DB::commit();

            return redirect()->route('actoslegislativos.index')
                ->with('success', 'El Acto Legislativo, sus discusiones e historiales de archivos han sido eliminados de forma permanente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('actoslegislativos.index')
                ->with('mensajeError', 'No se pudo eliminar el Acto Legislativo debido a un error crítico: ' . $e->getMessage());
        }
    }

    /**
     * Exportar reportes.
     */
    public function exportarExcel(Request $request)
    {
        return Excel::download(
        new ActosLegislativosExport($request), 
        'reporte_actos_legislativos_' . now()->format('YmdHis') . '.xlsx'
        );
    }

   public function exportarPDF(Request $request)
    {

        // 1. Reutilizamos la misma lógica de query con filtros que tienes en tu index
        $query = ActosLegislativos::with('tipoacto');

      if ($request->filled('buscar')) {
    $buscar = $request->input('buscar');
    $query->where(function ($q) use ($buscar) {
        $q->where('titulo', 'LIKE', "%{$buscar}%")
          ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
          ->orWhere('codigo', 'LIKE', "%{$buscar}%");
    });
}

        if ($request->filled('tipo_acto')) {
            $query->where('tipoacto_id', $request->input('tipo_acto')); // Ajusta según tu FK
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->input('estatus'));
        }

        if ($request->filled('fecha_iniciativa')) {
            $query->whereDate('fecha_iniciativa', $request->input('fecha_iniciativa'));
        }

        if ($request->filled('fecha_aprobacion')) {
            $query->whereDate('fecha_aprobacion', $request->input('fecha_aprobacion'));
        }

        // Obtener todos los registros coincidentes sin paginar
        $actos = $query->latest()->get();

        // 2. Cargar la vista específica para PDF y pasar los datos
        $pdf = Pdf::loadView('Parametrizar.actos.pdf', [
            'actos' => $actos,
            'filtros' => $request->all()
        ]);

        // 3. Configurar tamaño de papel y orientación horizontal (Landscape)
        $pdf->setPaper('a4', 'landscape');

        // 4. Descargar o visualizar en el navegador
        return $pdf->download('reporte_actos_legislativos_' . date('Ymd_His') . '.pdf');
        // Si prefieres ver en navegador en vez de descargar, usa: return $pdf->stream('reporte.pdf');
    }
}