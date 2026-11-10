@extends('layouts.app')
@section('titulo', 'Documentación del Sistema')
@section('link_back', route('home'))
@section('link_new_none', 'd-none')

@section('content')
<div class="content-wrapper">
    <!-- Encabezado de la página -->
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold text-primary">Manual y Documentación del Sistema</h3>
                    <p class="text-muted">Guía de uso para la gestión legislativa, registro de actos, sesiones y administración de perfiles.</p>
                </div>
                <div class="col-12 col-xl-4 d-flex justify-content-xl-end align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-light btn-sm font-weight-medium">
                        <i class="ti-arrow-left mr-2"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido del Manual -->
    <div class="row">
        <!-- Navegación lateral rápida por pestañas / secciones -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-info mb-3">
                        <i class="ti-bookmark-alt mr-1"></i> Índice
                    </h4>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active font-weight-bold" id="tab-intro-tab" data-toggle="pill" href="#tab-intro" role="tab" aria-controls="tab-intro" aria-selected="true">
                            <i class="ti-home mr-2"></i> 1. Introducción
                        </a>
                        <a class="nav-link font-weight-bold" id="tab-tiposactos-tab" data-toggle="pill" href="#tab-tiposactos" role="tab" aria-controls="tab-tiposactos" aria-selected="false">
                            <i class="ti-layout-grid2 mr-2"></i> 2. Tipos de Actos
                        </a>
                        <a class="nav-link font-weight-bold" id="tab-actos-tab" data-toggle="pill" href="#tab-actos" role="tab" aria-controls="tab-actos" aria-selected="false">
                            <i class="ti-files mr-2"></i> 3. Actos Legislativos
                        </a>
                        <a class="nav-link font-weight-bold" id="tab-sesiones-tab" data-toggle="pill" href="#tab-sesiones" role="tab" aria-controls="tab-sesiones" aria-selected="false">
                            <i class="ti-agenda mr-2"></i> 4. Diario de Debates
                        </a>
                        <a class="nav-link font-weight-bold" id="tab-seguridad-tab" data-toggle="pill" href="#tab-seguridad" role="tab" aria-controls="tab-seguridad" aria-selected="false">
                            <i class="ti-shield mr-2"></i> 5. Seguridad y Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuerpo de explicaciones -->
        <div class="col-md-9 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        
                        <!-- 1. Introducción -->
                        <div class="tab-pane fade show active" id="tab-intro" role="tabpanel" aria-labelledby="tab-intro-tab">
                            <h4 class="card-title text-primary font-weight-bold">Bienvenido al Sistema de Gestión de Archivo Legislativo</h4>
                            <p class="text-muted">Este sistema centraliza el control, archivo y seguimiento de la actividad legislativa, permitiendo mantener un registro ordenado de las normativas, sesiones plenarias y privilegios de acceso del personal.</p>
                            <hr class="my-4">
                            <h5 class="font-weight-bold text-dark">Características principales:</h5>
                            <ul class="pl-3 text-muted">
                                <li class="mb-2">Parametrización ágil de catálogos y clasificaciones.</li>
                                <li class="mb-2">Registro detallado de Actos Legislativos y soportes documentales.</li>
                                <li class="mb-2">Control interactivo tipo hoja de cálculo para el Diario de Debates.</li>
                                <li class="mb-2">Seguridad basada en roles y una matriz de permisos modular (Ver, Agregar, Editar, Eliminar).</li>
                            </ul>
                        </div>

                        <!-- 2. Tipos de Actos -->
                        <div class="tab-pane fade" id="tab-tiposactos" role="tabpanel" aria-labelledby="tab-tiposactos-tab">
                            <h4 class="card-title text-primary font-weight-bold">Gestión de Tipos de Actos</h4>
                            <p class="text-muted">La sección de parametrización de <strong>Tipo Acto</strong> define las categorías fundamentales bajo las cuales se clasifican los documentos legislativos (ej. Acuerdos, Leyes Orgánicas, Decretos, Resoluciones).</p>
                            <hr class="my-4">
                            <h5 class="font-weight-bold text-dark"><i class="ti-angle-right text-warning mr-1"></i> ¿Cómo crear un Tipo de Acto?</h5>
                            <ol class="pl-3 text-muted">
                                <li class="mb-2">Dirígete al menú lateral y despliega la opción <strong>Parametrización</strong>.</li>
                                <li class="mb-2">Haz clic en <strong>Tipo Acto</strong> para acceder al listado general.</li>
                                <li class="mb-2">Haz clic en el botón superior <strong>Nuevo Tipo de Acto</strong>.</li>
                                <li class="mb-2">Completa el nombre de la clasificación y una breve descripción en el formulario.</li>
                                <li class="mb-2">Haz clic en <strong>Guardar</strong> para registrarlo en el sistema. Quedará disponible inmediatamente para ser seleccionado en los Actos Legislativos.</li>
                            </ol>
                        </div>

                        <!-- 3. Actos Legislativos -->
                        <div class="tab-pane fade" id="tab-actos" role="tabpanel" aria-labelledby="tab-actos-tab">
                            <h4 class="card-title text-primary font-weight-bold">Registro de Actos Legislativos</h4>
                            <p class="text-muted">Módulo destinado a almacenar la información oficial de cada instrumento legal discutido y aprobado dentro del parlamento.</p>
                            <hr class="my-4">
                            <h5 class="font-weight-bold text-dark"><i class="ti-angle-right text-warning mr-1"></i> ¿Cómo registrar un Acto Legislativo?</h5>
                            <ol class="pl-3 text-muted">
                                <li class="mb-2">En el menú lateral, despliega la sección <strong>Registro</strong> y selecciona <strong>Actos Legislativos</strong>.</li>
                                <li class="mb-2">Selecciona el botón de creación de un nuevo registro.</li>
                                <li class="mb-2">Completa los campos obligatorios: Tipo de Acto asociado, número oficial, fecha de aprobación y síntesis o sumario del contenido.</li>
                                <li class="mb-2">Adjunta o ingresa las observaciones pertinentes y haz clic en <strong>Guardar Acto</strong>.</li>
                                <li class="mb-2">Desde la tabla principal podrás exportar los listados a formatos Excel o PDF según requieras.</li>
                            </ol>
                        </div>

                        <!-- 4. Sesiones y Diario de Debates -->
                        <div class="tab-pane fade" id="tab-sesiones" role="tabpanel" aria-labelledby="tab-sesiones-tab">
                            <h4 class="card-title text-primary font-weight-bold">Sesiones y Diario de Debates</h4>
                            <p class="text-muted">Permite registrar las sesiones plenarias (Ordinarias / Extraordinarias) junto con una tabla dinámica tipo hoja de cálculo que detalla el sumario de cuenta, orden del día y notas de cada debate.</p>
                            <hr class="my-4">
                            <h5 class="font-weight-bold text-dark"><i class="ti-angle-right text-warning mr-1"></i> ¿Cómo crear una Sesión y su Diario de Debates?</h5>
                            <ol class="pl-3 text-muted">
                                <li class="mb-2">En el menú lateral, entra a <strong>Registro</strong> > <strong>Diario de Debates</strong>.</li>
                                <li class="mb-2">Haz clic en el botón de registro para abrir la ficha de la sesión.</li>
                                <li class="mb-2">Rellena los datos generales de la sesión: <strong>Naturaleza</strong> (Ej. Ordinaria) y la <strong>Fecha</strong> de realización.</li>
                                <li class="mb-2">En la sección inferior <em>Detalle del Diario de Debates</em>, el sistema te provee una fila inicial interactiva. Completa el <strong>N° Sumario</strong>, el <strong>Sumario de Cuenta</strong> y el <strong>Orden del Día</strong>.</li>
                                <li class="mb-2">Si necesitas registrar más puntos en la sesión, haz clic en el botón verde <strong><i class="ti-plus"></i> Agregar Fila</strong> tantas veces como sea necesario (el sistema exige al menos 1 fila obligatoria para permitir el almacenamiento).</li>
                                <li class="mb-2">Haz clic en <strong>Guardar Sesión y Diario</strong> para consolidar la información en la base de datos de manera transaccional.</li>
                            </ol>
                        </div>

                        <!-- 5. Seguridad y Roles -->
                        <div class="tab-pane fade" id="tab-seguridad" role="tabpanel" aria-labelledby="tab-seguridad-tab">
                            <h4 class="card-title text-primary font-weight-bold">Seguridad, Roles y Matriz de Permisos</h4>
                            <p class="text-muted">El sistema maneja un control estricto de acceso basado en perfiles o roles configurables.</p>
                            <hr class="my-4">
                            <h5 class="font-weight-bold text-dark"><i class="ti-angle-right text-warning mr-1"></i> Administración de Accesos</h5>
                            <p class="text-muted">Desde el módulo de <strong>Roles y Permisos</strong> (en la sección Seguridad), los administradores pueden:</p>
                            <ul class="pl-3 text-muted">
                                <li class="mb-2">Crear nuevos perfiles de usuario corporativo.</li>
                                <li class="mb-2">Marcar mediante una matriz de casillas los permisos exactos de <strong>Ver, Agregar, Editar y Eliminar</strong> para cada módulo del menú (Tipos de Actos, Actos Legislativos, Diario de Debates, etc.).</li>
                                <li class="mb-2">Restringir automáticamente la visualización de enlaces en el menú lateral y proteger las rutas del sistema contra accesos no autorizados (mostrando una vista 403 personalizada en caso de restricción).</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection