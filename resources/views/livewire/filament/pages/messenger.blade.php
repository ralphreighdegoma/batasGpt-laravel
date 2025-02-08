<div class="grid grid-cols-3 gap-4">
    <div class="col-span-1 border  h-screen p-4">
        <livewire:messenger.user-search />
        @livewire('messenger.thread-list', ['selectedThread' => $selectedThread])
    </div>
    <div class="col-span-2  border">
        @if($selectedThread)
            <livewire:messenger.message-display :key="$selectedThread->id" :thread="$selectedThread" />
        @else
            <div class="h-screen">
                <div class="flex items-center justify-center h-full bg-white text-gray-500">
                    <div class="text-center">
                        <p class="text-lg font-medium">Select a thread to view messages.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
