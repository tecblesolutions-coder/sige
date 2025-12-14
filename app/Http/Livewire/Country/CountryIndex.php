<?php

namespace App\Http\Livewire\Country;

use App\Models\Country;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CountryIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $countryCode;

    public $name;

    public $editMode = false;

    public $countryId;

    protected function rules(): array
    {
        return [
            'countryCode' => [
                'required',
                'string',
                'max:5',
                Rule::unique('countries', 'country_code')->ignore($this->countryId),
            ],
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('countries', 'name')->ignore($this->countryId),
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
        $this->countryId = $id;
        $this->loadCountries();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'show']);
    }

    public function loadCountries()
    {
        $country = Country::find($this->countryId);
        $this->countryCode = $country->country_code;
        $this->name = $country->name;
    }

    public function deleteCountry($id)
    {
        $country = Country::withCount('states')->find($id);
        $employeesWithCountry = Employee::where('country_id', $id)->count();

        if ($employeesWithCountry > 0) {
            session()->flash('country-error', 'No se puede eliminar, hay empleados asociados.');

            return;
        }

        $country?->delete();
        session()->flash('country-message', 'Country successfully deleted');
    }

    public function showCountryModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
    }

    public function storeCountry()
    {
        $validated = $this->validate();
        Country::create([
            'country_code' => $validated['countryCode'],
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
        session()->flash('country-message', 'Country successfully created');
    }

    public function updateCountry()
    {
        $validated = $this->validate();
        $country = Country::find($this->countryId);
        $country->update([
            'country_code' => $validated['countryCode'],
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
        session()->flash('country-message', 'Country successfully updated');
    }

    public function render()
    {
        $countries = Country::paginate(5);
        if (strlen($this->search) > 2) {
            $countries = Country::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.country.country-index', [
            'countries' => $countries,
        ])->layout('layouts.main');
    }
}
