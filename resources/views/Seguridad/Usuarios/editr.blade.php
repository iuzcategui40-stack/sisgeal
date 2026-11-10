@extends ('layouts.app')
@section('titulo', 'Editar Usuario')
@section('link_back',route("usuarios.index"))
@section('link_new_none','d-none')

@section('content')
<div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>{{ __('Modificar Usuario') }}</strong> - Complete todos los campos
            </div>
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
            <div class="card-body card-block">
                <form action="{{ route('usuarios.update',$user->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
                <div class="row form-group">
                    <div class="col col-md-3">
                        <label class=" form-control-label">{{ __('Correo Electrónico') }}</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <p class="form-control-static">{{$user->email}}</p>
                    </div>
                </div>
                <div class="row form-group">
                        <div class="col col-md-3"><label for="email-input" class=" form-control-label">{{ __('Nombre') }}</label></div>
                        <div class="col-12 col-md-9"><input value="{{$user->name}}" placeholder="Nombre del Usuario" id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            <small class="help-block form-text">Porfavor ingresa el nombre del usuario</small></div>
                </div>                
                <div class="row form-group">
                    <div class="col col-md-3"><label for="select" class=" form-control-label">{{ __('Rol de Usuario') }}</label></div>
                    <div class="col-12 col-md-9">
                    <select id="role_id_select" class="form-control" name="role_id" required>
                        <option value="">Seleccione</option>
                        @foreach($roles as $role)
                        @if ($user->role_id==$role->id)
                        <option value="{{$role->id}}" selected="selected">{{$role->description}}</option>
                        @else
                        <option value="{{$role->id}}">{{$role->description}}</option> 
                        @endif                        
                        @endforeach                       
                    </select>                                    
                    </div>
                </div>
            </div>
                <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-refresh"></i> {{ __('Modificar') }}
                        </button>
                        <button type="reset" class="btn btn-warning btn-sm">
                            <i class="fa fa-undo"></i> {{__('Restaurar')}}
                        </button>
                    </div>
                </form>
        </div>
        </div>
@endsection
