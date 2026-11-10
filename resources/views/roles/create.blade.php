@extends('layouts.app')
@section('titulo', 'Crear Rol')
@section('link_back', route('roles.index'))

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <h3 class="font-weight-bold">Crear Nuevo Rol</h3>
            <p class="text-muted">Define los datos del perfil y marca los privilegios por sección.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form class="forms-sample" action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name" class="font-weight-bold">Nombre del Rol</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ej. Administrador" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="description" class="font-weight-bold">Descripción</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" placeholder="Breve descripción" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h4 class="card-title text-primary mb-3">
                            <i class="ti-shield mr-1"></i> Matriz de Permisos por Módulo
                        </h4>

                        <div class="table-responsive border rounded bg-white">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th class="text-left">Módulo del Sistema</th>
                                        <th style="width: 100px;">Ver</th>
                                        <th style="width: 100px;">Agregar</th>
                                        <th style="width: 100px;">Editar</th>
                                        <th style="width: 100px;">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modulos as $key => $nombreModulo)
                                        <tr>
                                            <td class="font-weight-bold align-middle">{{ $nombreModulo }}</td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][view]" value="1"></td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][create]" value="1"></td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][edit]" value="1"></td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][delete]" value="1"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('roles.index') }}" class="btn btn-light mr-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary text-white font-weight-medium">
                                    <i class="ti-save mr-1"></i> Guardar Rol
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection