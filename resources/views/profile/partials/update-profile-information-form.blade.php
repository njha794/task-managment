<section class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="p-6 sm:p-8">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('patch')

            <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                {{-- Avatar: smaller size --}}
                <div class="shrink-0">
                    <div class="relative group w-20 h-20 sm:w-24 sm:h-24">
                        <div class="w-full h-full rounded-xl overflow-hidden bg-[#e8f5e9] ring-2 ring-gray-100">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover" id="avatar-preview">
                        </div>
                        <label for="avatar" class="absolute inset-0 flex items-center justify-center rounded-xl bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <span class="text-white text-xs font-medium">Change</span>
                        </label>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="sr-only" onchange="previewAvatar(this)">
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500">JPG, PNG, WebP. Max 2MB.</p>
                </div>

                <div class="flex-1 min-w-0 space-y-5">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                        <p class="mt-0.5 text-sm text-gray-600">Update your name, email and profile photo.</p>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32]/50 text-gray-900">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:border-[#2E7D32] focus:ring-[#2E7D32]/50 text-gray-900">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="mt-2 text-sm text-gray-600">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" type="submit" class="text-[#2E7D32] hover:text-[#237026] font-medium underline">{{ __('Resend verification email') }}</button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm font-medium text-[#2E7D32]">{{ __('A new verification link has been sent to your email.') }}</p>
                            @endif
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2 transition-colors shadow-sm">
                            Save changes
                        </button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-sm text-gray-500" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">Saved.</span>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function previewAvatar(input) {
    var preview = document.getElementById('avatar-preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { preview.src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
