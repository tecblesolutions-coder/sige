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

class Create extends Component
{
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
                Rule::unique('employees', 'document_number'),
            ],
            'address' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('employees', 'email'),
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

    public function storeEmployee()
    {
        $this->validate();
        $positionName = optional(Position::find($this->positionId))->name;

        Employee::create([
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

        session()->flash('employee-message', 'Empleado creado con éxito.');

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

        return view('livewire.employee.create', [
            'departments' => $departments,
            'countries' => $countries,
            'states' => $this->statesList,
            'cities' => $this->citiesList,
            'positions' => $positions,
        ])->layout('layouts.main');
    }
}
