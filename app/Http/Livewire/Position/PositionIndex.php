<?php

namespace App\Http\Livewire\Position;

use App\Models\Position;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PositionIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $name;

    public $positionId;

    public $editMode = false;

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('positions', 'name')->ignore($this->positionId),
            ],
        ];
    }

    public function showPositionModal(): void
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#positionModal', 'actionModal' => 'show']);
    }

    public function showEditModal(int $id): void
    {
        $this->reset();
        $this->positionId = $id;
        $this->loadPosition();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#positionModal', 'actionModal' => 'show']);
    }

    private function loadPosition(): void
    {
        $position = Position::find($this->positionId);
        $this->name = optional($position)->name;
    }

    public function closeModal(): void
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#positionModal', 'actionModal' => 'hide']);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function storePosition(): void
    {
        $validated = $this->validate();
        Position::create([
            'name' => $validated['name'],
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#positionModal', 'actionModal' => 'hide']);
        session()->flash('position-message', 'Posición creada correctamente');
    }

    public function updatePosition(): void
    {
        $validated = $this->validate();
        $position = Position::find($this->positionId);
        $position?->update([
            'name' => $validated['name'],
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#positionModal', 'actionModal' => 'hide']);
        session()->flash('position-message', 'Posición actualizada correctamente');
    }

    public function deletePosition(int $id): void
    {
        $position = Position::withCount('employees')->find($id);
        if (! $position) {
            return;
        }

        if ($position->employees_count > 0) {
            session()->flash('position-error', 'No se puede eliminar, tiene empleados asignados.');

            return;
        }

        $position->delete();
        session()->flash('position-message', 'Posición eliminada correctamente');
    }

    public function render()
    {
        $positions = Position::query()
            ->when(strlen($this->search) > 2, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.position.position-index', [
            'positions' => $positions,
        ])->layout('layouts.main');
    }
}
