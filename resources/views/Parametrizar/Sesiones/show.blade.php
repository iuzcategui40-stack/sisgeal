@extends('layouts.app')

@section('titulo', 'Documento - Diario de Debates #' . $sesion->id)
@section('link_back', route('sesiones.index'))
@section('link_new_none', 'd-none')
@push('styles')
<style>
    /* Estilos para el aspecto de documento físico */
    .document-paper {
        background-color: #ffffff;
        border: 1px solid #dcdcdc;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 3rem 3.5rem;
        max-width: 900px;
        margin: 0 auto;
        font-family: 'Georgia', 'Times New Roman', serif;
        color: #2b2b2b;
        line-height: 1.7;
    }

    .document-header {
        border-bottom: 2px solid #1a252f;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }

    .document-title {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #1a252f;
        text-transform: uppercase;
    }

    .diario-item {
        border-bottom: 1px dashed #cccccc;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .diario-item:last-child {
        border-bottom: none;
    }

    .label-heading {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4b6584;
    }

    .content-text {
        font-size: 1.05rem;
        text-align: justify;
    }

    /* Ajustes específicos para Impresión / Exportación a PDF */
    @media print {
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
        }

        /* Ocultar elementos de la interfaz de la plantilla */
        .sidebar, .navbar, .footer, .btn-print-zone, .alert, header, nav {
            display: none !important;
        }

        .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: none !important;
        }

        .document-paper {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        .page-break {
            page-break-after: always;
        }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    
    {{-- Barra de Herramientas Superior (Se oculta al imprimir) --}}
    <div class="d-flex justify-content-between align-items-center mb-4 max-w-900 mx-auto btn-print-zone" style="max-width: 900px;">
        <a href="{{ route('sesiones.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti-arrow-left mr-1"></i> Volver al listado
        </a>
        <div>
            <a href="{{ route('sesiones.edit', $sesion->id) }}" class="btn btn-outline-warning btn-sm mr-2">
                <i class="ti-pencil mr-1"></i> Editar Documento
            </a>
            <button onclick="window.print();" class="btn btn-primary btn-sm">
                <i class="ti-printer mr-1"></i> Imprimir / Guardar en PDF
            </button>
        </div>
    </div>

    {{-- Hoja de Documento --}}
    <div class="document-paper">
        
        {{-- Encabezado del Documento --}}
        <div class="document-header text-center">
            <h3 class="document-title mb-2">Diario de Debates</h3>
            <h5 class="text-uppercase text-muted font-weight-normal mb-3">
                Sesión {{ $sesion->naturaleza }}
            </h5>
            
            <div class="d-flex justify-content-center align-items-center flex-wrap text-muted small mt-2">
                <span class="mr-3">
                    <strong class="text-dark">Nº de Control:</strong> #{{ $sesion->id }}
                </span>
                <span class="mr-3">•</span>
                <span>
                    <strong class="text-dark">Fecha de Sesión:</strong> 
                    {{ \Carbon\Carbon::parse($sesion->fecha)->isoFormat('DD [de] MMMM [de] YYYY') }}
                </span>
            </div>
        </div>

        {{-- Cuerpo del Diario de Debates --}}
        <div class="document-body">
            @forelse($sesion->diarioDebates as $index => $diario)
                <div class="diario-item">
                    {{-- Encabezado del Sumario --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-dark p-2" style="font-size: 0.9rem;">
                            Sumario N° {{ $diario->nro_sumario }}
                        </span>
                        <span class="small text-muted font-italic">
                            Registro {{ $index + 1 }} de {{ $sesion->diarioDebates->count() }}
                        </span>
                    </div>

                    {{-- Sumario de Cuenta --}}
                    @if($diario->sumario_cuenta)
                        <div class="mb-3">
                            <div class="label-heading mb-1">
                                <i class="ti-files mr-1"></i> Sumario de Cuenta
                            </div>
                            <div class="content-text pl-2 border-left-primary">
                                {!! nl2br(e($diario->sumario_cuenta)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Sumario del Orden del Día --}}
                    @if($diario->sumario_orden_dia)
                        <div class="mb-3">
                            <div class="label-heading mb-1">
                                <i class="ti-list-ol mr-1"></i> Orden del Día
                            </div>
                            <div class="content-text pl-2 border-left-info">
                                {!! nl2br(e($diario->sumario_orden_dia)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Notas / Observaciones --}}
                    @if($diario->notas)
                        <div class="mb-2">
                            <div class="label-heading mb-1">
                                <i class="ti-notepad mr-1"></i> Notas u Observaciones
                            </div>
                            <div class="content-text text-muted bg-light p-3 rounded small">
                                {!! nl2br(e($diario->notas)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="ti-agenda display-4 d-block mb-2"></i>
                    <p>Esta sesión no posee registros cargados en el Diario de Debates.</p>
                </div>
            @endforelse
        </div>

        {{-- Pie de Hoja / Validación --}}
        <div class="document-footer mt-5 pt-4 border-top text-right text-muted small">
            <p class="mb-0">Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

    </div>
</div>
@endsection