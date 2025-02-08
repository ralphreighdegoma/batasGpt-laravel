
<div class="flex" style="background-color:red;width:600px;heigh:500px">

  <div class="min-h-screen flex items-center justify-center bg-gray-900" style="background-color:red">
      <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
          <div class="flex justify-center mb-6">
              <img src="/path-to-logo.png" alt="Logo" class="h-12">
          </div>
          <form wire:submit.prevent="authenticate">
              <div class="mb-4">
                  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input id="email" type="email" wire:model.defer="email" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              </div>
              <div class="mb-4">
                  <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                  <input id="password" type="password" wire:model.defer="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              </div>
              <div class="flex items-center justify-between">
                  <label class="flex items-center">
                      <input type="checkbox" wire:model.defer="remember" class="form-checkbox">
                      <span class="ml-2 text-sm text-gray-700">Remember me</span>
                  </label>
                  <a href="#" class="text-sm text-orange-600 hover:underline">Forgot your password?</a>
              </div>
              <div class="mt-6">
                  <button type="submit" class="w-full bg-orange-500 text-white p-3 rounded-lg hover:bg-orange-600 transition ease-in-out" style="background-color: orange">
                      Sign in
                  </button>
              </div>
          </form>
      </div>
  </div>
</div>