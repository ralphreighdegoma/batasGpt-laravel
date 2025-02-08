<div class="max-w-xs h-52 rounded-lg border border-gray-300 shadow-md overflow-hidden">
    <div class="p-4 flex justify-between items-start">
        <div>
            <div class="font-bold text-gray-800">{{ $name }}</div>
            <div class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</div>
        </div>
        <div class="text-gray-400 text-sm">
            {{ \Carbon\Carbon::parse($date)->format('h:i A') }}
        </div>
    </div>
    <div class="px-4 pb-4">
        <div class="text-gray-700 truncate" style="max-height: 3.5em; overflow: hidden;">{{ $message }}</div>
    </div>
</div>
