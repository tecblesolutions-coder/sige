@php
    $items = [
        ['label' => 'Panel', 'route' => 'dashboard', 'icon' => 'heroicon-o-home', 'can' => 'ver panel'],
        ['label' => 'Mi Asistencia', 'route' => 'my-attendance.index', 'icon' => 'heroicon-o-calendar', 'can' => 'ver mi asistencia'],
        ['label' => 'Empleados', 'route' => 'employees.index', 'icon' => 'heroicon-o-user-group', 'can' => 'ver empleados'],
        ['label' => 'Asistencias', 'route' => 'attendance.index', 'icon' => 'heroicon-o-clock', 'can' => 'ver asistencia'],
        ['label' => 'Reportes', 'route' => 'reports.index', 'icon' => 'heroicon-o-chart-pie', 'can' => 'ver reportes'],
        [
            'label' => 'Catálogos',
            'icon' => 'heroicon-o-folder',
            'can' => 'gestionar catalogos',
            'children' => [
                ['label' => 'Países', 'route' => 'countries.index', 'can' => 'gestionar catalogos'],
                ['label' => 'Estados', 'route' => 'states.index', 'can' => 'gestionar catalogos'],
                ['label' => 'Ciudades', 'route' => 'cities.index', 'can' => 'gestionar catalogos'],
                ['label' => 'Departamentos', 'route' => 'departments.index', 'can' => 'gestionar catalogos'],
                ['label' => 'Posiciones', 'route' => 'positions.index', 'can' => 'gestionar catalogos'],
            ],
        ],
        [
            'label' => 'Configuración',
            'icon' => 'heroicon-o-cog',
            'can' => 'ver usuarios',
            'children' => [
                ['label' => 'Usuarios', 'route' => 'users.index', 'can' => 'ver usuarios'],
                ['label' => 'Roles y permisos', 'route' => 'roles.index', 'icon' => 'heroicon-o-shield-check', 'can' => 'gestionar roles'],
            ],
        ],
    ];
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 shadow-sm transform transition-transform duration-200 ease-in-out dark:bg-gray-900 dark:border-gray-800"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto dark:hidden">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Logo" class="h-9 w-auto hidden dark:block">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">TECBLE SOLUTIONS</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">SIGE</p>
            </div>
        </div>
        <button class="p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring focus:ring-indigo-500 lg:hidden dark:hover:bg-gray-800"
            @click="sidebarOpen=false" aria-label="Cerrar menú">
            <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <nav class="px-3 py-4 space-y-2 overflow-y-auto h-[calc(100%-72px)]">
        @foreach ($items as $item)
            @if (!isset($item['can']) || auth()->user()->can($item['can']))
            <div x-data="{ open: {{ isset($item['children']) ? 'false' : 'true' }} }" class="rounded-lg">
                @if (isset($item['children']))
                    <button @click="open=!open"
                        class="flex w-full items-center justify-between px-3 py-2 rounded-lg hover:bg-indigo-50 text-gray-800 dark:text-gray-100 dark:hover:bg-gray-800 {{ $currentRoute === ($item['route'] ?? '') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200' : '' }}">
                        <span class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200">
                                <x-dynamic-component :component="$item['icon']" class="w-5 h-5" />
                            </span>
                            <span class="font-medium text-left">{{ $item['label'] }}</span>
                        </span>
                        <svg class="w-4 h-4 text-gray-500 transition-transform dark:text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 pl-8 space-y-1">
                        @foreach ($item['children'] as $child)
                            @if (!isset($child['can']) || auth()->user()->can($child['can']))
                                <a href="{{ route($child['route']) }}"
                                    class="flex items-center px-3 py-2 rounded-lg text-sm {{ $currentRoute === $child['route'] ? 'bg-indigo-100 text-indigo-700 font-semibold dark:bg-indigo-900/60 dark:text-indigo-200' : 'text-gray-700 hover:bg-indigo-50 dark:text-gray-200 dark:hover:bg-gray-800' }}">
                                    {{ $child['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-50 text-gray-800 dark:text-gray-100 dark:hover:bg-gray-800 {{ $currentRoute === $item['route'] ? 'bg-indigo-50 text-indigo-600 font-semibold dark:bg-indigo-900/40 dark:text-indigo-200' : '' }}">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200">
                           <x-dynamic-component :component="$item['icon']" class="w-5 h-5" />
                        </span>
                        <span class="font-medium">{{ $item['label'] }}</span>
                    </a>
                @endif
            </div>
            @endif
        @endforeach
    </nav>
</aside>
