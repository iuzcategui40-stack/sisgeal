@extends ('layouts.app')
 @section('titulo', 'Seguridad - Usuarios')
 @section('link_new_none','d-none')
 @section('link_back',route("usuarios.index"))
 @section('content')   

    @if ( count( $errors ) > 0 )
                <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                    <span class="badge badge-pill badge-danger">AVISO:</span> 
                        @foreach ($errors->all() as $error)
                            {{ $error }} <br>
                        @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif

<div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Cambiar Contraseña</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
              <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Atrás</a></li>
            </ol>
</div>

          
    <div class="card-body card-block">
        <div class="row">
<div class="col-12 grid-margin stretch-card">

<div class="card">
                <div class="card-body">

<form role="form" action="{{ route('usuarios.update_password', $usuario->id) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

        <div class="col-lg-6">
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label>Ingrese nueva Clave ({{ $usuario->name }})</label>
                <input type="password" class="form-control" id="password" name="password" maxlength="100" required>
                @if ($errors->has('password'))
                    <p class="help-block">{{ $errors->first('password') }}</p>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Confirmar Clave</label>
                <input type="password" class="form-control" id ="password_confirmation" name="password_confirmation" maxlength="100" required onchange="validar();">
            </div>
        </div>
    </div>
     <div class="card-footer">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-fw fa-check"></i> Guardar</button>
        </div>
     
</form>
</div>
</div>
</div>




@endsection                        
@push('scripts')

<script type="text/javascript">
   jQuery(document).ready(function () {
      $('#password_confirmation').change(function(event) {
        var conf = $(this).val();
        var nuev = $('#password').val();
        if(conf!=nuev){
            alert('confirmación inválida');
            $(this).val("");    
        }
  });
  });

   function validar()
   {
        var conf = $('#password_confirmation').val();
        var nuev = $('#password').val();
        if(conf!=nuev){
            alert('confirmación inválida');
            $(this).val("");    
        }
   }
</script>

@endpush
