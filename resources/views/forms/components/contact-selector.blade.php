<div {{ $attributes }}>
    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">Search Assignee</h3>
    @livewire('contact-selector', ['record' => $getRecord()])
</div>
