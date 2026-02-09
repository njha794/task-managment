<section>
    <h3 class="text-lg font-semibold text-gray-900">Delete Account</h3>
    <p class="mt-1 text-sm text-gray-600">Once your account is deleted, all data is permanently removed. Download anything you need before deleting.</p>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="mt-4 inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
    >Delete Account</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900">Delete your account?</h2>
            <p class="mt-2 text-sm text-gray-600">This cannot be undone. Enter your password to confirm.</p>

            <div class="mt-4">
                <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500/50 text-gray-900">
                @error('password', 'userDeletion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </x-secondary-button>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
