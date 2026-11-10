@extends('layouts.app')
@section('titulo', 'Gestión de Roles')
@section('link_back', route('home'))

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4">
                    <h3 class="font-weight-bold">Roles y Permisos del Sistema</h3>
                    <p class="text-muted">Administra los perfiles de acceso y sus privilegios por módulo.</p>
                </div>
                <div class="col-12 col-xl-4 d-flex justify-content-xl-end align-items-center">
                    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm font-weight-medium text-white">
                        <i class="ti-plus mr-1"></i> Nuevo Rol
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive border rounded bg-white">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre del Rol</th>
                                    <th>Descripción</th>
                                    <th>Fecha de Creación</th>
                                    <th class="text-center" style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $rol)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold">{{ $rol->name }}</td>
                                        <td>{{ $rol->description }}</td>
                                        <td>{{ $rol->created_at }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('roles.edit', $rol->id) }}" class="btn btn-outline-primary btn-sm p-1" title="Editar">
                                                <i class="ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este rol?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm p-1" title="Eliminar">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No hay roles registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection