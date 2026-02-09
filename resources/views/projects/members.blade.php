<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assign Members — {{ $project->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-green-800 mb-4">{{ session('success') }}</div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('projects.members.assign', $project) }}" method="POST">
                    @csrf
                    <div class="space-y-4" id="members-container">
                        @foreach($project->members as $member)
                            <div class="flex gap-2 items-center member-row">
                                <select name="members[{{ $loop->index }}][user_id]" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ $u->id == $member->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                                <input type="text" name="members[{{ $loop->index }}][role]" value="{{ $member->pivot->role }}" placeholder="Role (e.g. manager)" class="rounded-md border-gray-300 shadow-sm w-32">
                                <button type="button" class="text-red-600 remove-member">Remove</button>
                            </div>
                        @endforeach
                        <div class="flex gap-2 items-center member-row">
                            <select name="members[][user_id]" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">— Select user —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                            <input type="text" name="members[][role]" placeholder="Role" class="rounded-md border-gray-300 shadow-sm w-32">
                            <button type="button" class="text-red-600 remove-member">Remove</button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Leave "Select user" as is to add a new row; select a user and optionally set role. Empty user rows are ignored on save.</p>
                    <div class="mt-4 flex gap-2">
                        <x-primary-button>Save Members</x-primary-button>
                        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.remove-member').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.member-row').remove();
            });
        });
    </script>
</x-app-layout>
