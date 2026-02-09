<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl sm:text-2xl text-gray-900 tracking-tight">Profile</h2>
            <p class="mt-0.5 text-sm text-gray-500">Manage your account and preferences</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @include('profile.partials.update-profile-information-form')

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
