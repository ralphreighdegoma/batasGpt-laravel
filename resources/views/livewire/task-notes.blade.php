<div>
    <div class="bg-gray-100 p-4 rounded-lg shadow-md h-screen">
        @foreach($notes as $note)
            <div class="p-4 rounded-lg mb-4 
                {{ $note['isActive'] ? 'bg-blue-200' : 'bg-white' }}
                ">
                <div class="flex justify-between">
                    <h3 class="font-bold {{ $note['isActive'] ? 'text-blue-900' : 'text-gray-900' }}">
                        {{ $note['title'] }}
                    </h3>
                    <span class="text-gray-600 text-sm">
                        {{ $note['date'] }}
                    </span>
                </div>
                <p class="text-gray-700 mt-2">
                    {{ $note['description'] }}
                </p>
            </div>
            @if(!$loop->last)
                <hr class="border-gray-300" />
            @endif
        @endforeach
    </div>
</div>
