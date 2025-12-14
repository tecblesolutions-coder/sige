<?php

namespace App\Http\Livewire\City;

use App\Models\City;
use App\Models\Employee;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CityIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $stateId;

    public $name;

    public $editMode = false;

    public $cityId;

    protected function rules(): array
    {
        return [
            'stateId' => [
                'required',
                'integer',
                Rule::exists('states', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:150',
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
        $this->cityId = $id;
        $this->loadCities();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'show']);
    }

    public function loadCities()
    {
        $city = City::find($this->cityId);
        $this->stateId = $city->state_id;
        $this->name = $city->name;
    }

    public function deleteCity($id)
    {
        $employeesWithCity = Employee::where('city_id', $id)->count();

        if ($employeesWithCity > 0) {
            session()->flash('city-error', 'No se puede eliminar, hay empleados asociados.');

            return;
        }

        $city = City::find($id);
        $city?->delete();
        session()->flash('city-message', 'City successfully deleted');
    }

    public function showCityModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
    }

    public function storeCity()
    {
        $validated = $this->validate();
        City::create([
            'state_id' => $validated['stateId'],
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
        session()->flash('city-message', 'City successfully created');
    }

    public function updateCity()
    {
        $validated = $this->validate();
        $city = City::find($this->cityId);
        $city->update([
            'state_id' => $validated['stateId'],
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
        session()->flash('city-message', 'City successfully updated');
    }

    public function render()
    {
        $cities = City::paginate(5);
        if (strlen($this->search) > 2) {
            $cities = City::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.city.city-index', [
            'cities' => $cities,
        ])->layout('layouts.main');
    }
}
