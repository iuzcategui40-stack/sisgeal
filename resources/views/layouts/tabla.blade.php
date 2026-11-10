<div class="row">
    <!-- Tabla: Últimos Actos Legislativos -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Últimos Actos Legislativos</h4>
                    <a href="{{ route('actoslegislativos.index') }}" class="text-primary small font-weight-bold">Ver Todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Título / Código</th>
                                <th>Estatus</th>
                                <th class="text-center">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosActos as $acto)
                                <tr>
                                    <td class="text-wrap">
                                        <div class="font-weight-bold">{{ Str::limit($acto->titulo, 35) }}</div>
                                        <small class="text-muted">{{ $acto->codigo ?? 'Sin código' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary p-1">{{ $acto->estatus }}</span>
                                    </td>
                                    <td class="text-center small">
                                        {{ $acto->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Sin actos recientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla: Últimos Diarios de Debates / Sesiones -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class=""><strong>Últimos Diarios de Debates</strong></h4>
                    <a href="{{ route('sesiones.index') }}" class="text-primary small font-weight-bold">Ver Todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Naturaleza</th>
                                <th>Extracto Orden del Día</th>
                                <th class="text-center">Fecha Sesión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasSesiones as $sesion)
                                <tr>
                                    <td>
                                        <span class="badge badge-info p-1">{{ $sesion->naturaleza }}</span>
                                    </td>
                                    <td class="text-wrap small">
                                        @php
                                            $primerSumario = $sesion->diarioDebates->first()->sumario_orden_dia ?? 'Sin registro en diario';
                                        @endphp
                                        {{ Str::limit($primerSumario, 40) }}
                                    </td>
                                    <td class="text-center small">
                                        {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Sin sesiones recientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>