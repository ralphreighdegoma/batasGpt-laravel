<div>
    @if ($currentTab === 1)
        <!-- Tab 1: Assign Program Form -->
        {{$this->assignProgramForm}}
        <div class="flex gap-2 mt-4 items-left">
            <x-filament::button
                wire:click="submitStep1"
                type="button"
                class="mt-4 flex items-center justify-center gap-2 w-[100px]"
            >
                Next
            </x-filament::button>
        </div>
    @elseif ($currentTab === 2)
        <!-- Tab 2: Assign Business Advisor Form -->
        
        {{$this->table}}

        <div class="flex gap-2 mt-4 items-left">
            <x-filament::button
                wire:click="$set('currentTab', 1)"
                type="button"
                class="flex items-center justify-center gap-2 w-[100px]"
            >
                Previous
            </x-filament::button>

            <x-filament::button
                wire:click="submitStep2"
                type="button"
                class="flex items-center justify-center gap-2 w-[100px]"
            >
                Next
            </x-filament::button>
        </div>

    @elseif ($currentTab === 3)
        <!-- Tab 3: Table and Submit Button -->
        {{$this->assignBusinessAdvisorForm}}

        <div class="flex gap-2 mt-4 items-left">
            <x-filament::button
                wire:click="$set('currentTab', 2)"
                type="button"
                class="flex items-center justify-center gap-2 w-[100px]"
            >
                Previous
            </x-filament::button>

            <x-filament::button
                wire:click="submit"
                type="button"
                class="flex items-center justify-center gap-2 w-[100px]"
            >
                Submit
            </x-filament::button>
        </div>
    @endif
</div>
