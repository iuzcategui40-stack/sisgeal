@extends('layouts.app')
@section('titulo', 'Gestión de Diario de Debates')
@section('link_back', route('home'))
@section('link_new', route('sesiones.create'))

@section('content')
<div class="content-wrapper">
    
    {{-- Mensajes de Éxito --}}
    @if(session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ session('mensaje') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
     @if(session('mensajeError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error!</strong> {{ session('mensajeError') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div>
                            <h4 class=""><b>Diario de Debates / Historial de Sesiones</b></h4>
                            <p class="card-description mb-0">Consulta y filtrado de registros de sesiones y sus debates</p>
                        </div>
                    </div>

                    {{-- Formulario de Filtros de Búsqueda --}}
                    <form action="{{ route('sesiones.index') }}" method="GET" class="mb-4">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="font-weight-bold mb-3"><i class="ti-filter mr-1"></i> Filtros de Búsqueda</h6>
                                <div class="row">
                                    {{-- Filtro: Sumario Cuenta --}}
                                    <div class="col-md-3 mb-2">
                                        <label for="sumario_cuenta" class="small font-weight-bold">Sumario Cuenta</label>
                                        <input type="text" name="sumario_cuenta" id="sumario_cuenta" class="form-control form-control-sm" placeholder="Buscar en sumario cuenta..." value="{{ request('sumario_cuenta') }}">
                                    </div>

                                    {{-- Filtro: Sumario Orden del Día --}}
                                    <div class="col-md-3 mb-2">
                                        <label for="sumario_orden_dia" class="small font-weight-bold">Sumario Orden del Día</label>
                                        <input type="text" name="sumario_orden_dia" id="sumario_orden_dia" class="form-control form-control-sm" placeholder="Buscar en orden del día..." value="{{ request('sumario_orden_dia') }}">
                                    </div>

                                    {{-- Filtro: Naturaleza de la Sesión --}}
                                    <div class="col-md-3 mb-2">
                                        <label for="naturaleza" class="small font-weight-bold">Naturaleza</label>
                                        <input type="text" name="naturaleza" id="naturaleza" class="form-control form-control-sm" placeholder="Ej. Ordinaria, Extraordinaria..." value="{{ request('naturaleza') }}">
                                    </div>

                                    {{-- Filtro: Fecha de la Sesión --}}
                                    <div class="col-md-3 mb-2">
                                        <label for="fecha" class="small font-weight-bold">Fecha de Sesión</label>
                                        <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" value="{{ request('fecha') }}">
                                    </div>
                                </div>

                                {{-- Botones de Acción para Filtros --}}
                               {{-- Botones de Acción para Filtros y Exportación --}}
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('sesiones.exportExcel', request()->query()) }}" class="btn btn-sm btn-success mr-2" title="Exportar a Excel">
                                        <i class="ti-file"></i> Exportar Excel
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-primary mr-2">
                                        <i class="ti-search"></i> Aplicar Filtros
                                    </button>
                                    @if(request()->hasAny(['sumario_cuenta', 'sumario_orden_dia', 'naturaleza', 'fecha']))
                                        <a href="{{ route('sesiones.index') }}" class="btn btn-sm btn-secondary">
                                            <i class="ti-reload"></i> Limpiar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Tabla de Registros --}}
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 15%;">Naturaleza / Fecha</th>
                                    <th style="width: 32%;">Sumarios de Cuenta</th>
                                    <th style="width: 33%;">Sumarios Orden del Día</th>
                                    <th style="width: 15%;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sesiones as $sesion)
                                    <tr>
                                        <td><strong>#{{ $sesion->id }}</strong></td>
                                        <td>
                                            <span class="badge badge-info text-wrap mb-1 d-inline-block">{{ $sesion->naturaleza }}</span>
                                            <small class="d-block text-muted">
                                                <i class="ti-calendar"></i> {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}
                                            </small>
                                        </td>

                                        {{-- Listado de Sumarios de Cuenta asociados --}}
                                        <td class="text-wrap">
                                            @forelse($sesion->diariodebates as $diario)
                                                @if($diario->sumario_cuenta)
                                                    <div class="mb-1 pb-1 border-bottom-light small">
                                                        • {{ \Illuminate\Support\Str::limit($diario->sumario_cuenta, 90) }}
                                                    </div>
                                                @endif
                                            @empty
                                                <span class="text-muted small">Sin datos de diario</span>
                                            @endforelse
                                        </td>

                                        {{-- Listado de Sumarios de Orden del Día asociados --}}
                                        <td class="text-wrap">
                                            @forelse($sesion->diariodebates as $diario)
                                                @if($diario->sumario_orden_dia)
                                                    <div class="mb-1 pb-1 border-bottom-light small">
                                                        • {{ \Illuminate\Support\Str::limit($diario->sumario_orden_dia, 90) }}
                                                    </div>
                                                @endif
                                            @empty
                                                <span class="text-muted small">Sin datos de diario</span>
                                            @endforelse
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                 <a href="{{ route('sesiones.show', $sesion->id) }}" class="btn btn-outline-success btn-sm p-2" title="Ver Sesión">
                                                    <i class="ti-eye"></i>
                                                </a>
                                                <a href="{{ route('sesiones.edit', $sesion->id) }}" class="btn btn-outline-warning btn-sm p-2" title="Editar Sesión">
                                                    <i class="ti-pencil"></i>
                                                </a>

                                                <form action="{{ route('sesiones.destroy', $sesion->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta sesión?');" style="display: inline;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-outline-danger btn-sm p-2" title="Eliminar Sesión">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No se encontraron registros de Sesiones o Diarios de Debate con los criterios ingresados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación manteniendo parámetros --}}
                    <div class="mt-4 d-flex justify-content-end pagination-sm">
                        {{ $sesiones->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection