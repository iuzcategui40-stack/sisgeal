<?php 

namespace App\Traits;
use App\Models\Auditoria as Auditar;
use App\UserNoticia as UserNoticia;
use Carbon\Carbon;

trait Auditoria{
    public function registrarAuditoria($accion){  
        $ip = \Request::getClientIp(true);       
        $auditoria = new Auditar();        
        $auditoria->id_usuario =  auth()->user()->id;
        $auditoria->fecha      = Carbon::now();
        $auditoria->accion     = $accion;
        $auditoria->ip =$ip; 
        $auditoria->save();
    }
    
    public function crearaccionnoticia($accion,$idnoticia,$antes,$despues){  
            
        $usernoticia = new UserNoticia();        
        $usernoticia->id_user    =  auth()->user()->id;
        $usernoticia->id_noticia =  $idnoticia;
        $usernoticia->texto_antes =  $antes;
        $usernoticia->texto_despues = $despues;
        $usernoticia->accion     = $accion;
        $usernoticia->save();
    }
}

 ?>