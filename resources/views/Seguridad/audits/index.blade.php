@extends('layouts.app')

@section('titulo', 'Auditoría del Sistema')
@section('link_back_none', "d-none")
@section('link_new_none', "d-none") {{-- Es solo de consulta --}}

@section('content')
<div class="content-wrapper">
    
    {{-- PANEL DE FILTROS AVANZADOS --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="ti-search mr-2"></i> Filtros de Auditoría
                        </h5>
                    </div>
                    <hr>
                    
                    <form action="{{ route('seguridad.audits.index') }}" method="GET" class="mt-3">
                        <div class="row">
                            {{-- Búsqueda general --}}
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold small">Buscar por IP / Módulo</label>
                                <input type="text" name="buscar" class="form-control form-control-sm" placeholder="IP, modelo afectado..." value="{{ request('buscar') }}">
                            </div>

                            {{-- Filtro por Evento --}}
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold small">Tipo de Evento</label>
                                <select name="evento" class="form-control form-control-sm">
                                    <option value="">Todos los eventos...</option>
                                    <option value="created" {{ request('evento') == 'created' ? 'selected' : '' }}>Creado (created)</option>
                                    <option value="updated" {{ request('evento') == 'updated' ? 'selected' : '' }}>Actualizado (updated)</option>
                                    <option value="deleted" {{ request('evento') == 'deleted' ? 'selected' : '' }}>Eliminado (deleted)</option>
                                </select>
                            </div>

                            {{-- Fecha Inicio --}}
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold small">Desde</label>
                                <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                            </div>

                            {{-- Fecha Fin --}}
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold small">Hasta</label>
                                <input type="date" name="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-sm btn-primary px-4">
                                    <i class="ti-search mr-1"></i> Filtrar Registros
                                </button>
                                @if(request()->anyFilled(['buscar', 'evento', 'fecha_inicio', 'fecha_fin']))
                                    <a href="{{ route('seguridad.audits.index') }}" class="btn btn-sm btn-light ml-1">
                                        Limpiar Filtros
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE REGISTROS DE AUDITORÍA --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div>
                        <h4 class="card-title mb-1">Registro de Actividad (Audits)</h4>
                        <p class="card-description mb-3">Historial de cambios realizados por los usuarios en el sistema</p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Evento</th>
                                    <th>Modelo / Tabla</th>
                                    <th>ID Registro</th>
                                    <th>IP</th>
                                    <th>Fecha y Hora</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($audits as $audit)
                                    <tr>
                                        <td><strong>#{{ $audit->id }}</strong></td>
                                        <td>
                                            <span class="font-weight-bold text-dark">{{ $audit->user->name ?? 'Sistema / Invitado' }}</span>
                                            <small class="text-muted d-block">{{ $audit->user->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            @if($audit->event == 'created')
                                                <span class="badge badge-success font-weight-medium">Creado</span>
                                            @elseif($audit->event == 'updated')
                                                <span class="badge badge-warning text-white font-weight-medium">Actualizado</span>
                                            @else
                                                <span class="badge badge-danger font-weight-medium">Eliminado</span>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-light p-1">{{ class_basename($audit->auditable_type) }}</span></td>
                                        <td>#{{ $audit->auditable_id }}</td>
                                        <td><code>{{ $audit->ip_address }}</code></td>
                                        <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('seguridad.audits.show', $audit->id) }}" class="btn btn-outline-warning btn-sm p-2" title="Ver detalle de cambios">
                                                <i class="ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="ti-info-alt d-block mb-2" style="font-size: 24px;"></i>
                                            No se encontraron registros de auditoría con los filtros seleccionados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación --}}
                    <div class="mt-4 d-flex justify-content-end pagination-sm">
                        {{ $audits->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection