<div>
    <button wire:click="$set('showModal', true)" class="px-4 py-2 bg-purple-500 text-white rounded">+ New Entry</button>

    <div x-show="$wire.showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            
            <div class="relative bg-white rounded-lg w-full max-w-md p-6">
                <button 
                    wire:click="$set('showModal', false)" 
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <h2 class="text-lg font-semibold mb-4">New Time Entry</h2>
                
                <div class="space-y-4">
                    {{ $this->form }}

                    <button 
                        wire:click="save"
                        class="w-full py-2 bg-purple-500 text-white rounded"
                    >
                        {{ empty($time) ? 'Start Timer' : 'Save Entry' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
