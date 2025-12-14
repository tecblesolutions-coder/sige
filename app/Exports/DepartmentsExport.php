<?php

namespace App\Exports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentsExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Department::orderBy('name')
            ->get(['id', 'name', 'created_at']);
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Creado en'];
    }
}
