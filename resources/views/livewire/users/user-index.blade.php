<div class="space-y-4">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Usuarios</h1>
            <p class="text-sm text-gray-500">Gesti?n de cuentas y roles.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <input type="search" wire:model="search" placeholder="Buscar"
                class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            <button wire:click="showUserModal"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-500 w-full sm:w-auto justify-center">
                Nuevo usuario
            </button>
        </div>
    </div>

    @if (session()->has('user-message'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('user-message') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" wire:loading.remove>
                <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Correo</th>
                        <th class="px-4 py-3 hidden md:table-cell">Rol</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                    @forelse ($users as $key => $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-200">{{ $users->firstItem() + $key }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden md:table-cell">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="px-4 py-3 text-right space-x-2 flex flex-wrap justify-end gap-2">
                                <button wire:click="showEditModal({{ $user->id }})"
                                    class="px-3 py-1 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-semibold w-full sm:w-auto">
                                    Editar
                                </button>
                                <button wire:click="deleteUser({{ $user->id }})"
                                    class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-xs font-semibold w-full sm:w-auto">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        <tr class="sm:hidden text-xs text-gray-600 dark:text-gray-300">
                            <td colspan="5" class="px-4 pb-4">
                                <div class="flex flex-wrap gap-3">
                                    <span class="flex items-center gap-1"><span class="font-semibold">Correo:</span> {{ $user->email }}</span>
                                    <span class="flex items-center gap-1"><span class="font-semibold">Roles:</span> {{ $user->roles->pluck('name')->join(', ') }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">Sin resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 px-4" id="userModal">
        <div class="w-full max-w-xl bg-white rounded-xl shadow-xl dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $editMode ? 'Editar usuario' : 'Crear usuario' }}
                </h3>
                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" wire:click="closeModal" aria-label="Cerrar">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                        <input type="text" wire:model.defer="name"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo</label>
                        <input type="email" wire:model.defer="email"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contrase?a @if(!$editMode)<span class="text-xs text-gray-500">(se genera)</span>@endif</label>
                        <input type="password" wire:model.defer="password"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100" @if($editMode) placeholder="Opcional" @endif>
                        @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Roles</label>
                        <select wire:model="selectedRole" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                            <option value="">Selecciona</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <button class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                    wire:click="closeModal">Cerrar</button>
                @if ($editMode)
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500"
                        wire:click="updateUser">Actualizar</button>
                @else
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500"
                        wire:click="storeUser">Guardar</button>
                @endif
            </div>
        </div>
    </div>
</div>
