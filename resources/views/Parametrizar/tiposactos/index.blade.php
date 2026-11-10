@extends('layouts.app')
@section('titulo', 'Gestión de Tipos de Actos Legislativos')
@section('link_back_none', "d-none")
@section('link_new_none', "d-none")
@section('content')
<div class="content-wrapper">
    
    {{-- Sección de Mensajes de Éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Formulario de Registro (Lado Izquierdo) --}}
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Registrar Nuevo Tipo Acto</h4>
                    <p class="card-description">Ingrese los datos correspondientes</p>
                    
                    <form class="forms-sample" action="{{ route('tiposactos.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="titulo">Título/Nombre</label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ej. Ley del Sistema Alimentario">
                            @error('titulo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary mr-2 text-white">Guardar Registro</button>
                        <button type="reset" class="btn btn-light">Limpiar</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabla de Registros y Sección de Búsqueda (Lado Derecho) --}}
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div>
                            <h4 ><strong>Historial de Tipo de Actos Registrados</strong></h4>
                            <p class="card-description mb-0">Listado ordenado por los más recientes</p>
                        </div>
                        
                        {{-- Barra de Búsqueda integrada estilo Skydash --}}
                        <div style="max-width: 300px; width: 100%;" class="mt-2 mt-sm-0">
                            <form action="{{ route('tiposactos.index') }}" method="GET" class="input-group">
                                <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar por título o descripción..." value="{{ request('buscar') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary text-white" type="submit">
                                        <i class="icon-search"></i> Buscar
                                    </button>
                                    @if(request('buscar'))
                                        <a href="{{ route('tiposactos.index') }}" class="btn btn-sm btn-light d-flex align-items-center" title="Limpiar filtro">
                                            ×
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título / Descripción</th>
                                    <th>Creado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tiposactos as $acto)
                                    <tr>
                                        <td><strong>#{{ $acto->id }}</strong></td>
                                        <td class="text-wrap" style="max-width: 250px;">
                                            <span class="font-weight-bold text-dark d-block">{{ $acto->titulo }}</span>
                                            <small class="text-muted">{{ Str::limit($acto->descripcion, 60) }}</small>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($acto->fecha_publicacion)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('tiposactos.edit', $acto->id) }}" class="btn btn-outline-warning btn-sm p-2" title="Editar Acto">
                                                    <i class="ti-pencil"></i>
                                                </a>

                                                {{-- Formulario y Botón Eliminar --}}
                                               {{-- Formulario y Botón Eliminar --}}
                                            <form action="{{ route('tiposactos.destroy', $acto->id) }}" method="POST" onsubmit="return confirm('¿Está completamente seguro de eliminar este acto legislativo? Esta acción no se puede deshacer.');" style="display: inline;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                
                                                <button type="submit" class="btn btn-outline-danger btn-sm p-2" title="Eliminar Acto">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No se encontraron actos legislativos que coincidan con la búsqueda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación Forzada a tamaños pequeños de Bootstrap --}}
                    <div class="mt-4 d-flex justify-content-end pagination-sm">
                        {{ $tiposactos->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection