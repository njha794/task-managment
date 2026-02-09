<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Milestone</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('milestones.update', $milestone) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $milestone->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $milestone->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="due_date" value="Due Date" />
                        <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', $milestone->due_date?->format('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                    <div class="flex gap-2">
                        <x-primary-button>Update Milestone</x-primary-button>
                        <a href="{{ route('projects.show', $milestone->project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
