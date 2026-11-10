@extends ('layouts.app')
@section('titulo', 'Seguridad - Usuarios')

@section('link_back',route("usuarios.index"))
@section('link_new_none','d-none')
@section('content')   

<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Botón de volver atrás -->
    <a href="{{ route('usuarios.index') }}" class="btn btn-light btn-sm">
        <i class="mdi mdi-arrow-left"></i> Volver
    </a>
    <h4 class="mb-0">Editar Usuario</h4>
</div>

@if ( count( $errors ) > 0 )
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="badge badge-pill badge-danger">AVISO:</span> 
        @foreach ($errors->all() as $error)
            {{ $error }} <br>
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-body card-block">
        <form action="{{ route('usuarios.update',$usuario->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            @csrf
            @method("PUT")

            <div class="row form-group">
                <div class="col col-md-3"><label for="email-input" class=" form-control-label">{{ __('Nombres') }}</label></div>
                <div class="col-12 col-md-9"><input placeholder="Nombre del Usuario" id="nombre" type="text" class="form-control" name="nombre" value="{{ $usuario->nombres }}" required autofocus oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');">
                    <small class="help-block form-text">Por favor ingresa el nombre del usuario</small></div>
            </div>
            
            <div class="row form-group">
                <div class="col col-md-3"><label for="email-input" class=" form-control-label">{{ __('Login de Usuario') }}</label></div>
                <div class="col-12 col-md-9">
                    <input placeholder="Login de Usuario - Ejemplo:pperez" id="email" type="text" class="form-control" name="email" value="{{ $usuario->email }}" required autocomplete="email">
                </div>
            </div>
            
            <div class="row form-group">
                <div class="col col-md-3"><label for="select" class=" form-control-label">{{ __('Rol de Usuario') }}</label></div>
                <div class="col-12 col-md-9">
                    <select id="role_id" class="form-control" name="role_id" required>
                        <option value="">Seleccione</option>
                        @foreach($roles as $role)
                        <option value="{{$role->id}}" @if($role->id==$usuario->rol_id) selected=""@endif>{{$role->description}}</option>
                        @endforeach                        
                    </select>                
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> {{ __('Guardar') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection