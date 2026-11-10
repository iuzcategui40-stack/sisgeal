@extends('layouts.app')

@section('titulo', 'Gestión de Actos Legislativos')
@section('link_back_none', "d-none")

@if(Auth::user()->tienePermiso('sesiones', 'create'))
    @section('link_new', route('actoslegislativos.crear'))
@else
    @section('link_new_none', "d-none")
@endif



@section('content')
<div class="content-wrapper">
    
    {{-- Sección de Mensajes de Éxito o Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ session('success') }}
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

    {{-- PANEL DE FILTROS AVANZADOS Y EXPORTACIÓN --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="ti-filter mr-2">Filtros de Búsqueda y Reportes</i> 
                        </h5>
                        <div class="mt-2 mt-sm-0">
                            {{-- Botones de Exportación --}}
                            <a href="{{ route('actoslegislativos.exportar.excel', request()->all()) }}" class="btn btn-sm btn-success text-white font-weight-medium mr-1">
                                <i class="ti-export mr-1"></i> Exportar Excel
                            </a>

                            <a href="{{ route('actoslegislativos.exportar.pdf', request()->all()) }}" class="btn btn-sm btn-danger text-white font-weight-medium">
                                <i class="ti-printer mr-1"></i> Exportar PDF
                            </a>
                        </div>
                    </div>
                    <hr>
                    
                    <form action="{{ route('actoslegislativos.index') }}" method="GET" class="mt-3">
                        <div class="row">
                            {{-- Búsqueda por palabra clave --}}
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold small">Buscar por Palabra Clave</label>
                                <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Título, descripción, código..." value="{{ request('buscar') }}">
                            </div>

                            {{-- Filtro por Tipo de Acto --}}
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold small">Tipo de Acto</label>
                                <select name="tipo_acto" class="form-control form-control-sm">
                                    <option value="">Todos los tipos...</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ request('tipo_acto') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filtro por Estatus --}}
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold small">Estatus</label>
                                <select name="estatus" class="form-control form-control-sm">
                                    <option value="">Todos...</option>
                                    <option value="En Discusión" {{ request('estatus') == 'En Discusión' ? 'selected' : '' }}>En Discusión</option>
                                    <option value="Aprobado" {{ request('estatus') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Publicado" {{ request('estatus') == 'Publicado' ? 'selected' : '' }}>Publicado</option>
                                    <option value="Diferido" {{ request('estatus') == 'Diferido' ? 'selected' : '' }}>Diferido</option>
                                </select>
                            </div>

                            {{-- Filtro por Fecha Iniciativa --}}
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold small">Fecha Iniciativa</label>
                                <input type="date" name="fecha_iniciativa" class="form-control form-control-sm" value="{{ request('fecha_iniciativa') }}">
                            </div>

                            {{-- Filtro por Fecha Aprobación --}}
                            <div class="form-group col-md-2">
                                <label class="font-weight-bold small">Fecha Aprobación</label>
                                <input type="date" name="fecha_aprobacion" class="form-control form-control-sm" value="{{ request('fecha_aprobacion') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-sm btn-primary px-4">
                                    <i class="ti-search mr-1"></i> Filtrar Actos
                                </button>
                                @if(request()->anyFilled(['buscar', 'tipo_acto', 'estatus', 'fecha_iniciativa', 'fecha_aprobacion']))
                                    <a href="{{ route('actoslegislativos.index') }}" class="btn btn-sm btn-light ml-1">
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

    {{-- TABLA DE REGISTROS --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div>
                        <h4 class="card-title mb-1">Historial de Actos Registrados</h4>
                        <p class="card-description mb-3">Listado estructurado por orden cronológico de carga</p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Título / Descripción</th>
                                    <th>Tipo</th>
                                    <th class="text-center">Año Carga</th>
                                    <th>Estatus</th>
                                    <th>F. Aprobación</th>
                                    <th>F. Sancionado</th>
                                    <th class="text-center">Documento</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($actos as $acto)
                                    <tr>
                                        <td><strong>#{{ $acto->id }}</strong></td>
                                        <td class="text-wrap" style="max-width: 280px;">
                                            <span class="font-weight-bold text-dark d-block">{{ $acto->titulo }}</span>
                                            <small class="text-muted d-block mt-1">Código: <span class="badge badge-light p-1 font-weight-bold">{{ $acto->codigo }}</span></small>
                                            <small class="text-muted d-block mt-1 text-truncate" style="max-width: 260px;">{{ Str::limit($acto->resumen ?? $acto->descripcion, 60) }}</small>
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $acto->tipoacto->descripcion }}</span>
                                        </td>
                                        <td class="text-center">
                                            {{-- Muestra el año basándose en el timestamp 'created_at' --}}
                                            <span class="badge badge-secondary py-1 px-2 font-weight-bold">
                                                {{ $acto->created_at ? $acto->created_at->format('Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($acto->estatus == 'Aprobado')
                                                <span class="badge badge-success font-weight-medium">Aprobado</span>
                                            @elseif($acto->estatus == 'En Discusión')
                                                <span class="badge badge-warning text-white font-weight-medium">En Discusión</span>
                                            @elseif($acto->estatus == 'Publicado')
                                                <span class="badge badge-info font-weight-medium">Publicado</span>
                                             @elseif($acto->estatus == 'Sancionado')
                                                <span class="badge badge-primary font-weight-medium">Sancionado</span>    
                                            @else
                                                <span class="badge badge-light text-dark font-weight-medium">Diferido</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $acto->fecha_aprobacion ? \Carbon\Carbon::parse($acto->fecha_aprobacion)->format('d/m/Y') : 'Pendiente' }}
                                        </td>
                                        <td>
                                            {{ $acto->fecha_sancion ? \Carbon\Carbon::parse($acto->fecha_sancion)->format('d/m/Y') : 'Pendiente' }}
                                        </td>
                                        <td class="text-center">
                                            {{-- Botón de Descarga del PDF Principal --}}
                                            @if($acto->url_archivo)
                                                <a href="{{ asset('storage/doc/actos/'.$acto->url_archivo) }}" target="_blank" class="btn btn-outline-info btn-icon-text p-2 btn-sm" title="Descargar PDF Principal">
                                                    <i class="ti-download mr-1"></i> PDF
                                                </a>
                                            @else
                                                <span class="text-muted small"><i class="ti-na mr-1"></i> Sin archivo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('actoslegislativos.show', $acto->id) }}" class="btn btn-outline-warning btn-sm p-2" title="Visualizar Acto">
                                                    <i class="ti-eye"></i>
                                                </a>
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('actoslegislativos.edit', $acto->id) }}" class="btn btn-outline-warning btn-sm p-2" title="Editar Acto">
                                                    <i class="ti-pencil"></i>
                                                </a>

                                                {{-- Formulario y Botón Eliminar --}}
                                                <form action="{{ route('actoslegislativos.destroy', $acto->id) }}" method="POST" onsubmit="return confirm('¿Está completamente seguro de eliminar este acto legislativo? Se borrarán permanentemente sus discusiones y archivos físicos asociados.');" style="display: inline;">
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
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="ti-info-alt d-block mb-2 style-2" style="font-size: 24px;"></i>
                                            No se encontraron actos legislativos que coincidan con los criterios o filtros establecidos.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación --}}
                    <div class="mt-4 d-flex justify-content-end pagination-sm">
                        {{ $actos->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection