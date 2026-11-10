<?php
namespace App\Traits;
use App\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

trait Usuario{
    public function buscarusuario($id){  
       $ip = \Request::getClientIp(true);       
       $user = User::where("id",$id)->where("id_estado",1)->first();
       if($user!=NULL){
         $data["valor"]=1;
         $data["id_usuario"] = $user->id;
         $data["nombre"] = $user->nombre." ".$user->apellido;

                    Cache::flush();
            
            $name_user = Cache::get('name_user');
            if($name_user==""){
                $users = User::where('id',$idusuario)->first();
                #dd($idusuario);
                $name_user= $users->nombre." ".$users->apellido; 
            }else{

                Cache::forget('name_user');
                Cache::forget('id_usuario');
            }

            Cache::forever('name_user', $name_user);
            $id_usuario = Cache::get('id_usuario');
            if($id_usuario==""){
                $id_usuario =Auth::user()->id; 
            }
              Cache::forever('id_usuario', $id_usuario);
              $rol = DB::table('role_user as a')->join('roles as b','b.id','=','a.role_id')->select('a.role_id','a.user_id','b.description','b.descargas_img','b.descargas_audio','b.descargas_video','b.descarga_notas')->where('user_id',$id_usuario)->first();
             
              Cache::forever('zperfil',$rol);
              if($rol){
                   $perfil = $rol->description; 
                   $idperfil = $rol->role_id; 
               } 
                 else{
                   $perfil="";
                   $idperfil=0;  
                }
                Cache::forever('perfil',$perfil);
                Cache::forever('idperfil',$idperfil);
                $opciones =Opciones::where('publico',1)->orderby('id','asc')->get();
                $zsistemas = $this->obteneropciones2($opciones,$idperfil,$id_usuario);
                Cache::forever('menu',$zsistemas);
                $fecha = date("Y-m-d");
                $fecha      = new Carbon();
                $fecha      =$fecha->setTimezone('America/La_Paz');
                $time       = strtotime($fecha);
                $fechalocal = date("Y-m-d", $time);
                $data["idperfil"] = $idperfil;   

       }else{
       	$data["valor"]=0;
       } 
       return $data;
    }

    public function obteneropciones2($zopciones,$idperfil,$idusuario)
    {
      $opciones=[];  

      foreach($zopciones as $key)
      {
        $permisos = Permisos::where('id_rol',$idperfil)->where('id_opcion',$key->id)->first();
        if($permisos!=NULL){
        if($permisos->autorizacion==1)  {
         $fuentes = Fuentes::where("id",$key->correlativo)->first();
         if($fuentes!=NULL && $fuentes->descripcion==$key->descripcion) {
            $descripcion = $key->descripcion;
            $ruta = "notas/".$fuentes->id;
          }  
         else {
            $descripcion = $key->descripcion;
            $ruta = $key->ruta;
           
         }    
         $opciones[]=[
            "id"=>$key->id,
            "descripcion"=>$descripcion,
            "icono" =>$key->icono,
            "ruta"=>$ruta,
            "idperfil"=>$idperfil,
            "autorizado"=>$permisos->autorizacion
         ];   
        }
       } 
      }
      #dd($opciones);
      return $opciones;

      }
    
}
?>