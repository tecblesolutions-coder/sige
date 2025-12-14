<?php

namespace App\Http\Livewire\Reports;

use App\Exports\AttendanceExport;
use App\Exports\DepartmentsExport;
use App\Exports\EmployeesExport;
use App\Exports\PositionsExport;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReportsIndex extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $tab = 'empleados';

    public string $search = '';

    public string $departmentId = '';

    public string $status = '';

    public string $jobTitle = '';

    public string $attendanceUserId = '';

    public ?string $attendanceRangeStart = null;

    public ?string $attendanceRangeEnd = null;

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        if (Gate::denies('ver reportes')) {
            abort(403);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDepartmentId(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingJobTitle(): void
    {
        $this->resetPage();
    }

    public function updatingAttendanceUserId(): void
    {
        $this->resetPage();
    }

    public function updatingAttendanceRangeStart(): void
    {
        $this->resetPage();
    }

    public function updatingAttendanceRangeEnd(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function exportEmployees(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorize('exportar reportes');

        $rows = $this->employeesQuery()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Nombre', 'Correo', 'Departamento', 'Cargo', 'Estado', 'Fecha ingreso']);
            foreach ($rows as $employee) {
                fputcsv($out, [
                    $employee->id,
                    trim($employee->first_name.' '.$employee->last_name),
                    $employee->email,
                    $employee->department->name ?? 'N/D',
                    $employee->job_title,
                    ucfirst($employee->status),
                    optional($employee->date_hired)->format('Y-m-d'),
                ]);
            }
            fclose($out);
        }, 'reporte-empleados.csv');
    }

    public function exportEmployeesXlsx()
    {
        $this->authorize('exportar reportes');

        return Excel::download(new EmployeesExport($this->employeesQuery()->get()), 'reporte-empleados.xlsx');
    }

    public function exportEmployeesPdf()
    {
        $this->authorize('exportar reportes');

        $rows = $this->employeesQuery()->get();

        return response()->streamDownload(function () use ($rows) {
            echo Pdf::loadView('reports.pdf-employees', ['rows' => $rows])
                ->setPaper('a4', 'portrait')
                ->output();
        }, 'reporte-empleados.pdf');
    }

    public function exportAttendance(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorize('exportar reportes');

        $rows = $this->attendanceQuery()->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Fecha', 'Usuario', 'Entrada', 'Salida', 'Estado']);
            foreach ($rows as $attendance) {
                fputcsv($out, [
                    optional($attendance->date)->format('Y-m-d'),
                    $attendance->user->username ?? 'N/D',
                    optional($attendance->time_in)->format('H:i'),
                    optional($attendance->time_out)->format('H:i'),
                    $attendance->status,
                ]);
            }
            fclose($out);
        }, 'reporte-asistencias.csv');
    }

    public function exportAttendanceXlsx()
    {
        $this->authorize('exportar reportes');

        return Excel::download(new AttendanceExport($this->attendanceQuery()->get()), 'reporte-asistencias.xlsx');
    }

    public function exportAttendancePdf()
    {
        $this->authorize('exportar reportes');

        $rows = $this->attendanceQuery()->get();

        return response()->streamDownload(function () use ($rows) {
            echo Pdf::loadView('reports.pdf-attendance', ['rows' => $rows])
                ->setPaper('a4', 'portrait')
                ->output();
        }, 'reporte-asistencias.pdf');
    }

    public function exportDepartmentsCsv()
    {
        $this->authorize('exportar reportes');

        $rows = Department::orderBy('name')->get(['id', 'name']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Nombre']);
            foreach ($rows as $department) {
                fputcsv($out, [$department->id, $department->name]);
            }
            fclose($out);
        }, 'departamentos.csv');
    }

    public function exportDepartmentsXlsx()
    {
        $this->authorize('exportar reportes');

        return Excel::download(new DepartmentsExport, 'departamentos.xlsx');
    }

    public function exportPositionsCsv()
    {
        $this->authorize('exportar reportes');

        $rows = Position::orderBy('name')->get(['id', 'name']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Nombre']);
            foreach ($rows as $position) {
                fputcsv($out, [$position->id, $position->name]);
            }
            fclose($out);
        }, 'posiciones.csv');
    }

    public function exportPositionsXlsx()
    {
        $this->authorize('exportar reportes');

        return Excel::download(new PositionsExport, 'posiciones.xlsx');
    }

    public function render()
    {
        $departments = Department::orderBy('name')->get();
        $users = User::orderBy('username')->get();

        return view('livewire.reports.reports-index', [
            'departments' => $departments,
            'employees' => $this->employeesQuery()->paginate(10),
            'attendanceRecords' => $this->attendanceQuery()->paginate(10),
            'users' => $users,
        ])->layout('layouts.main');
    }

    private function employeesQuery()
    {
        return Employee::with(['department', 'position'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('document_number', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->departmentId, fn ($q) => $q->where('department_id', $this->departmentId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->jobTitle, fn ($q) => $q->where('job_title', 'like', '%'.$this->jobTitle.'%'))
            ->orderBy('last_name')
            ->orderBy('first_name');
    }

    private function attendanceQuery()
    {
        return Attendance::with('user')
            ->when($this->attendanceUserId, fn ($q) => $q->where('user_id', $this->attendanceUserId))
            ->when($this->attendanceRangeStart, fn ($q) => $q->whereDate('date', '>=', $this->attendanceRangeStart))
            ->when($this->attendanceRangeEnd, fn ($q) => $q->whereDate('date', '<=', $this->attendanceRangeEnd))
            ->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc');
    }
}
