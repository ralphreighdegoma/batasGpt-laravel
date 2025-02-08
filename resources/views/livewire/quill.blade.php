<div>    
    <div wire:ignore>
        <div id="editor" style="height:200px" x-data x-init="
            quill = new Quill($el, {
                theme: 'snow'
            });
            Livewire.on('quill-initialized', () => {
                console.log('Quill Initialized');
            });

            Livewire.on('reset-message', () => {
                quill.setText('');
            });

            quill.on('text-change', function () {
                let value = document.getElementsByClassName('ql-editor')[0].innerHTML;
                @this.set('message', value)
            })
        "></div>
    </div>

    <button 
        wire:click="dispatchMessage()" 
        wire:loading.attr="disabled" 
        wire:target="dispatchMessage" 
        class="inline-flex items-center px-4 mt-10 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-white tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 transition ease-in-out duration-150">
        <span wire:loading.remove wire:target="dispatchMessage">Send</span>
        <span wire:loading wire:target="dispatchMessage">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8v-8H4z"></path>
            </svg>
        </span>
    </button>
</div>
