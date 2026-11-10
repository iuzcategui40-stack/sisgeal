@extends('layouts.app') {{-- O puedes usar una plantilla limpia si prefieres no mostrar el menú lateral en errores --}}

@section('titulo', 'Acceso Denegado')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center text-center error-page bg-info m-0">
            <div class="row flex-grow">
                <div class="col-lg-7 mx-auto text-white">
                    <div class="row align-items-center d-flex flex-row">
                        <div class="col-lg-6 text-lg-right pr-lg-4">
                            <h1 class="display-1 mb-0 font-weight-bold">403</h1>
                        </div>
                        <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                            <h2 class="font-weight-light">¡ACCESO DENEGADO!</h2>
                            <h3 class="font-weight-light">No cuentas con los permisos necesarios para realizar esta acción o ver este módulo.</h3>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 text-center mt-xl-2">
                            <a class="text-white font-weight-medium" href="{{ route('home') }}">
                                <i class="ti-arrow-left mr-1"></i> Volver al Inicio
                            </a>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 mt-xl-2">
                            <p class="text-white font-weight-medium text-center">
                                Si consideras que esto es un error, por favor contacta al administrador del sistema.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection