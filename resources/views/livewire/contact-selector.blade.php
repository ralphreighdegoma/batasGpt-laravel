<div class="space-y-4">
    <!-- Search Input -->
    <div class="relative">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Search for a contact..." 
            class="input w-full py-2 pl-3 pr-10 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        />
        
        <!-- Dropdown with Search Results -->
        @if (!empty($searchResults))
            <ul class="absolute left-0 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-10 max-h-60 overflow-y-auto">
                @foreach ($searchResults as $result)
                    <li wire:click="addContact({{ $result->id }})" 
                        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        {{ $result->first_name }} {{ $result->last_name }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Selected Contacts with Role and Delete Option -->
    <div class="space-y-2">
        @foreach ($selectedContacts as $index => $contact)
            <div class="flex items-center space-x-3 p-2 rounded-lg gap-3">
                <span class="text-sm font-medium text-gray-700 badge border border-gray-300 rounded-lg p-2">{{ $contact['first_name'] }} {{ $contact['last_name'] }}</span>
                
                <!-- Role Selection -->
                <select wire:model="selectedContacts.{{ $index }}.role" 
                        class="select w-32  p-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="owner">Owner</option>
                    <option value="watcher">Watcher</option>
                </select>
                
                <!-- Delete Button -->
                <x-filament::button wire:click="removeContact({{ $index }})">
                    <x-heroicon-o-trash class="w-4 h-4" />
                </x-filament::button>
            </div>
        @endforeach
    </div>
</div>
