<div class="p-4 bg-white rounded shadow" x-data="{ 
    selectedTab: 'day',
    currentDate: new Date(),
    
    formatDate(date) {
        return date.toLocaleDateString('en-US', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });
    },
    
    nextDay() {
        this.currentDate.setDate(this.currentDate.getDate() + 1);
        this.currentDate = new Date(this.currentDate);
        this.currentDate = new Date(this.currentDate);

    },
    
    previousDay() {
        this.currentDate.setDate(this.currentDate.getDate() - 1);
        this.currentDate = new Date(this.currentDate);
    },
    
    setDate(date) {
        this.currentDate = new Date(date);
        $wire.currentDate = new Date(this.currentDate);
        $wire.stopAllTimers();
        $wire.refreshEntries(this.currentDate.toISOString().split('T')[0]);

    }
}">


    <div class="flex items-center justify-between mb-4">
        <!-- Date Navigation -->


        <div class="flex items-center">
            <button
                @click="previousDay()"
                class="py-1 px-3 rounded-md border border-gray-300 text-gray-600">&lt;</button>
            <button
                @click="nextDay()"
                class="py-1 px-3 rounded-md border border-gray-300 text-gray-600">&gt;</button>
            <div class="pl-4">
                &nbsp;&nbsp;<span class="font-semibold" x-text="'Today: ' + formatDate(currentDate)"></span>
            </div>
        </div>

        <!-- View Options -->
        <div class="flex space-x-2">
            <!-- <button class="px-4 py-2 rounded border border-gray-300 text-gray-600">Custom</button>
            <button class="px-4 py-2 rounded ">Week</button>
            <button class="px-4 py-2 rounded bg-purple-500 text-white">Day</button> -->
            <button
                class="px-4 py-2 rounded "
                :class="selectedTab === 'custom' ? 'bg-purple-500 text-white' : 'border border-gray-300 text-gray-600'">
                Custom
            </button>
            <button
                class="px-4 py-2 rounded "
                :class="selectedTab === 'week' ? 'bg-purple-500 text-white' : 'border border-gray-300 text-gray-600'"
                x-on:click="selectedTab = 'week'">
                Week
            </button>
            <button
                class="px-4 py-2 rounded "
                :class="selectedTab === 'day' ? 'bg-purple-500 text-white' : 'border border-gray-300 text-gray-600'"
                x-on:click="selectedTab = 'day'">
                Day
            </button>
        </div>
    </div>

    <div x-show="selectedTab == 'day'">
        <div class="grid grid-cols-8 gap-2 mt-4 text-center bg-blue-500 rounded text-white">
            <div class="col-span-8 flex">
                <div class="w-full p-2 bg-blue-500 rounded"></div>
                @foreach ($weekDates as $wdate)
                <div
                    class="w-full p-2 bg-blue-500 rounded cursor-pointer"
                    :class="currentDate.toISOString().split('T')[0] === '{{ $wdate['date'] }}' ? 'border-2 border-white' : ''"
                    @click="setDate('{{ $wdate['date'] }}'); selectedTab = 'day'">
                    <div>{{ $wdate['day'] }}</div>
                    <div>{{ date('d M', strtotime($wdate['date'])) }}</div>
                </div>
                @endforeach

            </div>
        </div>

        @foreach ($entries as $entry)
        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="rounded-lg shadow-lg bg-white border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <!-- Header Section -->
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="font-bold text-lg text-gray-800">Timesheet Entry</h2>
                            <p class="text-sm text-gray-500 mt-1">{{$entry['task']['title']}}</p>
                            <p class="text-sm text-gray-500 mt-1">{{$entry['notes']}}</p>
                        </div>

                        <!-- Timer Section -->
                        <!-- Timer Section -->
                        <div class="flex items-center gap-4">
                            <div class="bg-gray-100 rounded-lg px-4 py-2 flex items-center gap-3">
                                <span class="font-mono text-lg font-semibold text-gray-700"
                                    x-text="$wire.timers[{{ $entry->id }}]?.display || '00:00'"
                                    :class="{ 'animate-pulse text-green-600': $wire.timers[{{ $entry->id }}]?.running }">
                                </span>
                                <div class="flex gap-2">

                                    <button
                                        x-show="!$wire.timers[{{ $entry->id }}]?.running"
                                        wire:click="startTimer({{ $entry->id }})"
                                        class="p-2 rounded-full bg-green-500 hover:bg-green-600 text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        </svg>
                                    </button>

                                    <button
                                        x-show="$wire.timers[{{ $entry->id }}]?.running"
                                        wire:click="stopTimer({{ $entry->id }})"
                                        class="p-2 rounded-full bg-red-500 hover:bg-red-600 text-white transition-colors">
                                        <svg class="w-5 h-5" :class="{ 'animate-spin': $wire.timers[{{ $entry->id }}]?.running }" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6l5.25 3.15.75-1.23-4-2.37z"></path>
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- User Info Section -->
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ substr($entry['owner']['first_name'], 0, 1) }}{{ substr($entry['owner']['last_name'], 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{$entry['owner']['first_name']}} {{$entry['owner']['last_name']}}</p>
                            <p class="text-sm text-gray-500">Team Member</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endforeach

    </div>

    <div x-show="selectedTab == 'week'">
        <div class="grid grid-cols-8 gap-2 mt-4 text-center bg-blue-500 rounded text-white">
            <div class="col-span-8 flex">
                <div class="w-full p-2 bg-blue-500 rounded"></div>
                @foreach ($weekDates as $wdate)
                <div
                    class="w-full p-2 bg-blue-500 rounded cursor-pointer"
                    :class="currentDate.toISOString().split('T')[0] === '{{ $wdate['date'] }}' ? 'border-2 border-white' : ''"
                    @click="setDate('{{ $wdate['date'] }}'); selectedTab = 'day'">
                    <div>{{ $wdate['day'] }}</div>
                    <div>{{ date('d M', strtotime($wdate['date'])) }}</div>
                </div>
                @endforeach

            </div>
        </div>

        @foreach ($entries as $entry)
        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="rounded-lg shadow-lg bg-white border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    
                </div>
            </div>
        </div>

        @endforeach

    </div>

    <div class="p-4 bg-white rounded shadow" x-data="{ selectedTab: 'day'}">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-semibold">Timesheet</h1>
            @livewire('create-time-entry', ['date' => $currentDate], key($currentDate))
        </div>
        <!-- rest of your timesheet code -->
    </div>
</div>