<?php

namespace App\Http\Controllers\Parametrizar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoActos;
use App\Models\ActosLegislativos;

use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; 
use App\Traits\Auditoria;
use Illuminate\Support\Facades\Auth;

class TiposActosController extends Controller
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
       
        $buscar = $request->get('buscar');

       $tiposactos = TipoActos::when($buscar, function ($query, $buscar) {
            return $query->where('descripcion', 'LIKE', "%{$buscar}%");
        })
        ->latest()
        ->paginate(10)
        ->appends(['buscar' => $buscar]); // Evita que se pierda el filtro al cambiar de página
        return view('Parametrizar.tiposactos.index', compact('tiposactos','buscar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
        $request->validate([
            'titulo' => 'required|string|max:255',
           
        ]);
        $tipos=TipoActos::where("descripcion",$request->titulo)->first();
        if($tipos!=NULL)
        {
            DB::rollBack();
           \Session::flash('mensajeError', 'Tipo de Acto Ya Registrado');
                      return back()->withInput();
        }else{
            DB::commit();
            $tipos = new TipoActos;
            $tipos->descripcion = $request->titulo;
            $tipos->user_id = Auth()->id();
            $tipos->save();

             return redirect()->route('tiposactos.index')
            ->with('success', 'El Acto Legislativo ha sido registrado exitosamente.');
        }
        
      } catch (\Exception $e) {
            DB::rollBack();
            \Session::flash('mensajeError', 'Error al registrar el tipo de Acto: ' . $e->getMessage());
            return back()->withInput();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $acto = TipoActos::findOrFail($id);
    return view('Parametrizar.tiposactos.edit', compact('acto'));
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
     
     DB::beginTransaction();

        try {


        $request->validate([
        'descripcion' => 'required|string|max:255',
        
         ]);
         #dd($request);
        $acto = TipoActos::findOrFail($id);
        $tipos2 = TipoActos::where("descripcion",$request["descripcion"])->where("id","!=",$id)->first();
        if($tipos2!=NULL)
        {
             DB::rollBack();
              \Session::flash('mensajeError', 'Tipo de Acto Ya Registrado');
                      return back()->withInput();
         }else{
            DB::commit();
            $acto->descripcion = $request["descripcion"];
                  $acto->save();

            return redirect()->route('tiposactos.index')->with('success', 'El Tipo de Acto Legislativo se actualizó correctamente.');
        }
           
      }catch (\Exception $e) {
            DB::rollBack();
            \Session::flash('mensajeError', 'Error al actualizar el tipo de Acto: ' . $e->getMessage());
            return back()->withInput();
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
       $ActosLegislativos= ActosLegislativos::where("tipoacto_id",$id)->get();
       if($ActosLegislativos->count()>0){
      $acto = TipoActos::findOrFail($id);
       $acto->destroy($id);

    return redirect()->route('TiposActos.index')
        ->with('success', 'El Acto Legislativo ha sido eliminado.');
       }else{
      $acto = TipoActos::findOrFail($id);
       $acto->destroy($id);

    return redirect()->route('tiposactos.index')
        ->with('success', 'El Acto Legislativo ha sido eliminado.');   
       }
      
    }
}
