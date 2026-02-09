<section>
    <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
    <p class="mt-1 text-sm text-gray-600">Use a long, random password to keep your account secure.</p>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" id="update_password_current_password" name="current_password" autocomplete="current-password" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32]/50 text-gray-900">
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="update_password_password" name="password" autocomplete="new-password" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32]/50 text-gray-900">
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32]/50 text-gray-900">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2 transition-colors">
                Save password
            </button>
            @if (session('status') === 'password-updated')
                <span class="text-sm text-gray-500" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">Saved.</span>
            @endif
        </div>
    </form>
</section>
