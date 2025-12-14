<div class="space-y-4">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Pa?ses</h1>
            <p class="text-sm text-gray-500">Cat?logo de pa?ses.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <input type="search" wire:model="search" placeholder="Buscar"
                class="w-full sm:w-64 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
            <div wire:loading>
                <span class="text-sm text-gray-500">Cargando...</span>
            </div>
            <button wire:click="showCountryModal"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-500 w-full sm:w-auto justify-center">
                Nuevo pa?s
            </button>
        </div>
    </div>

    @if (session()->has('country-message'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('country-message') }}
        </div>
    @endif
    @if (session()->has('country-error'))
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            {{ session('country-error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" wire:loading.remove>
                <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:bg-gray-700 dark:text-gray-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3 hidden sm:table-cell">C?digo</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm dark:divide-gray-700">
                    @forelse ($countries as $key => $country)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-200">{{ $countries->firstItem() + $key }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100 hidden sm:table-cell">{{ $country->country_code }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $country->name }}</td>
                            <td class="px-4 py-3 text-right space-x-2 flex flex-wrap justify-end gap-2">
                                <button wire:click="showEditModal({{ $country->id }})"
                                    class="px-3 py-1 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-semibold w-full sm:w-auto">
                                    Editar
                                </button>
                                <button wire:click="deleteCountry({{ $country->id }})"
                                    class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-xs font-semibold w-full sm:w-auto">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        <tr class="sm:hidden text-xs text-gray-600 dark:text-gray-300">
                            <td colspan="4" class="px-4 pb-4">
                                <div class="flex flex-wrap gap-3">
                                    <span class="flex items-center gap-1"><span class="font-semibold">C?digo:</span> {{ $country->country_code }}</span>
                                </div>
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
                {{ $countries->links() }}
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 px-4" id="countryModal">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-xl dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $editMode ? 'Editar pa?s' : 'Crear pa?s' }}
                </h3>
                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" wire:click="closeModal" aria-label="Cerrar">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">C?digo</label>
                    <input id="countryCode" type="text" wire:model.defer="countryCode"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    @error('countryCode') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                    <input id="name" type="text" wire:model.defer="name"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <button class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                    wire:click="closeModal">Cerrar</button>
                @if ($editMode)
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500"
                        wire:click="updateCountry">Actualizar</button>
                @else
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500"
                        wire:click="storeCountry">Guardar</button>
                @endif
            </div>
        </div>
    </div>
</div>
