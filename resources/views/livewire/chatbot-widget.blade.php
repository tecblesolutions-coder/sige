<div x-data="{ open: false }" class="fixed bottom-4 right-4 z-50">
    <!-- Chat Bubble Button -->
    <button @click="open = !open"
        class="flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-full text-white shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform hover:scale-110">
        <template x-if="!open">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </template>
        <template x-if="open">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </template>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border border-gray-200 flex flex-col h-[70vh] max-h-[70vh] dark:bg-gray-800 dark:border-gray-700"
         style="display: none;">

        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-indigo-600 text-white rounded-t-xl flex-shrink-0">
            <h3 class="text-lg font-semibold">Asistente Virtual</h3>
            <button @click="open = false" class="p-1 rounded-full hover:bg-indigo-500 focus:outline-none">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        <!-- Message Area -->
        <div id="chat-messages-container" class="flex-1 p-4 space-y-4 overflow-y-auto">
            @foreach($messages as $message)
                @if($message['sender'] == 'bot')
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 flex-shrink-0">
                            <x-heroicon-s-sparkles class="w-5 h-5 text-indigo-500" />
                        </div>
                        <div class="flex-1 bg-gray-100 p-3 rounded-lg dark:bg-gray-700">
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                {{ $message['content'] }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-3 flex-row-reverse">
                        <div class="flex-1 bg-indigo-500 text-white p-3 rounded-lg">
                            <p class="text-sm">
                                {{ $message['content'] }}
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
            <div wire:loading wire:target="sendMessage" class="flex items-start gap-3">
                <div class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 flex-shrink-0">
                    <x-heroicon-s-sparkles class="w-5 h-5 text-indigo-500" />
                </div>
                <div class="flex-1 bg-gray-100 p-3 rounded-lg dark:bg-gray-700">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        <span class="animate-pulse">...</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <form class="p-4 bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 rounded-b-xl flex-shrink-0" wire:submit.prevent="sendMessage">
            <div class="flex items-center gap-2">
                <input type="text"
                    wire:model.defer="newMessage"
                    placeholder="Escribe tu mensaje..."
                    class="flex-1 w-full px-4 py-2 text-sm text-gray-800 bg-gray-100 border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-200"
                    autocomplete="off">
                <button type="submit"
                    wire:loading.attr="disabled"
                    class="p-3 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <div wire:loading.remove wire:target="sendMessage">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div wire:loading wire:target="sendMessage">
                         <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>