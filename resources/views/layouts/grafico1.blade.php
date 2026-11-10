
<div class="row">
    <!-- Muestras Metricas KPI Generales -->
    <div class="col-md-12 grid-margin transparent">
        <div class="row">
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Total Actos Legislativos</p>
                        <p class="fs-30 mb-2">{{ $totalActos }}</p>
                        <p>Registrados en sistema</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Sesiones / Diarios</p>
                        <p class="fs-30 mb-2">{{ $totalSesiones }}</p>
                        <p>Debates procesados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Actos Aprobados</p>
                        <p class="fs-30 mb-2">{{ $actosAprobados }}</p>
                        <p>Sancionados / Firmados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">En Discusión</p>
                        <p class="fs-30 mb-2">{{ $actosEnDiscusión }}</p>
                        <p>Pendientes de revisión</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 NUEVA FILA: Estadísticas de Registros Diarios (Hoy) -->
        <div class="row mt-2">
            <div class="col-md-12">
                <h4 class="card-title text-dark mb-3"><i class="ti-stats-up mr-2"></i> Actividad del Día ({{ now('America/Caracas')->format('d/m/Y') }})</h4>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <p class="mb-4 text-white font-weight-bold">Actos Legislativos Cargados Hoy</p>
                        <p class="fs-30 mb-2 text-white">{{ $actosHoy ?? 0 }}</p>
                        <p class="text-white-50">Nuevos registros en la fecha actual</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <p class="mb-4 text-white font-weight-bold">Sesiones / Diarios Registrados Hoy</p>
                        <p class="fs-30 mb-2 text-white">{{ $sesionesHoy ?? 0 }}</p>
                        <p class="text-white-50">Debates ingresados en la fecha actual</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>