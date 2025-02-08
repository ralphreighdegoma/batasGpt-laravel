<div>
    <div class="flex flex-col p-4 border rounded shadow">
        <div class="mb-2">
            <label for="message" class="block text-gray-700">Reply:</label>
            <textarea wire:model="message" id="message" class="w-full h-32 border rounded focus:outline-none focus:ring focus:ring-blue-500"></textarea>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <button type="button" class="mr-2">
                    <i class="fas fa-paperclip"></i> <!-- Attach icon -->
                </button>
            </div>
            <x-filament::button wire:click="sendMessage" class="bg-blue-500 hover:bg-blue-600">
                Send
            </x-filament::button>
        </div>
    </div>
</div>
<!-- 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-DyZvN6FRcXt4IzbKH7Z6HpFQ/l0aT1c55/jQ8Lehbj9cUnu5F+O73bO8P04Dg59A" crossorigin="anonymous"><!-- Include your rich text editor library -->
<!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
<!-- <script>
    tinymce.init({
        selector: '#message',
        plugins: 'lists link image charmap preview',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
                @this.set('message', editor.getContent());
            });
        }
    });
</script> 
 -->
