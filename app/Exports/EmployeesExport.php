<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, Responsable, WithHeadings, WithMapping
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
        return ['ID', 'Nombre', 'Correo', 'Departamento', 'Cargo', 'Estado', 'Fecha ingreso'];
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            trim($employee->first_name.' '.$employee->last_name),
            $employee->email,
            $employee->department->name ?? 'N/D',
            $employee->position->name ?? $employee->job_title,
            ucfirst($employee->status),
            optional($employee->date_hired)->format('Y-m-d'),
        ];
    }
}
