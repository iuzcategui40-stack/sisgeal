@extends('layouts.app')
@section('titulo', 'Editar Rol')
@section('link_back', route('roles.index'))

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <h3 class="font-weight-bold">Editar Rol: {{ $rol->name }}</h3>
            <p class="text-muted">Modifica los permisos asociados a este perfil.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form class="forms-sample" action="{{ route('roles.update', $rol->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name" class="font-weight-bold">Nombre del Rol</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $rol->name) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="description" class="font-weight-bold">Descripción</label>
                                <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $rol->description) }}" required>
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
                                        @php
                                            $p = $permisosActuales[$key] ?? null;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bold align-middle">{{ $nombreModulo }}</td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][view]" value="1" {{ optional($p)->can_view ? 'checked' : '' }}></td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][create]" value="1" {{ optional($p)->can_create ? 'checked' : '' }}></td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][edit]" value="1" {{ optional($p)->can_edit ? 'checked' : '' }}></td>
                                            <td class="text-center align-middle"><input type="checkbox" name="permisos[{{ $key }}][delete]" value="1" {{ optional($p)->can_delete ? 'checked' : '' }}></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('roles.index') }}" class="btn btn-light mr-2">Cancelar</a>
                                <button type="submit" class="btn v-primary font-weight-medium">
                                    <i class="ti-save mr-1"></i> Actualizar Rol
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