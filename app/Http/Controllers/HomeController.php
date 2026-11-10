<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Opciones;
use App\Models\Permisos;
use App\Models\User;
use App\Models\Sessions;
use App\Models\Roles;
use App\Models\ActosLegislativos;
use App\Models\Sesiones;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
 public function index(Request $request)
{
    if(session()->has('id_usuario')){
        $id_usuario = session('id_usuario');     
    } else { 
        $id_usuario = 0; 
    }

    if($id_usuario > 0){
        $valor = $this->buscarusuario($id_usuario);
        $rol = DB::table('role_user as a')->join('roles as b','b.id','=','a.role_id')->select('a.role_id','a.user_id','b.description')->where('a.user_id',$id_usuario)->first();
        
        $idperfil = $rol ? $rol->role_id : 0; 
        
        $hoy = now('America/Caracas')->format('Y-m-d');

        // Totales generales para las tarjetas KPI existentes
        $totalActos = ActosLegislativos::count();
        $totalSesiones = Sesiones::count();
        $actosAprobados = ActosLegislativos::where('estatus', 'Aprobado')->count();
        $actosEnDiscusión = ActosLegislativos::where('estatus', 'En Discusión')->count();

        // 📊 NUEVOS: Estadísticas de registros diarios (Hoy)
        $actosHoy = ActosLegislativos::whereDate('created_at', $hoy)->count();
        $sesionesHoy = Sesiones::whereDate('created_at', $hoy)->count();
         
        // ÚLTIMOS REGISTROS CARGADOS
        $ultimosActos = ActosLegislativos::with('tipoacto')->latest()->take(5)->get();
        $ultimasSesiones = Sesiones::with('diarioDebates')->latest()->take(5)->get();

        return view('home', compact(
            'id_usuario',
            'totalActos', 
            'totalSesiones', 
            'actosAprobados', 
            'actosEnDiscusión', 
            'actosHoy',      // <-- Nuevo
            'sesionesHoy',   // <-- Nuevo
            'ultimosActos', 
            'ultimasSesiones'
        ));

    } else {
        return view("auth.login");
    }
}
   


     public function buscarusuario($idusuario)
        {

            Cache::flush();
            Cache::forget('name_user');
            Cache::forget('id_usuario');
            $users = User::where('id',$idusuario)->first();
            $idperfil = $users->rol_id; 
            $name_user = Cache::get('name_user');
            if($name_user==""){
                $name_user= $users->nombre." ".$users->apellido; 
            }else{

                Cache::forget('name_user');
                Cache::forget('id_usuario');
            }
            #dd($users);
            Cache::forever('name_user', $name_user);
            $id_usuario = $idusuario;
            if($id_usuario==""){
                $id_usuario =Auth::user()->id; 
            }
            Cache::forever('id_usuario', $id_usuario);
            $rol = DB::table('role_user as a')->join('roles as b','b.id','=','a.role_id')->select('a.role_id','a.user_id','b.description')->where('a.user_id',$id_usuario)->first();
         
            #dd($rol);
           
            if($rol){
                   $perfil = $rol->description; 
                   
             } 
                 else{
                   $perfil="";
                   $idperfil=0;  
                }
                Cache::forever('perfil',$perfil);
                Cache::forever('idperfil',$idperfil);

                 $opciones =Opciones::where('publico',1)->orderby('id','asc')->get();
                $zsistemas = $this->obteneropciones2($opciones,$idperfil,$id_usuario);
                #dd($zsistemas);
                Cache::forget('modulos');
                Cache::forget('menu');
                Cache::forever('modulos',$zsistemas["modulos"]);
                Cache::forever('menu',$zsistemas["opciones"]);
              
           return $idperfil;
        }

        
      

   public function obteneropciones2($zopciones,$idperfil,$idusuario)
    {
      $opciones=[]; 
      $ano = date("Y");
      $modulos=[];
      for($i=1;$i<6;$i++){
        $modulos[$i]=0;
      }
      
      foreach($zopciones as $key)
      {
        $permisos = Permisos::where('id_rol',$idperfil)->where('id_opcion',$key->id)->first();
          $titulo = $key->descripcion;  
         $ruta=$key->ruta;
        if($permisos!=NULL){
            $autorizacion =$permisos->autorizacion;
        }else{
            $autorizacion=0;
        }    
        if($key->id>=1  && $key->id<8 && $key->autorizacion==1){ $modulos["0"]=1;}
        if($key->id==8  &&  $autorizacion==1){ $modulos["1"]=1; }
        if($key->id==9  &&  $autorizacion==1){ $modulos["2"]=1; }
        if($key->id==10 &&  $autorizacion==1){ $modulos["3"]=1; }
        if($key->id==12 &&  $autorizacion==1){ $modulos["4"]=1; } 
        if($key->id==13 &&  $autorizacion==1){ $modulos["5"]=1; } 
        
        $consultar=0;           
         $opciones[]=[
            "id"=>$key->id,
            "descripcion"=>$titulo,
            "icono" =>$key->icono,
            "ruta"=>$ruta,
            "idperfil"=>$idperfil,
            "autorizado"=>$autorizacion,
            "idperfil" => $idperfil,
            ];   
         
        
      }

      $sistemas["modulos"]=$modulos;
      $sistemas["opciones"]=$opciones;
      #dd($idperfil);
      return $sistemas;

      }
    
}
