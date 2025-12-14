<?php

namespace App\Http\Livewire\State;

use App\Models\Employee;
use App\Models\State;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class StateIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $countryId;

    public $name;

    public $editMode = false;

    public $stateId;

    protected function rules(): array
    {
        return [
            'countryId' => [
                'required',
                'integer',
                Rule::exists('countries', 'id'),
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
        $this->stateId = $id;
        $this->loadStates();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'show']);
    }

    public function loadStates()
    {
        $state = State::find($this->stateId);
        $this->countryId = $state->country_id;
        $this->name = $state->name;
    }

    public function deleteState($id)
    {
        $employeesWithState = Employee::where('state_id', $id)->count();

        if ($employeesWithState > 0) {
            session()->flash('state-error', 'No se puede eliminar, hay empleados asociados.');

            return;
        }

        $state = State::find($id);
        $state?->delete();
        session()->flash('state-message', 'State successfully deleted');
    }

    public function showStateModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
    }

    public function storeState()
    {
        $validated = $this->validate();
        State::create([
            'country_id' => $validated['countryId'],
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully created');
    }

    public function updateState()
    {
        $validated = $this->validate();
        $state = State::find($this->stateId);
        $state->update([
            'country_id' => $validated['countryId'],
            'name' => $validated['name'],
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully updated');
    }

    public function render()
    {
        $states = State::paginate(5);
        if (strlen($this->search) > 2) {
            $states = State::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.state.state-index', [
            'states' => $states,
        ])->layout('layouts.main');
    }
}
