<?php

namespace App\Listeners\Audit;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
@session_start();

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $ip = \Request::getClientIp(true); 
       #dd($ip);

             
            $idusuario = $event->user->id;
            $users = User::where('id',$idusuario)->first();
            $idperfil = $users->rol_id; 
            $name_user= $users->name; 
           
            Cache::forever('name_user', $name_user);
            Cache::forever('id_usuario', $idusuario);
        
        $nuevo = new Auditoria;
        $nuevo->id_usuario = $event->user->id;

        $date = Carbon::now();
        $nuevo->fecha =$date->toDateTimeString();
        $nuevo->accion = 'Usuario '. $event->user->name .' ingreso al sistema';
        $nuevo->ultimo_login = Carbon::now();
        $nuevo->ip =$ip; 

        $nuevo->save();
        $user= User::where('id',$event->user->id)->first();
        $user->activo = 1;
        $user->ultimo_login = $date->toDateTimeString();
        $user->ip = $ip;
        $user->token = session()->token();
        $user->session_id = session()->getId();
        $user->save();

        session(['id'=> session()->getId()]); 
        session(['id_usuario'=> $event->user->id]);
        session(['id_perfil'=> $user->rol_id]);
        session(['token'=> session()->token()]);

        #$_SESSION['id_usuario'] = $user->id;
        #$_SESSION['token'] = session()->token();

    }
}

