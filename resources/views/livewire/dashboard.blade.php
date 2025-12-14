<div class="space-y-6">
    @if ($isEmployee)
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Mi panel</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Tu resumen de asistencia reciente.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-300">Últimos registros</p>
                <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                    @forelse ($myAttendance as $att)
                        <li class="flex items-center justify-between">
                            <span>{{ $att->date->format('d/m/Y') }}</span>
                            <span class="flex items-center gap-2">
                                <span class="text-gray-600 dark:text-gray-300">Entrada: {{ $att->time_in ? $att->time_in->format('H:i') : '-' }}</span>
                                <span class="text-gray-600 dark:text-gray-300">Salida: {{ $att->time_out ? $att->time_out->format('H:i') : '-' }}</span>
                            </span>
                        </li>
                    @empty
                        <li class="text-gray-500 dark:text-gray-300">Sin registros</li>
                    @endforelse
                </ul>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-300">Acciones rápidas</p>
                <div class="mt-3 flex gap-3">
                    <a href="{{ route('attendance.index') }}"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">Ir a asistencia</a>
                </div>
            </div>
        </div>
    @else
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Panel general</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Resumen rápido de personal y asistencia.</p>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Empleados</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $employeesCount }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center dark:bg-indigo-900/40 dark:text-indigo-200">
                        <x-heroicon-o-user-group class="h-6 w-6" />
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Departamentos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $departmentsCount }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center dark:bg-blue-900/40 dark:text-blue-100">
                        <x-heroicon-o-building-office-2 class="h-6 w-6" />
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Asistentes hoy</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $attendanceToday }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center dark:bg-emerald-900/40 dark:text-emerald-100">
                        <x-heroicon-o-calendar-days class="h-6 w-6" />
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Usuarios</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $usersCount }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center dark:bg-amber-900/40 dark:text-amber-100">
                        <x-heroicon-o-users class="h-6 w-6" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Empleados por cargo</p>
                </div>
                <div id="chart-positions" class="h-72"></div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Resumen</p>
                </div>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                    <li class="flex items-center justify-between">
                        <span>Total empleados</span><span class="font-semibold text-gray-900 dark:text-white">{{ $employeesCount }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Activos</span><span class="font-semibold text-gray-900 dark:text-white">{{ $activeCount }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Inactivos</span><span class="font-semibold text-gray-900 dark:text-white">{{ $inactiveCount }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Asistentes hoy</span><span class="font-semibold text-gray-900 dark:text-white">{{ $attendanceToday }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Departamentos</span><span class="font-semibold text-gray-900 dark:text-white">{{ $departmentsCount }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Usuarios</span><span class="font-semibold text-gray-900 dark:text-white">{{ $usersCount }}</span>
                    </li>
                </ul>
            </div>
        </div>
    @endif
</div>

@if (! $isEmployee)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (!window.ApexCharts) return;

        const renderPositionsChart = () => {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#6b7280';
            const gridColor = isDark ? '#374151' : '#f3f4f6';
            const tooltipTheme = isDark ? 'dark' : 'light';
            const colors = [isDark ? '#8b5cf6' : '#4f46e5'];

            const positionOptions = {
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
                    foreColor: textColor,
                    background: 'transparent',
                },
                series: [{
                    name: 'Empleados',
                    data: @json($positionValues),
                }],
                xaxis: {
                    categories: @json($positionLabels),
                    labels: { style: { colors: Array(@json($positionLabels).length).fill(textColor) } }
                },
                yaxis: {
                    labels: { style: { colors: textColor } }
                },
                colors,
                grid: { borderColor: gridColor },
                dataLabels: { enabled: false },
                tooltip: { theme: tooltipTheme }
            };

            const positionChartEl = document.querySelector('#chart-positions');
            if (positionChartEl) {
                positionChartEl.innerHTML = '';
                const positionChart = new ApexCharts(positionChartEl, positionOptions);
                positionChart.render();
            }
        };

        renderPositionsChart();

        const observer = new MutationObserver(() => renderPositionsChart());
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endpush
@endif