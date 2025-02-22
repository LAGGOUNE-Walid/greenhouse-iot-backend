<?php

namespace App\Exports;

use App\Models\Measurement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MeasurementExport implements FromQuery, WithMapping, WithHeadings
{

    public function headings(): array
    {
        return [
            'datetime',
            '#',
            'node',
            'value',
            'type',
        ];
    }

    public function map($measurement): array
    {
        return [
            $measurement->created_at->format("Y-m-d H:i:s"),
            $measurement->id,
            $measurement->node_id,
            $measurement->value,
            $measurement->measurement_type->name,            
        ];
    }

    public function query()
    {
        return Measurement::orderByDesc('created_at');
    }
}
