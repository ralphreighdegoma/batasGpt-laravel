
<div
  x-data="{ selectedThread: @entangle('selectedThread'), message: @entangle('message'), currentUserId: @entangle('currentUserId'), threads: @entangle('threads'), selectedUserId: @entangle('selectedUserId')}" >
    <input class="selected_thread" :id="selectedThread.id" style="display:none" x-ref="selected_thread"/>
    <div class="flex space-x-4">
        <button wire:click="setActiveTab('messages')" class="{{ $activeTab === 'messages' ? 'font-bold' : '' }}">
            Inbox
        </button>
        <button wire:click="setActiveTab('notifications')" class="{{ $activeTab === 'notifications' ? 'font-bold' : '' }}">
            Notifications
        </button>
    </div>

    <x-filament::modal
        id="addThreadModal"
        heading="New Thread">
        <div class="space-y-4">
            <form wire:submit.prevent="createThread(selectedUserId)">
                <select wire:model="selectedUserId" class="block w-full py-2 px-3 border border-gray-300 rounded-md">
                    <option value="" disabled>Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
                <div class="mt-4 flex justify-end gap-x-4">
                    <button
                        type="submit"
                        class="bg-purple-500 text-white p-2 rounded">
                        Submit
                    </button>
                    <button
                        type="button"
                        class="bg-purple-500 text-white p-2 rounded"
                        @click="$dispatch('close-modal', { id: 'addThreadModal' })">
                        Close
                    </button>
                </div>
            </form>

        </div>
    </x-filament::modal>

    <div class="mt-4">
        @if ($activeTab === 'messages')
        <div class="flex justify-end mb-2">
            <button type="button" class="bg-purple-500 text-white p-2 rounded" @click="$dispatch('open-modal', { id: 'addThreadModal' })">
                New Message
            </button>
        </div>
        <div class="p-4 flex h-screen gap-x-2">
            <div class="flex-none" style="max-width: 300px; width: 100%; max-height: calc(100vh - 2rem); overflow-y: auto;">
                <!-- <h2 class="text-xl">Messages</h2> -->
                <div class="flex flex-col gap-y-2">
                <template x-for="(thread) in threads">
                    <div
                        class="max-w-xs rounded-lg cursor-pointer border border-b-1 overflow-hidden"
                        :class="selectedThread && selectedThread.id == thread.id ? 'bg-blue-300 text-black' : 'bg-white hover:bg-gray-300 text-black'"
                        x-on:click="selectedThread = thread"
                        x-key="'thread-'+thread.id'">
                        <div>
                            <div class="p-4 flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-gray-800" x-text="thread.name"></div>
                                    <div class="text-gray-500 text-sm" x-text="thread.date"></div>
                                </div>
                            </div>
                            <div class="px-4 pb-4">
                                <div class="text-gray-700 truncate" style="max-height: 3.5em; overflow: hidden;" x-html="thread.message"></div>
                            </div>
                        </div>
                    </div>
                </template>
                </div>
            </div>

            <div class="ml-4 ">
                <!-- Message viewer should take up available space -->
                <template x-if="selectedThread">
                    <div class="border p-4 rounded-lg" style="max-height: calc(100vh - 20rem);height: 100%; overflow-y: auto;" >
                        <div class="mx-4">
                            <div class="" >
                                <div class="mb-2">
                                    <!-- <div>
                                        <div class="flex justify-between">
                                            <div class="text-gray-500 mb-2"></div>
                                            <div class="flex gap-x-2">
                                                <button type="button" class="rounded bg-blue-300 py-1 px-2 text-white">
                                                    <i class="fa-solid fa-reply"></i> Reply
                                                </button>
                                                <button type="button" class="rounded bg-red-300 py-1 px-2">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div> -->
                                    <template x-for="(message_r, index) in selectedThread.messages">
                                        <div class="flex" :class="message_r.sender_id == currentUserId ? 'justify-end' : 'justify-start'">
                                            <div class="mt-4 border rounded p-2">
                                                <div>
                                                    <strong x-text="message_r.sender.name"></strong>
                                                </div>
                                                <div>
                                                    <p x-html="message_r.body"></p>
                                                    <p x-text="message_r.date" class="text-gray-500 text-xs">

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>



                <!-- Reply box always stays at the bottom -->
                <div class="mt-auto">
                    <template x-if="selectedThread">
                        <form
                          wire:submit.prevent="sendMessage(selectedThread.other_user_id,selectedThread.id,message)">
                            <div class="flex flex-col p-4 border rounded shadow">
                                <div class="mb-2">
                                    {{ $this->form }}
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <button type="button" class="mr-2">
                                            <i class="fas fa-paperclip"></i> <!-- Attach icon -->
                                        </button>
                                    </div>
                                    <button type="submit" class="p-4 rounded bg-blue-300 hover:bg-blue-600">
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>



        @elseif ($activeTab === 'notifications')
            <div class="p-4 border rounded">
                <h2 class="text-xl">Notifications</h2>
                <p>Your notifications will be displayed here.</p>
                <!-- Add your notifications logic here -->
            </div>
        @endif
    </div>
</div>

<script type="text/javascript">
    let timeoutId;

    window.addEventListener("DOMContentLoaded", function() {
        console.log('DOMContentLoaded', window.Echo)


        window.Echo.channel('chat')
        .listen('.new-message', (data) => {
            console.log('receiveMessage', data)
            Livewire.dispatch('receiveMessage', data);
        });

        // intervalId = setInterval(() => {
        //     const alpineDiv = document.querySelector('[x-ref="selected_thread"]');
        //     console.log('selectedThread', alpineDiv.id);
        // }, 1000);
    });

    // window.addEventListener('beforeunload', () => {
    //     // Clear the Interval when the page is about to unload
    //     clearInterval(intervalId);
    // });
</script>
