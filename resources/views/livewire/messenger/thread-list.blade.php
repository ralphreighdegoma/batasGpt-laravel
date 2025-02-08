<div class="bg-white rounded-lg shadow">
    <ul class="divide-y divide-gray-200">
        @foreach($threads as $thread)
            <li 
                wire:key="{{ $thread->id }}" 
                wire:click="selectThread({{ $thread->id }})"
                class="flex items-center p-4 hover:bg-gray-50 {{ $selectedThreadId == $thread->id ? 'selected-thread' : '' }} cursor-pointer transition-colors duration-150"
            >
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                        {{ substr($thread->name ?? $thread->participants->except(auth()->id())->map->user->pluck('name')->first(), 0, 1) }}
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">
                        {{ $thread->name ?? $thread->participants->except(auth()->id())->map->user->pluck('name')->join(', ') }}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                        Last message preview here
                    </p>
                </div>
            </li>
        @endforeach
    </ul>
</div>
