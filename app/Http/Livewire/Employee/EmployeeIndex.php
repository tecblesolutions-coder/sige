<?php

namespace App\Http\Livewire\Employee;

use App\Models\Department;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $selectedDepartmentId = null;

    public $selectedStatus = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
        session()->flash('employee-message', 'Empleado eliminado con éxito.');
    }

    public function clearFilters()
    {
        $this->reset(['search', 'selectedDepartmentId', 'selectedStatus']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Employee::query()
            ->with(['department', 'position']);

        if (strlen($this->search) > 2) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('document_number', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedDepartmentId) {
            $query->where('department_id', $this->selectedDepartmentId);
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        $employees = $query->latest()->paginate(10);
        $departments = Department::orderBy('name')->get();

        return view('livewire.employee.employee-index', [
            'employees' => $employees,
            'departments' => $departments,
        ])->layout('layouts.main');
    }
}
