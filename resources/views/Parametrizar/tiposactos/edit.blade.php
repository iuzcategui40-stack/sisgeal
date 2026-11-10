@extends('layouts.app') {{-- Asegúrate de que coincida con tu layout maestro de Skydash --}}
@section('link_back', route('tiposactos.index'))
@section('link_new_none', 'd-none')
@section('content')
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
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Modificar Acto Legislativo</h3>
                    <p class="text-muted">Formulario de actualización de instrumentos legales en el sistema.</p>
                </div>
                <div class="col-12 col-xl-4 d-flex justify-content-xl-end align-items-center">
                    {{-- Botón para regresar al listado general --}}
                    <a href="{{ route('actoslegislativos.index') }}" class="btn btn-light btn-sm font-weight-medium">
                        <i class="ti-arrow-left mr-2"></i> Volver al Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-warning">Editar Registro #{{ $acto->id }}</h4>
                    <p class="card-description">Modifique los campos necesarios y guarde los cambios.</p>
                    
                    {{-- El formulario apunta al método UPDATE y lleva la directiva PUT obligatoria de Laravel --}}
                    <form class="forms-sample mt-4" action="{{ route('tiposactos.update', $acto->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}


                        <div class="form-group row">
                            <label for="descripcion" class="col-sm-3 col-form-label font-weight-bold">Descripción / Resumen</label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="6" placeholder="Contenido o extracto del instrumento legal...">{{ old('descripcion', $acto->descripcion) }}</textarea>
                                @error('descripcion') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-warning mr-2 text-white font-weight-medium">
                                    <i class="ti-save mr-1"></i> Actualizar Cambios
                                </button>
                                <a href="{{ route('actoslegislativos.index') }}" class="btn btn-light">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection