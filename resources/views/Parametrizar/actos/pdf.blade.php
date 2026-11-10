<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Actos Legislativos</title>
    <style>
        @page {
            margin: 12mm 10mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #2b2b2b;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 6px;
        }
        .logo-container {
            width: 15%;
            vertical-align: middle;
        }
        .logo-img {
            max-height: 50px; /* Ajusta la altura máxima de tu logo */
            width: auto;
        }
        .header-titles {
            width: 55%;
            vertical-align: middle;
            padding-left: 10px;
        }
        .header-title {
            font-size: 15pt;
            font-weight: bold;
            color: #0d6efd;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 8.5pt;
            color: #495057;
            margin-top: 2px;
        }
        .meta-info {
            width: 30%;
            text-align: right;
            vertical-align: middle;
            font-size: 8pt;
            color: #6c757d;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            font-size: 8pt;
            border: 1px solid #1e293b;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            font-size: 8pt;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-success { background-color: #d1e7dd; color: #0f5132; }
        .badge-warning { background-color: #fff3cd; color: #664d03; }
        .badge-info { background-color: #cff4fc; color: #055160; }
        .badge-primary { background-color: #cfe2ff; color: #084298; }
        .badge-light { background-color: #e2e3e5; color: #41464b; }
        .code-tag {
            font-family: monospace;
            background-color: #f1f5f9;
            padding: 1px 4px;
            border-radius: 3px;
            color: #475569;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <!-- Columna del Logo -->
            <td class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" class="logo-img" alt="Logo Institucional">
            </td>
            <!-- Columna de Títulos -->
            <td class="header-titles">
                <div class="header-title">Gestión de Actos Legislativos</div>
                <div class="header-subtitle">Reporte Oficial de Registros</div>
            </td>
            <!-- Columna de Metadatos -->
            <td class="meta-info">
                <strong>Fecha:</strong> {{ date('d/m/Y H:i') }}<br>
                <strong>Total Registros:</strong> {{ $actos->count() }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 38%;">Título / Código / Resumen</th>
                <th style="width: 17%;">Tipo de Acto</th>
                <th style="width: 8%;" class="text-center">Año</th>
                <th style="width: 12%;">Estatus</th>
                <th style="width: 10%;" class="text-center">F. Aprobación</th>
                <th style="width: 10%;" class="text-center">F. Sancionado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actos as $acto)
                <tr>
                    <td class="font-bold">#{{ $acto->id }}</td>
                    <td>
                        <div class="font-bold">{{ $acto->titulo }}</div>
                        @if($acto->codigo)
                            <div style="margin-top: 2px;"><span class="code-tag">Código: {{ $acto->codigo }}</span></div>
                        @endif
                        <div style="color: #64748b; font-size: 7.5pt; margin-top: 2px;">
                            {{ Str::limit($acto->resumen ?? $acto->descripcion, 90) }}
                        </div>
                    </td>
                    <td>{{ $acto->tipoacto->descripcion ?? 'N/A' }}</td>
                    <td class="text-center">
                        {{ $acto->created_at ? $acto->created_at->format('Y') : 'N/A' }}
                    </td>
                    <td>
                        @if($acto->estatus == 'Aprobado')
                            <span class="badge badge-success">Aprobado</span>
                        @elseif($acto->estatus == 'En Discusión')
                            <span class="badge badge-warning">En Discusión</span>
                        @elseif($acto->estatus == 'Publicado')
                            <span class="badge badge-info">Publicado</span>
                        @elseif($acto->estatus == 'Sancionado')
                            <span class="badge badge-primary">Sancionado</span>
                        @else
                            <span class="badge badge-light">Diferido</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $acto->fecha_aprobacion ? \Carbon\Carbon::parse($acto->fecha_aprobacion)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-center">
                        {{ $acto->fecha_sancion ? \Carbon\Carbon::parse($acto->fecha_sancion)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #6c757d;">
                        No se encontraron actos legislativos registrados con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>