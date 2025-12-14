<div class="space-y-4">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Hoja de Asistencia Diaria</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Marca la entrada y salida de los empleados para la fecha seleccionada.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Seleccionar Fecha</label>
            <input type="date" wire:model="date"
                class="mt-1 w-full lg:w-auto rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
        </div>
    </div>

    @if (session()->has('attendance-message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-100">
            {{ session('attendance-message') }}
        </div>
    @endif
    
    <div wire:loading.flex class="items-center justify-center w-full">
        <span class="text-sm text-gray-500">Cargando empleados...</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700" wire:loading.remove>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3">Empleado</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Entrada</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Salida</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                    @forelse ($employees as $employee)
                        @php $attendance = $todaysAttendances->get($employee->id); @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                            <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-200">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($attendance && $attendance->time_in)
                                    @if ($attendance->time_out)
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-100">
                                            Completo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-100">
                                            Presente
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-900/40 dark:text-gray-400">
                                        Ausente
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell">
                                {{ $attendance && $attendance->time_in ? $attendance->time_in->format('H:i A') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell">
                                {{ $attendance && $attendance->time_out ? $attendance->time_out->format('H:i A') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                @can('gestionar asistencia')
                                    @if ($attendance && $attendance->time_in && !$attendance->time_out)
                                        <button wire:click="markOut({{ $employee->id }})" wire:loading.attr="disabled"
                                            class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-600 text-white text-xs font-semibold hover:bg-amber-500 focus:outline-none focus:ring focus:ring-amber-500">
                                            Marcar Salida
                                        </button>
                                    @elseif (!$attendance || !$attendance->time_in)
                                        <button wire:click="markIn({{ $employee->id }})" wire:loading.attr="disabled"
                                            class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-500 focus:outline-none focus:ring focus:ring-emerald-500">
                                            Marcar Entrada
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">No hay empleados activos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>