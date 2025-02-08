<div {{ $attributes }}>
    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">Comments</h3>
    @livewire('task-comment', ['record' => $getRecord()])
</div>
