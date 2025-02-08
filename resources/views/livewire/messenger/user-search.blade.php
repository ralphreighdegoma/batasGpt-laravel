<div class="mb-4">
    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..." class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    @if ($search)
        <ul class="absolute bg-white border rounded shadow-md mt-2">
            @forelse ($users as $user)
                <li class="px-4 py-2 hover:bg-gray-100  cursor-pointer" wire:click="startConversation({{ $user->id }})">
                    {{ $user->name }}
                </li>
            @empty
                <li class="px-4 py-2">No users found.</li>
            @endforelse
        </ul>
    @endif
</div>
