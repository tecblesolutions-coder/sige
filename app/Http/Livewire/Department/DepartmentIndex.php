<?php

namespace App\Http\Livewire\Department;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $name;

    public $editMode = false;

    public $departmentId;

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('departments', 'name')->ignore($this->departmentId),
            ],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function showEditModal($id)
    {
        $this->reset();
        $this->departmentId = $id;
        $this->loadDepartment();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'show']);
    }

    public function loadDepartment()
    {
        $department = Department::find($this->departmentId);
        $this->name = $department->name;
    }

    public function deleteDepartment($id)
    {
        $employeesWithDepartment = Employee::where('department_id', $id)->count();

        if ($employeesWithDepartment > 0) {
            session()->flash('department-error', 'No se puede eliminar, hay empleados asociados.');

            return;
        }

        $department = Department::find($id);
        $department?->delete();
        session()->flash('department-message', 'Department successfully deleted');
    }

    public function showDepartmentModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
    }

    public function storeDepartment()
    {
        $validated = $this->validate();
        Department::create([
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully created');
    }

    public function updateDepartment()
    {
        $validated = $this->validate();
        $department = Department::find($this->departmentId);
        $department->update([
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully updated');
    }

    public function render()
    {
        $departments = Department::paginate(5);
        if (strlen($this->search) > 2) {
            $departments = Department::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.department.department-index', [
            'departments' => $departments,
        ])->layout('layouts.main');
    }
}
