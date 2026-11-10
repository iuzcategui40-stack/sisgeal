<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <-- Agrega esta línea
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Auth;

use App\Models\Opciones;
use App\Models\Roles;
use App\Models\Permisos;
use DB;
use Illuminate\Support\Facades\Cache;
#@session_start();

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      Schema::defaultStringLength(191);
      Carbon::setLocale('es');
      Carbon::setUTF8(true);
      setlocale(LC_TIME, 'es_ES');

      view()->composer('*', function($view) {
       
         $data = session()->all();
         $name_user ="";
         if(session()->has('id_usuario')) 
            $id_usuario = session('id_usuario');
         else $id_usuario=0;
        
         if($id_usuario)
              {    
                 
                 $user = User::where('id',$id_usuario)->first();
                 #dd($user);
                if($user!=NULL){
                   $activo=$user->activo;
                   $name_user = $user->nombre." ".$user->apellido;
                   if($activo==1)
                    {    
                     
                    }else{
                       auth()->logout();
                    }

                }
            }         
            $view->with('id_usuario',$id_usuario)->with("name_user",$name_user);
          });
          
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
  
    
   public function boot(): void
    {
      Paginator::useBootstrapFour();
        // Directiva para verificar permisos: @permission('sesiones', 'create')
        Blade::if('permission', function (string $modulo, string $accion) {
            $user = Auth::user();

            // Si no hay usuario autenticado, denegar
            if (!$user || !method_exists($user, 'tienePermiso')) {
                return false;
            }

            // Llamamos a tu método tienePermiso que definimos previamente
            return $user->tienePermiso($modulo, $accion);
        });
    }
}
