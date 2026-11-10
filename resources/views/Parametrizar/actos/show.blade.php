@extends('layouts.app')

@section('titulo', 'Detalle de Acto Legislativo')
@section('link_back', route('actoslegislativos.index'))
@section('link_new_none', "d-none")

@section('content')
<div class="content-wrapper">
    {{-- Encabezado de la Vista --}}
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Detalle del Acto Legislativo</h3>
                    <p class="text-muted">Consulte la información detallada del instrumento legal y su historial de discusiones.</p>
                </div>
                <div class="col-12 col-xl-4 d-flex justify-content-xl-end align-items-center">
                    <a href="{{ route('actoslegislativos.edit', $acto->id) }}" class="btn btn-warning btn-sm font-weight-medium text-white mr-2">
                        <i class="ti-pencil mr-1"></i> Editar
                    </a>
                    <a href="{{ route('actoslegislativos.index') }}" class="btn btn-light btn-sm font-weight-medium">
                        <i class="ti-arrow-left mr-2"></i> Volver al Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BLOQUE 1: Datos Principales del Acto --}}
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary"><i class="ti-file mr-2"></i>Información General</h4>
                    <hr>
                    
                    <div class="row mt-4">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Tipo de Acto Legislativo</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->tipoActo->descripcion ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="form-group col-md-5">
                            <label class="font-weight-bold">Título del Acto</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->titulo ?? 'Sin título' }}
                            </p>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="font-weight-bold">Código / Expediente</label>
                            <p class="form-control-static border-bottom pb-2">
                                <span class="badge badge-outline-primary">{{ $acto->codigo ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Fecha Iniciativa / Cuenta</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->fecha_iniciativa ? \Carbon\Carbon::parse($acto->fecha_iniciativa)->format('d/m/Y') : 'No registrada' }}
                            </p>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Fecha Distribución</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->fecha_distribucion ? \Carbon\Carbon::parse($acto->fecha_distribucion)->format('d/m/Y') : 'No registrada' }}
                            </p>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Fecha Aprobación</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->fecha_aprobacion ? \Carbon\Carbon::parse($acto->fecha_aprobacion)->format('d/m/Y') : 'No registrada' }}
                            </p>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Fecha Sanción</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->fecha_sancion ? \Carbon\Carbon::parse($acto->fecha_sancion)->format('d/m/Y') : 'No registrada' }}
                            </p>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Estatus</label>
                            <p class="form-control-static border-bottom pb-2">
                                @switch($acto->estatus)
                                    @case('Aprobado')
                                    @case('Publicado')
                                    @case('Sancionado')
                                        <span class="badge badge-success">{{ $acto->estatus }}</span>
                                        @break
                                    @case('En Discusión')
                                        <span class="badge badge-info">{{ $acto->estatus }}</span>
                                        @break
                                    @case('Diferido')
                                        <span class="badge badge-warning">{{ $acto->estatus }}</span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary">{{ $acto->estatus ?? 'N/A' }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Enviado / Comisión</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $acto->enviado ?? 'Sin información especificada.' }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Resumen / Extracto</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $acto->resumen ?? 'Sin resumen disponible.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 2: Datos de Gaceta Oficial e Instrumento PDF --}}
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-success"><i class="ti-book mr-2"></i>Gaceta Oficial y Adjuntos</h4>
                    <hr>
                    
                    <div class="row mt-4">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Nro. Gaceta Oficial</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->gaceta_oficial ?? 'No registrada' }}
                            </p>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Fecha Gaceta Oficial</label>
                            <p class="form-control-static border-bottom pb-2">
                                {{ $acto->fecha_gaceta_oficial ? \Carbon\Carbon::parse($acto->fecha_gaceta_oficial)->format('d/m/Y') : 'No registrada' }}
                            </p>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Archivo PDF Principal</label>
                            <div>
                                @if($acto->url_archivo)
                                    <a href="{{ asset('storage/doc/actos/'.$acto->url_archivo) }}" target="_blank" class="badge badge-info p-2">
                                        <i class="ti-download mr-1"></i> Abrir en ventana nueva
                                    </a>
                                @else
                                    <span class="badge badge-secondary p-2">Sin archivo adjunto</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- VISOR DE PDF EMBEBIDO --}}
                    @if($acto->url_archivo)
                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="font-weight-bold text-dark"><i class="ti-eye mr-1"></i> Vista Previa del Archivo Actual:</label>
                                <div class="embed-responsive embed-responsive-16by9 border rounded bg-light" style="min-height: 500px;">
                                    <iframe class="embed-responsive-item" 
                                            src="{{ asset('storage/doc/actos/'.$acto->url_archivo) }}#toolbar=1&navpanes=0" 
                                            type="application/pdf" 
                                            style="width: 100%; height: 500px;" 
                                            allowfullscreen>
                                        <p>Tu navegador no soporta la visualización de archivos PDF. 
                                           <a href="{{ asset('storage/doc/actos/'.$acto->url_archivo) }}" target="_blank">Haz clic aquí para descargar el PDF.</a>
                                        </p>
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3" role="alert">
                            <i class="ti-info-alt mr-1"></i> Este acto legislativo no posee un archivo PDF adjunto actualmente.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: Historial Dinámico de Discusiones --}}
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-warning mb-0"><i class="ti-comments mr-2"></i>Historial de Discusiones, Observaciones y Documentos</h4>
                    <hr>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 15%;">Inicio</th>
                                    <th style="width: 15%;">Aprobado</th>
                                    <th style="width: 20%;">Tipo Discusión</th>
                                    <th style="width: 35%;">Observaciones / Comentarios</th>
                                    <th style="width: 15%;">Documento Anexo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($acto->discusiones) && count($acto->discusiones) > 0)
                                    @foreach($acto->discusiones as $disc)
                                        <tr>
                                            <td>
                                                {{ $disc->fecha ? \Carbon\Carbon::parse($disc->fecha)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                {{ $disc->aprobado ? \Carbon\Carbon::parse($disc->aprobado)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                <span class="badge badge-outline-info font-weight-medium">{{ $disc->tipo ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                {{ $disc->observaciones ?? 'Sin observaciones' }}
                                            </td>
                                            <td>
                                                @if($disc->url_archivo)
                                                    <a href="{{ asset('storage/doc/actos/discusiones/'.$disc->url_archivo) }}" target="_blank" class="badge badge-outline-info p-1.5 d-inline-flex align-items-center">
                                                        <i class="ti-clip mr-1"></i> Ver Adjunto
                                                    </a>
                                                @else
                                                    <span class="text-muted"><small>Sin anexo</small></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            <i class="ti-info-alt mr-1"></i> No se han registrado discusiones ni eventos para este acto legislativo.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOTONES DE NAVEGACIÓN --}}
        <div class="col-md-12 text-right mb-5">
            <a href="{{ route('actoslegislativos.edit', $acto->id) }}" class="btn btn-warning text-white font-weight-medium mr-2">
                <i class="ti-pencil mr-1"></i> Editar Acto
            </a>
            <a href="{{ route('actoslegislativos.index') }}" class="btn btn-light">Volver al Historial</a>
        </div>
    </div>
</div>
@endsection