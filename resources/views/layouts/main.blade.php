<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false, theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }"
    x-init="document.documentElement.classList.toggle('dark', theme === 'dark');
            $watch('theme', value => {
                localStorage.setItem('theme', value);
                document.documentElement.classList.toggle('dark', value === 'dark');
            });">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex">
        <x-sidebar />
        <div class="flex-1 flex flex-col">
            <header class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <button class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 focus:outline-none focus:ring focus:ring-indigo-500 dark:border-gray-600 dark:hover:bg-gray-700"
                        @click="sidebarOpen = true" aria-label="Abrir menú">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Bienvenido</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->username }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 focus:outline-none focus:ring focus:ring-indigo-500 dark:border-gray-600 dark:hover:bg-gray-700"
                        @click="theme = theme === 'dark' ? 'light' : 'dark';"
                        aria-label="Cambiar tema">
                        <template x-if="theme === 'light'">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-11.314L7.05 7.05m11.314 11.314L16.95 16.95M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                        </template>
                        <template x-if="theme === 'dark'">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                            </svg>
                        </template>
                    </button>
                    <button class="hidden lg:inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring focus:ring-indigo-500 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                        onclick="document.getElementById('logout-form').submit();">
                        Cerrar sesión
                    </button>
                    <div class="lg:hidden">
                        <button class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 focus:outline-none focus:ring focus:ring-indigo-500 dark:border-gray-600 dark:hover:bg-gray-700"
                            onclick="document.getElementById('logout-form').submit();">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </header>
            <main class="flex-1 p-4 lg:p-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
            <footer class="px-4 py-4 bg-white border-t border-gray-200 text-sm text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                Derechos reservados © Gestión de Empleados 2025
            </footer>
        </div>
    </div>

    <div class="fixed inset-0 bg-black/50 z-40" x-show="sidebarOpen" x-transition @click="sidebarOpen=false"></div>

    @stack('modals')
    @stack('scripts')
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                get open() { return Alpine.evaluate(document.body, 'sidebarOpen'); },
                set open(val) { document.body.__x.$data.sidebarOpen = val; }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('scroll-chat-to-bottom', event => {
                const chatContainer = document.getElementById('chat-messages-container');
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });

            window.addEventListener('modal', event => {
                const { modalId, actionModal } = event.detail;
                const modal = document.querySelector(modalId);
                if (!modal) return;
                if (actionModal === 'show') {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });

            if (window.flatpickr) {
                const pickerOptions = { dateFormat: 'Y-m-d' };
                ['birthDate', 'dateHired'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        flatpickr(el, pickerOptions);
                    }
                });
            }
        });
    </script>
    <livewire:chatbot-widget />
</body>
</html>
