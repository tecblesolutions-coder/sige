<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $username;

    public $firstName;

    public $lastName;

    public $email;

    public $password;

    public $userId;

    public $editMode = false;

    public $selectedRole;

    protected $rules = [
        'username' => 'required|unique:users,username',
        'firstName' => 'required',
        'lastName' => 'required',
        'password' => 'required|min:8',
        'email' => 'required|email|unique:users,email',
    ];

    public function storeUser()
    {
        $this->validate();

        $user = User::create([
            'username' => $this->username,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        if ($this->selectedRole) {
            $user->syncRoles([$this->selectedRole]);
        }
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
        session()->flash('user-message', 'User successfully created');
    }

    public function showUserModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'show']);
    }

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        // find user
        $this->userId = $id;
        // load user
        $this->loadUser();
        // show Modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'show']);
    }

    public function loadUser()
    {
        $user = User::find($this->userId);
        $this->username = $user->username;
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles()->pluck('name')->first();
    }

    public function updateUser()
    {
        $validated = $this->validate([
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore($this->userId),
            ],
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
            'password' => 'nullable|min:8',
        ]);
        $user = User::find($this->userId);
        $data = [
            'username' => $validated['username'],
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        if ($this->selectedRole) {
            $user->syncRoles([$this->selectedRole]);
        }
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
        session()->flash('user-message', 'User successfully updated');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();

        session()->flash('user-message', 'User successfully deleted');
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
        $this->reset();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::paginate(5);
        if (strlen($this->search) > 2) {
            $users = User::where('username', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.users.user-index', [
            'users' => $users,
            'roles' => Role::all(),
        ])
            ->layout('layouts.main');
    }
}
