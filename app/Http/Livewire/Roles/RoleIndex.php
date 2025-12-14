<?php

namespace App\Http\Livewire\Roles;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    use WithPagination;

    public $name;

    public $roleId;

    public $editMode = false;

    public $selectedPermissions = [];

    public $search = '';

    public $selectedUserId;

    public $selectedUserRole;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:100',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showRoleModal(): void
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#roleModal', 'actionModal' => 'show']);
    }

    public function showEditModal(int $id): void
    {
        $this->resetInput();
        $this->roleId = $id;
        $this->loadRole();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#roleModal', 'actionModal' => 'show']);
    }

    public function closeModal(): void
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#roleModal', 'actionModal' => 'hide']);
    }

    public function storeRole(): void
    {
        $this->validate();
        $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
        $role->syncPermissions($this->selectedPermissions);
        $this->closeModal();
        session()->flash('role-message', 'Rol creado correctamente.');
    }

    public function updateRole(): void
    {
        $this->validate();
        $role = Role::findOrFail($this->roleId);
        $role->name = $this->name;
        $role->save();
        $role->syncPermissions($this->selectedPermissions);
        $this->closeModal();
        session()->flash('role-message', 'Rol actualizado correctamente.');
    }

    public function deleteRole(int $id): void
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'Admin') {
            session()->flash('role-message', 'El rol Admin no puede eliminarse.');

            return;
        }
        $role->delete();
        session()->flash('role-message', 'Rol eliminado.');
    }

    public function assignRoleToUser(): void
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedUserRole' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $user->syncRoles([$this->selectedUserRole]);
        session()->flash('role-message', 'Rol asignado al usuario.');
    }

    public function render()
    {
        $roles = Role::query()
            ->when(strlen($this->search) > 2, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->with('permissions')
            ->paginate(5);

        $permissions = Permission::all();
        $users = User::select('id', 'username')->orderBy('username')->get();

        return view('livewire.roles.role-index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'users' => $users,
        ])->layout('layouts.main');
    }

    private function loadRole(): void
    {
        $role = Role::with('permissions')->findOrFail($this->roleId);
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    private function resetInput(): void
    {
        $this->reset(['name', 'roleId', 'selectedPermissions', 'editMode']);
    }
}
