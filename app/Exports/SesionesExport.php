<?php

namespace App\Exports;

use App\Models\Sesiones;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SesionesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithDrawings, WithStyles, WithCustomStartCell
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Sesiones::with('diarioDebates');

        if (!empty($this->request['naturaleza'])) {
            $query->where('naturaleza', 'like', '%' . $this->request['naturaleza'] . '%');
        }

        if (!empty($this->request['fecha'])) {
            $query->whereDate('fecha', $this->request['fecha']);
        }

        if (!empty($this->request['sumario_cuenta'])) {
            $query->whereHas('diarioDebates', function ($q) {
                $q->where('sumario_cuenta', 'like', '%' . $this->request['sumario_cuenta'] . '%');
            });
        }

        if (!empty($this->request['sumario_orden_dia'])) {
            $query->whereHas('diarioDebates', function ($q) {
                $q->where('sumario_orden_dia', 'like', '%' . $this->request['sumario_orden_dia'] . '%');
            });
        }

        return $query->latest();
    }

    // Define en qué celda inicia la tabla de datos (dejamos espacio para el logo)
    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Naturaleza',
            'Fecha Sesión',
            'Sumarios de Cuenta',
            'Sumarios Orden del Día',
        ];
    }

    public function map($sesion): array
    {
        // Nota: Corregida la propiedad a 'diarioDebates' como la tienes en query()
        $diarios = $sesion->diarioDebates ?? collect();
        
        $sumariosCuenta = $diarios->pluck('sumario_cuenta')->filter()->implode("\n• ");
        $sumariosOrden = $diarios->pluck('sumario_orden_dia')->filter()->implode("\n• ");

        return [
            $sesion->id,
            $sesion->naturaleza,
            \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y'),
            $sumariosCuenta ? '• ' . $sumariosCuenta : 'Sin datos',
            $sumariosOrden ? '• ' . $sumariosOrden : 'Sin datos',
        ];
    }

    // Agrega la imagen del Logo
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Institucional');
        $drawing->setPath(public_path('images/logo.png')); // Asegúrate de colocar tu logo en public/images/logo.png
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    // Aplica estilos, colores y fuentes a las celdas
    public function styles(Worksheet $sheet)
    {
        // Título del reporte al lado del logo
        $sheet->setCellValue('C2', 'REPORTE - DIARIO DE DEBATES');
        $sheet->setCellValue('C3', 'Historial de Sesiones y Sumarios');
        
        $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0D6EFD'));
        $sheet->getStyle('C3')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('6C757D'));

        // Obtener el número de la última fila con datos
        $highestRow = $sheet->getHighestRow();

        // 1. Estilos para los encabezados de la tabla (Fila 6)
        $sheet->getStyle('A6:E6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'], // Color azul/gris oscuro elegante
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 2. Estilos para el cuerpo de la tabla (A partir de la fila 7)
        if ($highestRow >= 7) {
            // Activar salto de línea en columnas largas
            $sheet->getStyle('D7:E' . $highestRow)->getAlignment()->setWrapText(true);

            // Colores intercalados suaves por columna/fila y bordes ligeros
            $sheet->getStyle('A7:E' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ]);

            // Formato centrado para ID y Fecha
            $sheet->getStyle('A7:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C7:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Fondo leve para destacar columnas clave (A y B)
            $sheet->getStyle('A7:B' . $highestRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
        }

        return [];
    }
}