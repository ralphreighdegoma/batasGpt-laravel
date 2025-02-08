<div class="flex flex-col h-full bg-white rounded-lg shadow-lg">
    <!-- Header -->
    @if ($selectedThread)
    <div class="p-4 border-b bg-gray-100">
        <h3 class="text-lg font-semibold text-gray-800">
            {{ $selectedThread->name ?? $selectedThread->users->except(auth()->id())->pluck('name')->join(', ') }}
        </h3>
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-4 space-y-6" id="messages-container" style="max-height: 400px;">
        @foreach($selectedThread->messages as $message)
        <div
            x-data="{ isOpen: true }"
            wire:key="{{ $message->id }}"
            class="flex w-full {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
            <div
                class="w-full md:w-2/3 {{ $message->sender_id === auth()->id() ? 'bg-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-6 py-4 shadow-sm">
                <!-- Sender Info -->
                <div
                    class="flex items-center justify-between mb-3 cursor-pointer"
                    @click="isOpen = !isOpen">
                    <div class="text-sm">
                        <span class="font-bold">{{ $message->sender->name }}</span>
                        <span class="text-gray-500">({{ $message->sender->email }})</span>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $message->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
                <!-- Message Body -->
                <div x-show="isOpen" class="transition-all duration-300 ease-in-out">
                    <p class="text-base leading-relaxed">
                        {!! $message->body !!}
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="border-t p-4 bg-gray-50">
        <livewire:quill />
    </div>
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
