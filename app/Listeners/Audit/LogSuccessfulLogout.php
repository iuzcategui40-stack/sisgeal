<?php
    namespace App\Listeners\Audit;
    use Illuminate\Auth\Events\Logout;
    use Illuminate\Auth\Events\Login;
   use Illuminate\Http\Request;
   use Carbon\Carbon;
    use App\Models\Auditoria;
    use App\Models\User;
    use Illuminate\Support\Facades\Cache;
   # @session_start();
class LogSuccessfulLogout
    {
        /**
         * Create the event listener.
         *
         * @return void
         */
        public function __construct()
        {
            //
        }

        /**
         * Handle the event.
         *
         * @param  Logout  $event
         * @return void
         */
        public function handle(Logout $event)
        {
        $ip = \Request::getClientIp(true); 
           # dd($ip);
        $idusuario = session('id_usuario');
        #$valor = helper::buscarusuario($id_usuario); ;
        $user= User::where('id',$idusuario)->first();
        $nuevo = new Auditoria;
        $idusuario = $idusuario;
        $name = $user->nombre." ".$user->apellido;
        $nuevo->id_usuario = $idusuario;
        $date = Carbon::now();
        $nuevo->fecha =$date->toDateTimeString();
        $nuevo->accion = 'Usuario '. $name .' saliendo del sistema';
        $nuevo->ultimo_login = Carbon::now();
        $nuevo->ip =$ip; 
        $nuevo->save();
        
        if($user!=NULL){
            $user->activo = 0;
            $user->save();
            $valor = session()->has('id_usuario');
            if($valor)
             session()->forget('id_usuario');
          }
        }

    }