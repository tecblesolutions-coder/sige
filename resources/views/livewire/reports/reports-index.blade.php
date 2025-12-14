<div class="space-y-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Reportes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Genera reportes de empleados y asistencia con filtros y exportación.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-2">
                <button wire:click="setTab('empleados')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 {{ $tab === 'empleados' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    Empleados
                </button>
                <button wire:click="setTab('asistencia')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 {{ $tab === 'asistencia' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    Asistencia
                </button>
            </div>
            @can('exportar reportes')
                @if ($tab === 'empleados')
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        <button wire:click="exportEmployees"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400 w-full sm:w-auto justify-center">
                            Exportar CSV
                        </button>
                        <button wire:click="exportEmployeesXlsx"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500 focus:outline-none focus:ring focus:ring-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400 w-full sm:w-auto justify-center">
                            Exportar XLSX
                        </button>
                        <button wire:click="exportEmployeesPdf"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-700 focus:outline-none focus:ring focus:ring-slate-500 w-full sm:w-auto justify-center">
                            Exportar PDF
                        </button>
                        <div class="hidden lg:block h-6 w-px bg-gray-200 dark:bg-gray-700"></div>
                        <div class="flex flex-wrap items-center gap-2">
                            <button wire:click="exportDepartmentsCsv"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 focus:outline-none focus:ring focus:ring-blue-200 dark:bg-blue-900/30 dark:text-blue-100">
                                Departamentos CSV
                            </button>
                            <button wire:click="exportDepartmentsXlsx"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-100 text-blue-800 text-xs font-semibold hover:bg-blue-200 focus:outline-none focus:ring focus:ring-blue-200 dark:bg-blue-900/40 dark:text-blue-100">
                                Departamentos XLSX
                            </button>
                            <button wire:click="exportPositionsCsv"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-purple-50 text-purple-700 text-xs font-semibold hover:bg-purple-100 focus:outline-none focus:ring focus:ring-purple-200 dark:bg-purple-900/30 dark:text-purple-100">
                                Posiciones CSV
                            </button>
                            <button wire:click="exportPositionsXlsx"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-purple-100 text-purple-800 text-xs font-semibold hover:bg-purple-200 focus:outline-none focus:ring focus:ring-purple-200 dark:bg-purple-900/40 dark:text-purple-100">
                                Posiciones XLSX
                            </button>
                        </div>
                    </div>
                @else
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        <button wire:click="exportAttendance"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400 w-full sm:w-auto justify-center">
                            Exportar CSV
                        </button>
                        <button wire:click="exportAttendanceXlsx"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500 focus:outline-none focus:ring focus:ring-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400 w-full sm:w-auto justify-center">
                            Exportar XLSX
                        </button>
                        <button wire:click="exportAttendancePdf"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-700 focus:outline-none focus:ring focus:ring-slate-500 w-full sm:w-auto justify-center">
                            Exportar PDF
                        </button>
                    </div>
                @endif
            @endcan
        </div>
    </div>

    @if ($tab === 'empleados')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-4 dark:bg-gray-800 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Búsqueda</label>
                    <input type="search" wire:model.debounce.400ms="search" placeholder="Nombre, documento o correo"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Departamento</label>
                    <select wire:model="departmentId"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <option value="">Todos</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Estado</label>
                    <select wire:model="status"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <option value="">Todos</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cargo</label>
                    <input type="text" wire:model.debounce.400ms="jobTitle" placeholder="Cargo"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" wire:loading.remove>
                    <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3 hidden sm:table-cell">Correo</th>
                        <th class="px-4 py-3 hidden md:table-cell">Departamento</th>
                        <th class="px-4 py-3">Cargo</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3 hidden sm:table-cell">Ingreso</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                        @forelse ($employees as $employee)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-semibold">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">{{ $employee->email }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden md:table-cell">{{ $employee->department->name ?? 'N/D' }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $employee->position->name ?? $employee->job_title }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $employee->status === 'activo' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-100' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">
                                    {{ optional($employee->date_hired)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr class="sm:hidden text-xs text-gray-600 dark:text-gray-300">
                                <td colspan="6" class="px-4 pb-4">
                                    <div class="flex flex-wrap gap-3">
                                        <span class="flex items-center gap-1"><span class="font-semibold">Correo:</span> {{ $employee->email }}</span>
                                        <span class="flex items-center gap-1"><span class="font-semibold">Depto:</span> {{ $employee->department->name ?? 'N/D' }}</span>
                                        <span class="flex items-center gap-1"><span class="font-semibold">Cargo:</span> {{ $employee->position->name ?? $employee->job_title }}</span>
                                        <span class="flex items-center gap-1"><span class="font-semibold">Ingreso:</span> {{ optional($employee->date_hired)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">Sin resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-4 dark:bg-gray-800 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Usuario</label>
                    <select wire:model="attendanceUserId"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <option value="">Todos</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Desde</label>
                    <input type="date" wire:model="attendanceRangeStart"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Hasta</label>
                    <input type="date" wire:model="attendanceRangeEnd"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" wire:loading.remove>
                    <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Usuario</th>
                            <th class="px-4 py-3 hidden sm:table-cell">Entrada</th>
                            <th class="px-4 py-3 hidden sm:table-cell">Salida</th>
                            <th class="px-4 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                        @forelse ($attendanceRecords as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ optional($attendance->date)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $attendance->user->username ?? 'N/D' }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">{{ optional($attendance->time_in)->format('H:i') }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">{{ optional($attendance->time_out)->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $attendance->status === 'abierta' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-100' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="sm:hidden text-xs text-gray-600 dark:text-gray-300">
                                <td colspan="5" class="px-4 pb-4">
                                    <div class="flex flex-wrap gap-3">
                                        <span class="flex items-center gap-1"><span class="font-semibold">Entrada:</span> {{ optional($attendance->time_in)->format('H:i') }}</span>
                                        <span class="flex items-center gap-1"><span class="font-semibold">Salida:</span> {{ optional($attendance->time_out)->format('H:i') }}</span>
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
                    {{ $attendanceRecords->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
