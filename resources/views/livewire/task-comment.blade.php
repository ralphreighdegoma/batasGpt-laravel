<div>
    <div>
        <!-- Comment Form -->
        <div class="mb-4 mt-10">
            <textarea wire:model="commentText" class="w-full p-2 border rounded" placeholder="Write a comment..."></textarea>
            @error('commentText') <span class="text-red-500">{{ $message }}</span> @enderror
            <x-filament::button wire:click="submit" color="primary" class="mt-2">
                Comment
            </x-filament::button>
        </div>

        <!-- Display Comments -->
        <div class="space-y-4">
            @foreach($comments as $comment)
            <div class="p-4 border rounded relative">
                <div class="flex justify-between items-start">
                    <!-- Comment Author and Content -->
                    <div>
                        <div class="text-sm text-gray-500 font-semibold">{{ $comment->user->name }} said:</div>
                        <p class="text-sm p-5 task-comment">{{ $comment->content }}</p>
                    </div>

                    <!-- Delete Button (Only visible to comment author) -->
                    @if($comment->user_id === auth()->id())
                    <x-filament::icon-button
                        icon="heroicon-o-x-circle"
                        color="danger"
                        size="sm"
                        class="absolute top-2 right-2"
                        wire:click="deleteComment({{ $comment->id }})"
                        label="Delete" />
                    @endif
                </div>
            </div>

            @endforeach
        </div>
    </div>

    <style>
        .task-comment {
            margin-top: 10px;
            margin-bottom: 10px;
            color: gray;
        }
    </style>

</div>