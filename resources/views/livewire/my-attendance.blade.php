<div class="space-y-4">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Mi Asistencia</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Registra tu entrada/salida y consulta tu historial.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if ($this->todayAttendance->status === 'abierta')
                <button wire:click="markOut" wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-600 text-white text-sm font-semibold hover:bg-amber-500 focus:outline-none focus:ring focus:ring-amber-500 dark:focus:ring-amber-400 w-full sm:w-auto justify-center">
                    Marcar Salida
                </button>
            @elseif (!$this->todayAttendance->time_in)
                <button wire:click="markIn" wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500 focus:outline-none focus:ring focus:ring-emerald-500 dark:focus:ring-emerald-400 w-full sm:w-auto justify-center">
                    Marcar Entrada
                </button>
            @else
                <span class="text-sm text-gray-500 dark:text-gray-400">Asistencia de hoy completada.</span>
            @endif
        </div>
    </div>

    @if (session()->has('attendance-message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-100">
            {{ session('attendance-message') }}
        </div>
    @elseif (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-4 dark:bg-gray-800 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Desde</label>
                <input type="date" wire:model="rangeStart"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Hasta</label>
                <input type="date" wire:model="rangeEnd"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Entrada</th>
                        <th class="px-4 py-3 hidden sm:table-cell">Salida</th>
                        <th class="px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                    @forelse ($attendances as $attendance)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $attendance->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">
                                {{ $attendance->time_in ? $attendance->time_in->format('H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200 hidden sm:table-cell">
                                {{ $attendance->time_out ? $attendance->time_out->format('H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $attendance->status === 'abierta' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-100' : 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-100' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr class="sm:hidden text-xs text-gray-600 dark:text-gray-300">
                            <td colspan="4" class="px-4 pb-4">
                                <div class="flex flex-wrap gap-3">
                                    <span class="flex items-center gap-1"><span class="font-medium">Entrada:</span> {{ $attendance->time_in ? $attendance->time_in->format('H:i') : '-' }}</span>
                                    <span class="flex items-center gap-1"><span class="font-medium">Salida:</span> {{ $attendance->time_out ? $attendance->time_out->format('H:i') : '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">Sin registros de asistencia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>