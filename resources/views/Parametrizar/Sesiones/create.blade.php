@extends('layouts.app')
@section('titulo', 'Gestión de Diario de Debates')
@section('link_back', route('sesiones.index'))
@section('link_new_none', 'd-none')

@section('content')
    {{-- Sección de Mensajes de Alerta --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Atención!</strong> Por favor corrige los siguientes errores:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Crear Sesión y Diario de Debates</h3>
                    <p class="text-muted">Formulario de registro de sesiones y sus sumarios detallados.</p>
                </div>
                <div class="col-12 col-xl-4 d-flex justify-content-xl-end align-items-center">
                    <a href="{{ route('sesiones.index') }}" class="btn btn-light btn-sm font-weight-medium">
                        <i class="ti-arrow-left mr-2"></i> Volver al Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-warning">Ficha de Registro</h4>
                    <p class="card-description">Complete los datos generales y cargue la tabla del Diario de Debates</p>
                    
                    <form class="forms-sample mt-4" action="{{ route('sesiones.store') }}" method="POST">
                        {{ csrf_field() }}

                        {{-- DATOS GENERALES DE LA SESIÓN --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="naturaleza" class="font-weight-bold">Naturaleza</label>
                                <input type="text" class="form-control @error('naturaleza') is-invalid @enderror" id="naturaleza" name="naturaleza" value="{{ old('naturaleza') }}" placeholder="Ej. Ordinaria / Extraordinaria" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="naturaleza" class="font-weight-bold">Clasificacion</label>
                                <input type="text" class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" value="{{ old('tipo') }}" placeholder="Ej. Cámara de Diputados" >
                            </div>
                              <div class="col-md-6 form-group">
                                <label for="naturaleza" class="font-weight-bold">Tomo</label>
                                <input type="text" class="form-control @error('tomo') is-invalid @enderror" id="tomo" name="tomo" value="{{ old('tomo') }}" placeholder="Ej. Nro.136">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="fecha" class="font-weight-bold">Fecha</label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha') }}" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- SECCIÓN TABLA EXCEL: DETALLE DEL DIARIO DE DEBATES --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold text-primary mb-0">
                                <i class="ti-layout-grid2 mr-1"></i> Detalle del Diario de Debates
                            </h5>
                            <button type="button" class="btn btn-sm btn-success text-white" id="btn-agregar-fila">
                                <i class="ti-plus mr-1"></i> Agregar Fila
                            </button>
                        </div>

                        <div class="table-responsive border rounded bg-white">
                            <table class="table table-bordered table-hover mb-0" id="tabla-debates">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 160px;">N° Sumario</th>
                                        <th style="width: 280px;">Sumario Cuenta</th>
                                        <th style="width: 320px;">Sumario Orden del Día</th>
                                        <th>Notas / Observaciones</th>
                                        <th style="width: 50px;"><i class="ti-settings"></i></th>
                                    </tr>
                                </thead>
                               <tbody id="tbody-debates">
    @if(old('diario'))
        @foreach(old('diario') as $index => $row)
            <tr class="fila-debate">
                <td class="text-center align-middle font-weight-bold num-fila">{{ $index + 1 }}</td>
                <td>
                    <input type="text" name="diario[{{ $index }}][nro_sumario]" value="{{ $row['nro_sumario'] ?? '' }}" class="form-control form-control-sm cell-input" placeholder="Ej. SUM-001" required>
                </td>
                <td>
                    <textarea name="diario[{{ $index }}][sumario_cuenta]" class="form-control form-control-sm cell-input" rows="2" placeholder="Resumen de cuenta...">{{ $row['sumario_cuenta'] ?? '' }}</textarea>
                </td>
                <td>
                    <textarea name="diario[{{ $index }}][sumario_orden_dia]" class="form-control form-control-sm cell-input" rows="2" placeholder="Detalle orden del día...">{{ $row['sumario_orden_dia'] ?? '' }}</textarea>
                </td>
                <td>
                    <textarea name="diario[{{ $index }}][notas]" class="form-control form-control-sm cell-input" rows="2" placeholder="Notas adicionales...">{{ $row['notas'] ?? '' }}</textarea>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm p-1 btn-eliminar-fila {{ count(old('diario')) == 1 ? 'd-none' : '' }}" title="Eliminar fila">
                        <i class="ti-close"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    @else
        {{-- Fila 0 inicial por defecto --}}
        <tr class="fila-debate">
            <td class="text-center align-middle font-weight-bold num-fila">1</td>
            <td>
                <input type="text" name="diario[0][nro_sumario]" class="form-control form-control-sm cell-input" placeholder="Ej. SUM-001" required>
            </td>
            <td>
                <textarea name="diario[0][sumario_cuenta]" class="form-control form-control-sm cell-input" rows="2" placeholder="Resumen de cuenta..."></textarea>
            </td>
            <td>
                <textarea name="diario[0][sumario_orden_dia]" class="form-control form-control-sm cell-input" rows="2" placeholder="Detalle orden del día..."></textarea>
            </td>
            <td>
                <textarea name="diario[0][notas]" class="form-control form-control-sm cell-input" rows="2" placeholder="Notas adicionales..."></textarea>
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-outline-danger btn-sm p-1 btn-eliminar-fila d-none" title="Eliminar fila">
                    <i class="ti-close"></i>
                </button>
            </td>
        </tr>
    @endif
</tbody>
                            </table>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="ti-info-alt"></i> Tip: Utiliza la tecla <strong>TAB</strong> para desplazarte rápidamente entre las celdas.
                        </small>

                        {{-- BOTONES DE ACCIÓN --}}
                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('sesiones.index') }}" class="btn btn-light mr-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary text-white font-weight-medium">
                                    <i class="ti-save mr-1"></i> Guardar Sesión y Diario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT DINÁMICO ESTILO EXCEL --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dentro de tu script de JS, reemplaza la inicialización de contadorFilas por:
        let contadorFilas = document.querySelectorAll('.fila-debate').length;
        //let contadorFilas = 1;
        const tbody = document.getElementById('tbody-debates');
        const btnAgregar = document.getElementById('btn-agregar-fila');

        // Función para agregar nueva fila a la tabla
        btnAgregar.addEventListener('click', function () {
            const nuevaFila = document.createElement('tr');
            nuevaFila.classList.add('fila-debate');
            
            nuevaFila.innerHTML = `
                <td class="text-center align-middle font-weight-bold num-fila">${contadorFilas + 1}</td>
                <td>
                    <input type="text" name="diario[${contadorFilas}][nro_sumario]" class="form-control form-control-sm cell-input" placeholder="Ej. SUM-00${contadorFilas + 1}" required>
                </td>
                <td>
                    <textarea name="diario[${contadorFilas}][sumario_cuenta]" class="form-control form-control-sm cell-input" rows="2" placeholder="Resumen de cuenta..."></textarea>
                </td>
                <td>
                    <textarea name="diario[${contadorFilas}][sumario_orden_dia]" class="form-control form-control-sm cell-input" rows="2" placeholder="Detalle orden del día..."></textarea>
                </td>
                <td>
                    <textarea name="diario[${contadorFilas}][notas]" class="form-control form-control-sm cell-input" rows="2" placeholder="Notas adicionales..."></textarea>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-outline-danger btn-sm p-1 btn-eliminar-fila" title="Eliminar fila">
                        <i class="ti-close"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(nuevaFila);
            
            // Enfocar automáticamente la primera celda de la nueva fila creada
            nuevaFila.querySelector('input').focus();
            
            contadorFilas++;
            actualizarBotonesYNumeracion();
        });

        // Evento delegado para eliminar filas de la tabla
        tbody.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-eliminar-fila') || e.target.closest('.btn-eliminar-fila')) {
                const fila = e.target.closest('tr');
                fila.remove();
                actualizarBotonesYNumeracion();
            }
        });

        // Reordena el contador de filas (1, 2, 3...) y oculta el botón eliminar si solo queda 1 fila
        function actualizarBotonesYNumeracion() {
            const filas = tbody.querySelectorAll('.fila-debate');
            
            filas.forEach((fila, index) => {
                fila.querySelector('.num-fila').textContent = index + 1;
                const btnEliminar = fila.querySelector('.btn-eliminar-fila');
                
                if (filas.length === 1) {
                    btnEliminar.classList.add('d-none');
                } else {
                    btnEliminar.classList.remove('d-none');
                }
            });
        }
    });
</script>
@endpush

{{-- ESTILOS PARA APARIENCIA TIPO HOJA DE CÁLCULO --}}
@push('styles')
<style>
    #tabla-debates th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px;
        background-color: #f8f9fa;
    }
    #tabla-debates td {
        padding: 5px !important;
        vertical-align: top;
    }
    .cell-input {
        border: 1px solid transparent;
        border-radius: 2px;
        background-color: transparent;
        transition: all 0.2s ease;
    }
    .cell-input:focus {
        border-color: #4b49ac !important;
        background-color: #fff !important;
        box-shadow: 0 0 5px rgba(75, 73, 172, 0.3) !important;
    }
    .cell-input:hover {
        border-color: #ced4da;
        background-color: #fff;
    }
</style>
@endpush
@endsection