<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;

use App\Models\Roleuser;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Traits\Auditoria;

class UsuariosController extends Controller
{

     use RegistersUsers;
     use Auditoria;
public function __construct()
{
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        $this->user = Auth::user();
        return $next($request);
    });
}
    
 public function checkEmail(Request $request)
{
    $email = $request->input('email');
    
    // Verifica si el email ya existe en la tabla de usuarios
    $exists = User::where('email', $email)->exists();

    return response()->json([
        'exists' => $exists,
        'message' => $exists ? 'El correo / login ya se encuentra registrado.' : 'Correo disponible.'
    ]);
}
   
 protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'string', 'integer'],

        ]);
    }


public function show($user_id)
{
 
}
  
 public function edit($user_id)
    {

        $usuario = User::where('users.id',$user_id)
                    ->first();
        $roles = Role::get();
                   
        $accion = "Acceso a Editar Usuario Id.".$user_id;
       $this->registrarAuditoria($accion); 
        // dd($user->role_id);

        return view('Seguridad.Usuarios.edit',compact('usuario','roles'));
    }





public function update(Request $request,$user_id)
    {
        #dd($request);
        $user = User::where('id', $user_id)->first();

        if (!$user){
            \Session::flash('mensajeError', 'Usuario no encontrado');
            return back();
        }
        else{
            $user->nombres         = $request["nombre"];
            $user->rol_id          = $request["role_id"];
            $user->save();
 
            }
      $accion = "información de Usuario actualizada.".$user_id;
    
       $this->registrarAuditoria($accion);     
       \Session::flash('mensaje', 'Información de Usuario Actualizada con éxito');                
            return redirect()->route("usuarios.index");
        
    }

public function create(){
    $roles = Role::get();
    return view('Seguridad.Usuarios.create',['roles'=>$roles]);
}

 public function status($id)

    {
        
        $user_id = $id;
        
        $user = User::find($user_id);
        if ($user->id_estado == null) {
              $user = User::where('id', $user_id)
                        ->update(['id_estado'=>1]); 
                        $anterior=0;
                        $actual=1;  
          }  
          elseif ($user->id_estado == 1) {
              $user = User::where('id', $user_id)
                        ->update(['id_estado'=>2]); 
                                 $anterior=1;
                        $actual=2; 
          }
          else{
            $user = User::where('id', $user_id)
                        ->update(['id_estado'=>1]); 
                $anterior=2;
               $actual=1;                  
          }
     if($anterior==1 && $actual==2)
     {
        /*$eliminarol = roleuser::where('user_id',$user_id)->first();
        $id = $eliminarol->id;
        $eliminarol->destroy($id);*/
     }
      if($anterior==2 && $actual==1)
     {
        $agregarol = roleuser::where('user_id',$user_id)->first();
        if($agregarol==NULL){
        $agregarol = new roleuser();
        $agregarol->user_id = $user_id;
        $agregarol->rol_id = $user->rol_id;
        $agregarol->save();
       }
     }    
     if($anterior==0 && $actual==1)
     {
         $agregarol = roleuser::where('user_id',$user_id)->first();
        if($agregarol==NULL){
        $agregarol = new roleuser();
        $agregarol->user_id = $user_id;
        $agregarol->rol_id = $user->rol_id;
        $agregarol->save();
      }
     }    
           $accion = "Estatus de Usuario cambiado de.".$anterior." a:".$actual;
    
       $this->registrarAuditoria($accion); 
        \Session::flash('mensaje', 'Información de Usuario Actualizada con éxito');                
            return redirect()->route("usuarios.index");
    }

  public function index()
    {
        $usuarios = User::join('roles as r','r.id','=','users.rol_id')->select('users.id','users.nombres','users.email','users.id_estado','r.name as role')->where('id_estado','!=',4)->orderBy('users.created_at','desc')
                    ->get();       

        // dd($usuarios);
        return view('Seguridad.Usuarios.index',compact('usuarios'));        
    }


   public function store(Request $request)
   {

    $request->validate([
        'nombre'   => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'role_id'  => 'required|exists:roles,id',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'email.required' => 'El campo Login de usuario es obligatorio.',
        'email.email'    => 'Login incorrecto: Debe ingresar una dirección de correo válida.',
        'email.unique'   => 'El correo o login ya se encuentra registrado.',
    ]);
            $rules = [
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            //dd($request);
            $role_admin = Role::where('id', $request["role_id"])->first();
            $usuario               = new User($request->all());
            $usuario->nombres        = $request["nombre"];
            $usuario->email        = $request["email"];
            $usuario->password     = Hash::make($request->password);
            $usuario->id_estado    = 1;
            $usuario->sesiones     = 5;
            $usuario->tiempo       = 10;
            $usuario->rol_id       = $request->role_id;
            $usuario->save();

            $usuario->role()->attach($role_admin);
            $accion = "Usuario creado:.".$usuario->id;
            $i=1;
            $user_id = $usuario->id;
          
           $this->registrarAuditoria($accion);     
            return redirect()->route('usuarios.index')->with("notificacion","Se ha guardado correctamente su información");
        } catch (Exception $e) {
            \Log::info('Error creating item: '.$e);
            return \Response::json(['created' => false], 500);
        }

   }

  public function cambiar($id)
  {
        //dd($id);
    $usuario=User::find($id);
    return view('Seguridad.Usuarios.cambiarp')->with('usuario',$usuario);
  }

  


  public function update_password(Request $request, $valor)
  {
      //dd($valor);
      $id=$valor;
      $password = Hash::make($request->password);
      $usuarios=User::find($id);
      $usuarios->password = $password;
      $usuarios->save();
      $accion = "Password de usuario actualizado iduser:.".$id;
    
       $this->registrarAuditoria($accion); 
      return redirect()->route('usuarios.index')->with("notificacion","Se ha guardado correctamente su información");            
  }

  public function eliminar($id)
  {
    $x=0;
    $usuario = User::where("id",$id)->first();
    if($usuario!=NULL)
    {
        $noticias=Notas::where("id_usuario",$id)->get();
        if($noticias->count()>0)
            $x++;
       
        if($x>0){
            $usuario->delete_at = date("Y-m-d h:i:s");  
            $usuario->id_estado = 4;
            $usuario->save();
        }else{
            $usuario->destroy($id); 
        }
         \Session::flash('mensaje', 'Usuario Eliminado con éxito');
       return redirect()->route('usuarios.index'); 
    }
  }


}
