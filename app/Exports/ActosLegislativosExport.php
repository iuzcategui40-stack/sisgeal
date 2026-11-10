<?php

namespace App\Exports;

use App\Models\ActosLegislativos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class ActosLegislativosExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithDrawings, 
    WithStyles,
    WithCustomStartCell,
    WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Movemos la tabla a la fila 7 para dejar espacio arriba (Logo y Títulos)
     */
    public function startCell(): string
    {
        return 'A7';
    }

    /**
     * Insertar el Logo Institucional (Se mantiene en A1)
     */
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Institucional');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('images/logo.png')); // Ajusta si tu ruta es diferente
        $drawing->setHeight(75); 
        $drawing->setCoordinates('A1');
        return $drawing;
    }

    /**
     * Agregar los Títulos de forma manual usando Eventos
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Título Principal (Celda C2)
                $sheet->setCellValue('C2', 'SISTEMA DE GESTIÓN DE ACTOS LEGISLATIVOS');
                $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14);
                
                // Subtítulo o nombre del Reporte (Celda C3)
                $sheet->setCellValue('C3', 'Reporte Consolidado de Actos Legislativos');
                $sheet->getStyle('C3')->getFont()->setItalic(true)->setSize(11)->getColor()->setARGB('555555');

                // Fecha de Generación (Celda C4)
                $sheet->setCellValue('C4', 'Fecha de emisión: ' . now()->format('d/m/Y h:i A'));
                $sheet->getStyle('C4')->getFont()->setSize(9)->getColor()->setARGB('777777');
            },
        ];
    }

    /**
     * Estilos para los encabezados de la tabla (Ahora en la Fila 7)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilos para la fila 7 (Nuestros encabezados)
            7 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '0056B3'], // Azul Institucional
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Consulta filtrada (Permanece igual)
     */
    public function query()
    {
        $query = ActosLegislativos::with('tipoacto');

        if ($this->request->filled('buscar')) {
            $buscar = $this->request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                  ->orWhere('codigo', 'LIKE', "%{$buscar}%")
                  ->orWhere('resumen', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            });
        }

        if ($this->request->filled('tipo_acto')) {
            $query->where('tipoacto_id', $this->request->input('tipo_acto'));
        }

        if ($this->request->filled('estatus')) {
            $query->where('estatus', $this->request->input('estatus'));
        }

        if ($this->request->filled('fecha_iniciativa')) {
            $query->whereDate('fecha_iniciativa', $this->request->input('fecha_iniciativa'));
        }

        if ($this->request->filled('fecha_aprobacion')) {
            $query->whereDate('fecha_aprobacion', $this->request->input('fecha_aprobacion'));
        }

        return $query->latest();
    }

    /**
     * Encabezados de la tabla
     */
    public function headings(): array
    {
        return [
            'ID', 'Código', 'Título', 'Tipo de Acto', 'Estatus', 
            'Fecha Iniciativa', 'Fecha Aprobación', 'Gaceta Oficial', 
            'Fecha Gaceta', 'Enviado', 'Fecha Registro'
        ];
    }

    /**
     * Mapeo de columnas
     */
    public function map($acto): array
    {
        return [
            $acto->id,
            $acto->codigo,
            $acto->titulo,
            $acto->tipoacto->descripcion ?? 'N/A',
            $acto->estatus,
            $acto->fecha_iniciativa ? \Carbon\Carbon::parse($acto->fecha_iniciativa)->format('d/m/Y') : 'N/A',
            $acto->fecha_aprobacion ? \Carbon\Carbon::parse($acto->fecha_aprobacion)->format('d/m/Y') : 'N/A',
            $acto->gaceta_oficial ?? 'N/A',
            $acto->fecha_gaceta_oficial ? \Carbon\Carbon::parse($acto->fecha_gaceta_oficial)->format('d/m/Y') : 'N/A',
            $acto->enviado,
            $acto->created_at ? $acto->created_at->format('d/m/Y H:i') : 'N/A',
        ];
    }
}