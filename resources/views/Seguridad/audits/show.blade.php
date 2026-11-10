@extends('layouts.app')

@section('titulo', 'Detalle de Auditoría #' . $audit->id)
@section('link_back', route('seguridad.audits.index'))

@section('content')
<div class="content-wrapper">
    
    <div class="row">
        {{-- INFORMACIÓN GENERAL DEL EVENTO --}}
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary mb-3">
                        <i class="ti-info-alt mr-2"></i> Resumen del Evento
                    </h4>
                    <hr>
                    <ul class="list-arrow pl-0" style="list-style: none;">
                        <li class="mb-2"><strong>ID Auditoría:</strong> #{{ $audit->id }}</li>
                        <li class="mb-2"><strong>Usuario:</strong> {{ $audit->user->name ?? 'Sistema / Invitado' }}</li>
                        <li class="mb-2"><strong>Correo:</strong> {{ $audit->user->email ?? 'N/A' }}</li>
                        <li class="mb-2"><strong>Acción / Evento:</strong> 
                            <span class="badge badge-{{ $audit->event == 'created' ? 'success' : ($audit->event == 'updated' ? 'warning' : 'danger') }}">
                                {{ ucfirst($audit->event) }}
                            </span>
                        </li>
                        <li class="mb-2"><strong>Modelo Afectado:</strong> <code>{{ $audit->auditable_type }}</code></li>
                        <li class="mb-2"><strong>ID del Registro:</strong> #{{ $audit->auditable_id }}</li>
                        <li class="mb-2"><strong>Dirección IP:</strong> {{ $audit->ip_address }}</li>
                        <li class="mb-2"><strong>Navegador / User Agent:</strong> <br><small class="text-muted">{{ $audit->user_agent }}</small></li>
                        <li class="mb-2"><strong>Fecha y Hora:</strong> {{ $audit->created_at->format('d/m/Y H:i:s') }}</li>
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('seguridad.audits.index') }}" class="btn btn-light btn-block">
                            <i class="ti-arrow-left mr-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- COMPARATIVA DE CAMBIOS (VALORES ANTIGUOS VS NUEVOS) --}}
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary mb-3">
                        <i class="ti-layers-alt mr-2"></i> Cambios Realizados
                    </h4>
                    <p class="card-description">Comparación de los datos antes y después de la operación</p>
                    <hr>

                    @if($audit->event == 'created')
                        <div class="alert alert-success">
                            <strong>Registro Creado:</strong> A continuación se muestran los datos con los que se dio de alta el registro.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor Ingresado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($audit->new_values as $key => $value)
                                        <tr>
                                            <td><strong>{{ $key }}</strong></td>
                                            <td><span class="text-success">{{ is_array($value) ? json_encode($value) : $value }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @elseif($audit->event == 'updated')
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor Anterior (Old)</th>
                                        <th>Valor Nuevo (New)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($audit->new_values as $key => $newValue)
                                        @php
                                            $oldValue = $audit->old_values[$key] ?? null;
                                            $isChanged = $oldValue != $newValue;
                                        @endphp
                                        <tr class="{{ $isChanged ? 'table-warning' : '' }}">
                                            <td><strong>{{ $key }}</strong></td>
                                            <td><span class="text-danger">{{ is_array($oldValue) ? json_encode($oldValue) : ($oldValue ?? '(Vacío)') }}</span></td>
                                            <td><span class="text-success font-weight-bold">{{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? '(Vacío)') }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @elseif($audit->event == 'deleted')
                        <div class="alert alert-danger">
                            <strong>Registro Eliminado:</strong> Estos eran los datos que tenía el registro antes de ser borrado permanentemente.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor que tenía</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($audit->old_values as $key => $value)
                                        <tr>
                                            <td><strong>{{ $key }}</strong></td>
                                            <td><span class="text-danger">{{ is_array($value) ? json_encode($value) : $value }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection