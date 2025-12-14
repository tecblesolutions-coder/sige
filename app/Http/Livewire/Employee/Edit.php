<?php

namespace App\Http\Livewire\Employee;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\State;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Employee $employee;

    public $lastName;

    public $firstName;

    public $middleName;

    public $address;

    public $email;

    public $phone;

    public $jobTitle;

    public $documentType;

    public $documentNumber;

    public $status = 'activo';

    public $countryId;

    public $stateId;

    public $cityId;

    public $departmentId;

    public $positionId;

    public $zipCode;

    public $birthDate;

    public $dateHired;

    public $statesList = [];

    public $citiesList = [];

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->lastName = $employee->last_name;
        $this->firstName = $employee->first_name;
        $this->middleName = $employee->middle_name;
        $this->address = $employee->address;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->jobTitle = $employee->job_title;
        $this->documentType = $employee->document_type;
        $this->documentNumber = $employee->document_number;
        $this->status = $employee->status;
        $this->countryId = $employee->country_id;
        $this->stateId = $employee->state_id;
        $this->cityId = $employee->city_id;
        $this->departmentId = $employee->department_id;
        $this->positionId = $employee->position_id;
        $this->zipCode = $employee->zip_code;
        $this->birthDate = $employee->birthdate ? $employee->birthdate->format('Y-m-d') : null;
        $this->dateHired = $employee->date_hired ? $employee->date_hired->format('Y-m-d') : null;

        // Pre-load dependent dropdowns
        if ($this->countryId) {
            $this->statesList = State::where('country_id', $this->countryId)->get();
        }
        if ($this->stateId) {
            $this->citiesList = City::where('state_id', $this->stateId)->get();
        }
    }

    protected function rules(): array
    {
        return [
            'lastName' => 'required|string|max:150',
            'firstName' => 'required|string|max:150',
            'middleName' => 'nullable|string|max:150',
            'documentType' => 'nullable|string|max:50',
            'documentNumber' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('employees', 'document_number')->ignore($this->employee->id),
            ],
            'address' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('employees', 'email')->ignore($this->employee->id),
            ],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\\-\s()]{7,20}$/'],
            'jobTitle' => 'nullable|string|max:120',
            'positionId' => 'required|integer|exists:positions,id',
            'status' => 'required|in:activo,inactivo',
            'countryId' => 'required|integer|exists:countries,id',
            'stateId' => 'required|integer|exists:states,id',
            'cityId' => 'required|integer|exists:cities,id',
            'departmentId' => 'required|integer|exists:departments,id',
            'zipCode' => 'required|string|max:20',
            'birthDate' => 'nullable|date',
            'dateHired' => 'nullable|date',
        ];
    }

    public function updateEmployee()
    {
        $this->validate();
        $positionName = optional(Position::find($this->positionId))->name;

        $this->employee->update([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'document_type' => $this->documentType,
            'document_number' => $this->documentNumber,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'job_title' => $this->jobTitle ?: $positionName,
            'status' => $this->status,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'department_id' => $this->departmentId,
            'position_id' => $this->positionId,
            'zip_code' => $this->zipCode,
            'birthdate' => $this->birthDate,
            'date_hired' => $this->dateHired,
        ]);

        session()->flash('employee-message', 'Empleado actualizado con éxito.');

        return redirect()->route('employees.index');
    }

    public function updatedCountryId($value)
    {
        $this->stateId = null;
        $this->cityId = null;
        $this->statesList = State::where('country_id', $value)->get();
        $this->citiesList = collect();
    }

    public function updatedStateId($value)
    {
        $this->cityId = null;
        $this->citiesList = City::where('state_id', $value)->get();
    }

    public function render()
    {
        $departments = Department::all();
        $countries = Country::all();
        $positions = Position::orderBy('name')->get();

        return view('livewire.employee.edit', [
            'departments' => $departments,
            'countries' => $countries,
            'states' => $this->statesList,
            'cities' => $this->citiesList,
            'positions' => $positions,
        ])->layout('layouts.main');
    }
}
