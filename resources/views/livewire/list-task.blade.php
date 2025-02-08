<div>
  @foreach ($items as $item)
      <div class="space-y-4">
          <div class="flex items-center text-xs rounded-md">
              <div class="w-16 truncate text-white rounded-tl-md rounded-bl-md p-2" style="background-color: red;">
                  <span>{{$item['date_created']}}</span>
              </div>
              <div class="flex-grow bg-gray-100 rounded-md p-2">
                  <p>{{$item['title']}}</p>
              </div>
          </div>
      </div>
  @endforeach

  @if(count($items) === 0)
    <div class="mt-4 fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">
        <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
          <svg class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
        </svg>
        </div>

        <h4 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
          No Tasks
        </h4>
    </div>
  @endif
</div>