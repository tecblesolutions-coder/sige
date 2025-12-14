<div class="space-y-4">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Empleados</h1>
            <p class="text-sm text-gray-500">Gestiona el personal y sus datos.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <input type="search" wire:model="search" placeholder="Buscar"
                class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            <select wire:model="selectedDepartmentId"
                class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                <option value="">Departamento</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            <select wire:model="selectedStatus"
                class="w-full sm:w-40 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                <option value="">Estado</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>

            @if ($search || $selectedDepartmentId || $selectedStatus)
                <button wire:click="clearFilters"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-300 w-full sm:w-auto justify-center dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Limpiar Filtros
                </button>
            @endif

            <div wire:loading>
                <span class="text-sm text-gray-500">Cargando...</span>
            </div>
            <a href="{{ route('employees.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-500 w-full sm:w-auto justify-center">
                <x-heroicon-o-plus class="h-4 w-4" />
                <span>Nuevo empleado</span>
            </a>
        </div>
    </div>

    @if (session()->has('employee-message'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('employee-message') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" wire:loading.remove>
                <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Departamento</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Cargo</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Fecha contratacion</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                            <td class="px-4 py-3 text-gray-600">{{ $employee->id }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $employee->first_name }}</td>
                            <td class="px-4 py-3 text-gray-700 hidden sm:table-cell">{{ $employee->department->name }}</td>
                            <td class="px-4 py-3 text-gray-700 hidden sm:table-cell">{{ $employee->position->name ?? $employee->job_title }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $employee->status === 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 hidden sm:table-cell">{{ optional($employee->date_hired)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right space-x-2 flex flex-wrap justify-end gap-2">
                                <a href="{{ route('employees.edit', $employee) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-semibold w-full sm:w-auto text-center">
                                    <x-heroicon-o-pencil class="h-3 w-3" />
                                    <span>Editar</span>
                                </a>
                                <button wire:click="deleteEmployee({{ $employee->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-xs font-semibold w-full sm:w-auto text-center">
                                    <x-heroicon-o-trash class="h-3 w-3" />
                                    <span>Eliminar</span>
                                </button>
                            </td>
                        </tr>
                        <tr class="sm:hidden text-xs text-gray-600 dark:text-gray-300">
                            <td colspan="7" class="px-4 pb-4">
                                <div class="flex flex-wrap gap-3">
                                    <span class="flex items-center gap-1"><span class="font-semibold">Depto:</span> {{ $employee->department->name }}</span>
                                    <span class="flex items-center gap-1"><span class="font-semibold">Cargo:</span> {{ $employee->position->name ?? $employee->job_title }}</span>
                                    <span class="flex items-center gap-1"><span class="font-semibold">Ingreso:</span> {{ optional($employee->date_hired)->format('d/m/Y') }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Sin resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            <div class="overflow-x-auto">
                {{ $employees->links() }}
            </div>
        </div>
    </div>

</div>
