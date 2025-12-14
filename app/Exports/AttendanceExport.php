<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, Responsable, WithHeadings, WithMapping
{
    use Exportable;

    private Collection $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Fecha', 'Usuario', 'Entrada', 'Salida', 'Estado'];
    }

    public function map($attendance): array
    {
        return [
            optional($attendance->date)->format('Y-m-d'),
            $attendance->user->username ?? 'N/D',
            optional($attendance->time_in)->format('H:i'),
            optional($attendance->time_out)->format('H:i'),
            $attendance->status,
        ];
    }
}
