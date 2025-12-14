<div class="space-y-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Roles y permisos</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Gestiona roles, permisos y asignaciones.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <input type="search" wire:model="search" placeholder="Buscar rol"
                class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            <button wire:click="showRoleModal"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400 w-full sm:w-auto justify-center">
                Nuevo rol
            </button>
        </div>
    </div>

    @if (session()->has('role-message'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-100">
            {{ session('role-message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" wire:loading.remove>
                    <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Rol</th>
                            <th class="px-4 py-3 hidden md:table-cell">Permisos</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                        @forelse ($roles as $key => $role)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-200">{{ $roles->firstItem() + $key }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden md:table-cell">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($role->permissions as $permission)
                                            <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs dark:bg-gray-700 dark:text-gray-100">{{ $permission->name }}</span>
                                        @empty
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Sin permisos</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button wire:click="showEditModal({{ $role->id }})"
                                        class="px-3 py-1 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-semibold dark:bg-amber-900/30 dark:text-amber-100 dark:hover:bg-amber-800/40 w-full sm:w-auto">
                                        Editar
                                    </button>
                                    <button wire:click="deleteRole({{ $role->id }})"
                                        class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-xs font-semibold dark:bg-red-900/30 dark:text-red-100 dark:hover:bg-red-800/40 w-full sm:w-auto">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">Sin resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 mb-3 dark:text-white">Asignar rol a usuario</h3>
            <form wire:submit.prevent="assignRoleToUser" class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Usuario</label>
                    <select wire:model="selectedUserId"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <option value="">Selecciona usuario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rol</label>
                    <select wire:model="selectedUserRole"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <option value="">Selecciona rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserRole') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end">
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                        type="submit">Asignar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 dark:bg-black/60" id="roleModal" x-data>
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-xl dark:bg-gray-900 dark:border-gray-700">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $editMode ? 'Editar rol' : 'Crear rol' }}
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre del rol</label>
                        <input id="name" type="text" wire:model.defer="name"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">Permisos</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="checkbox" value="{{ $permission->name }}" wire:model.defer="selectedPermissions"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <button class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                    wire:click="closeModal">Cerrar</button>
                @if ($editMode)
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                        wire:click="updateRole">Actualizar rol</button>
                @else
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                        wire:click="storeRole">Guardar rol</button>
                @endif
            </div>
        </div>
    </div>
</div>
