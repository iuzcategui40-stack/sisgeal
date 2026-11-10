<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistema de Gestión de Archivo Legislativo AN</title>
  
  <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png"> 

  @include("layouts.scripts")
</head>

@php
     use Carbon\Carbon;
     use App\Models\User;
     use Illuminate\Support\Facades\Cache;
     $fecha      = new Carbon();
     $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
     $mes = $meses[($fecha->format('n')) - 1];
     $fecha = now('America/Caracas')->format('d-m-Y h:i:s');
     $time       = strtotime($fecha);
     $fechalocal = date("d-m-Y h:i:s", $time);
    
     $name        = Cache::get('name_user');
     $idperfil     = Cache::get('idperfil');
     $perfil     = Cache::get('perfil');
     $id_usuario = Cache::get('id_usuario');
     $zrol       = Cache::get('zrol');
     $menu       = Cache::get('menu');
     $name_user = Cache::get('name_user');
@endphp

<body>

  <div class="container-scroller">
    @include("layouts.panel")
    <div class="container-fluid page-body-wrapper">
    
      @include("layouts.menu")
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            @include("layouts.desplegable")
          </div>
          @yield("content")
        </div>
        @include("layouts.footer")
        </div>
      </div>    
    </div>
  @include("layouts.plugins")
  
<script>
    // Configuración global opcional de Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    // Lanzar alertas basadas en Sesiones de Laravel
    @if(session('mensaje'))
        toastr.success("{{ session('mensaje') }}", "¡Éxito!");
    @endif

    @if(session('mensajeError'))
        toastr.error("{{ session('mensajeError') }}", "¡Acceso Denegado!");
    @endif

    // Opcional: Si quieres atrapar errores generales de validación o excepciones
    @if($errors->any())
        @foreach ($errors->all() as $error)
            toastr.warning("{{ $error }}", "Atención");
        @endforeach
    @endif
</script>

  @yield('javascript')
  @stack('scripts')
  @stack('componentScripts')


</body>

</html>