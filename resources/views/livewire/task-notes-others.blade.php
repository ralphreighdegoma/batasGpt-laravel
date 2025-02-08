<div class="h-screen">
    <div class="bg-gray-100 p-4 rounded-lg shadow-md h-full overflow-y-auto">
        <form wire:submit="create">
            {{ $this->form }}
            <div class="mt-4 mb-10">
                <button 
                    type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg"
                >
                    Save
                </button>
            </div>
        </form>
 
        
        @if (session()->has('success'))
            <div class="mt-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
