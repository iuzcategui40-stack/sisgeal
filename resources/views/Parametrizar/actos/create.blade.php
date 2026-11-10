@extends('layouts.app')

@section('titulo', 'Gestión de Actos Legislativos')
@section('link_back', route('actoslegislativos.index'))
@section('link_new_none', "d-none" )

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
@if(session('mensajeError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ session('mensajeError') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Registrar Nuevo Acto Legislativo</h3>
                    <p class="text-muted">Ingrese la información del instrumento legal y su historial de discusiones.</p>
                </div>
                <div class="col-12 col-xl-4 d-flex justify-content-xl-end align-items-center">
                    <a href="{{ route('actoslegislativos.index') }}" class="btn btn-light btn-sm font-weight-medium">
                        <i class="ti-arrow-left mr-2"></i> Volver al Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de Errores de Validación Generales --}}
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Por favor, verifique los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error) {{-- <-- Corregido aquí --}}
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

    <form class="forms-sample" action="{{ route('actoslegislativos.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
            {{-- BLOQUE 1: Datos Principales del Acto --}}
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-primary"><i class="ti-file mr-2"></i>Información General</h4>
                        <hr>
                        
                        <div class="row mt-4">
                            <div class="form-group col-md-4">
                                <label for="tipo_acto" class="font-weight-bold">Tipo de Acto Legislativo <span class="text-danger">*</span></label>
                                <select class="form-control @error('tipo_acto') is-invalid @enderror" id="tipo_acto" name="tipo_acto" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($tipos as $key)
                                    <option value="{{$key->id}}">{{$key->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="titulo" class="font-weight-bold">Título del Acto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ej. Ley de Contrataciones Públicas" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="codigo_expediente" class="font-weight-bold">Código / Expediente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('codigo_expediente') is-invalid @enderror" id="codigo_expediente" name="codigo_expediente" value="{{ old('codigo_expediente') }}" placeholder="Ej. AN-2026-0041" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="fecha_iniciativa" class="font-weight-bold">Fecha Iniciativa/Cuenta</label>
                                <input type="date" class="form-control" id="fecha_iniciativa" name="fecha_iniciativa" value="{{ old('fecha_iniciativa') }}">
                            </div>
                             <div class="form-group col-md-4">
                                <label for="fecha_iniciativa" class="font-weight-bold">Fecha Distribución</label>
                                <input type="date" class="form-control" id="fecha_distribucion" name="fecha_distribucion" value="{{ old('fecha_distribucion') }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="fecha_aprobacion" class="font-weight-bold">Fecha Aprobación</label>
                                <input type="date" class="form-control" id="fecha_aprobacion" name="fecha_aprobacion" value="{{ old('fecha_aprobacion') }}">
                            </div>
                              <div class="form-group col-md-4">
                                <label for="fecha_aprobacion" class="font-weight-bold">Fecha Sanción</label>
                                <input type="date" class="form-control" id="fecha_sancion" name="fecha_sancion" value="{{ old('fecha_sancion') }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="estatus" class="font-weight-bold">Estatus <span class="text-danger">*</span></label>
                                <select class="form-control @error('estatus') is-invalid @enderror" id="estatus" name="estatus" required>
                                    <option value="En Discusión" {{ old('estatus') == 'En Discusión' ? 'selected' : '' }}>En Discusión</option>
                                    <option value="Aprobado" {{ old('estatus') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Publicado" {{ old('estatus') == 'Publicado' ? 'selected' : '' }}>Publicado</option>
                                    <option value="Diferido" {{ old('estatus') == 'Diferido' ? 'selected' : '' }}>Diferido</option>
                                    <option value="Sancionado" {{ old('estatus') == 'Sancionado' ? 'selected' : '' }}>Sancionado</option>
                                </select>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="resumen" class="font-weight-bold">Enviado / Comisión</label>
                            <textarea class="form-control" id="enviado" name="enviado" rows="4" placeholder="Enviado por comision...">{{ old('enviado') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="resumen" class="font-weight-bold">Resumen / Extracto</label>
                            <textarea class="form-control" id="resumen" name="resumen" rows="4" placeholder="Breve descripción del alcance del acto legislativo...">{{ old('resumen') }}</textarea>
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
                                <label for="nro_gaceta" class="font-weight-bold">Nro. Gaceta Oficial</label>
                                <input type="text" class="form-control" id="nro_gaceta" name="nro_gaceta" value="{{ old('nro_gaceta') }}" placeholder="Ej. 6.812 Extraordinario">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="fecha_gaceta" class="font-weight-bold">Fecha Gaceta Oficial</label>
                                <input type="date" class="form-control" id="fecha_gaceta" name="fecha_gaceta" value="{{ old('fecha_gaceta') }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="archivo_pdf" class="font-weight-bold">Cargar Archivo PDF del Acto</label>
                                <input type="file" class="form-control-file @error('archivo_pdf') is-invalid @enderror" id="archivo_pdf" name="archivo_pdf" accept="application/pdf">
                                <small class="text-muted d-block mt-1">Solo se permiten archivos en formato PDF (.pdf)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BLOQUE 3: Historial Dinámico de Discusiones --}}
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="card-title text-warning mb-0"><i class="ti-comments mr-2"></i>Historial de Discusiones, Observaciones y Documentos</h4>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-discusion">
                                <i class="ti-plus mr-1"></i> Agregar Discusión
                            </button>
                        </div>
                        <hr>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered" id="tabla-discusiones">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 20%;">Inicio<span class="text-danger">*</span></th>
                                        <th style="width: 20%;">Aprobado<span class="text-danger">*</span></th>
                                        <th style="width: 25%;">Tipo Discusión <span class="text-danger">*</span></th>
                                        <th style="width: 35%;">Observaciones / Comentarios</th>
                                        <th style="width: 15%;">Documento Anexo</th>
                                        <th style="width: 5%;" class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Fila inicial por defecto --}}
                                    <tr class="fila-discusion">
                                        <td>
                                            <input type="date" name="discusiones[0][fecha]" class="form-control form-control-sm">
                                        </td>
                                         <td>
                                            <input type="date" name="discusiones[0][aprobado]" class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <select name="discusiones[0][tipo]" class="form-control form-control-sm">
                                                <option value="">Seleccione...</option>
                                                <option value="1era Discusión">1era Discusión</option>
                                                <option value="2da Discusión">2da Discusión</option>
                                                <option value="Discusiones Extraordinaria">Discusiones Extraordinaria</option>
                                                <option value="Consultas Públicas">Consultas Públicas</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="discusiones[0][observaciones]" class="form-control form-control-sm" placeholder="Detalles de la sesión...">
                                        </td>
                                        <td>
                                            <input type="file" name="discusiones[0][documento]" class="form-control-file" accept=".pdf,.doc,.docx">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm p-2 btn-eliminar-fila" disabled>
                                                <i class="ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOTONES DE ACCIÓN DEL FORMULARIO GENERAL --}}
            <div class="col-md-12 text-right mb-5">
                <button type="submit" class="btn btn-primary mr-2 text-white font-weight-medium">
                    <i class="ti-save mr-1"></i> Guardar
                </button>
                <a href="{{ route('actoslegislativos.index') }}" class="btn btn-light">Cancelar</a>
            </div>

        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let contadorFilas = 1;
        const tabla = document.getElementById('tabla-discusiones').getElementsByTagName('tbody')[0];
        const btnAgregar = document.getElementById('btn-agregar-discusion');

        // Función para añadir una fila dinámicamente al historial
        btnAgregar.addEventListener('click', function () {
            const nuevaFila = document.createElement('tr');
            nuevaFila.className = 'fila-discusion';
            
            nuevaFila.innerHTML = `
                <td>
                    <input type="date" name="discusiones[${contadorFilas}][fecha]" class="form-control form-control-sm">
                </td>
                 <td>
                    <input type="date" name="discusiones[${contadorFilas}][aprobado]" class="form-control form-control-sm">
                </td>
                <td>
                    <select name="discusiones[${contadorFilas}][tipo]" class="form-control form-control-sm">
                        <option value="">Seleccione...</option>
                        <option value="1era Discusión">1era Discusión</option>
                        <option value="2da Discusión">2da Discusión</option>
                        <option value="Discusiones Extraordinaria">Discusiones Extraordinaria</option>
                        <option value="Consultas Públicas">Consultas Públicas</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="discusiones[${contadorFilas}][observaciones]" class="form-control form-control-sm" placeholder="Detalles de la sesión...">
                </td>
                <td>
                    <input type="file" name="discusiones[${contadorFilas}][documento]" class="form-control-file" accept=".pdf,.doc,.docx">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm p-2 btn-eliminar-fila">
                        <i class="ti-trash"></i>
                    </button>
                </td>
            `;
            
            tabla.appendChild(nuevaFila);
            contadorFilas++;
            evaluarBotonesEliminar();
        });

        // Evento delegado para eliminar filas agregadas por error
        tabla.addEventListener('click', function (e) {
            if (e.target.closest('.btn-eliminar-fila')) {
                const fila = e.target.closest('tr');
                fila.remove();
                evaluarBotonesEliminar();
            }
        });

        // Controla que no se pueda eliminar la única fila por defecto
        function evaluarBotonesEliminar() {
            const filas = tabla.getElementsByClassName('fila-discusion');
            if (filas.length === 1) {
                filas[0].querySelector('.btn-eliminar-fila').disabled = true;
            } else {
                for (let i = 0; i < filas.length; i++) {
                    filas[i].querySelector('.btn-eliminar-fila').disabled = false;
                }
            }
        }
    });
</script>
@endsection