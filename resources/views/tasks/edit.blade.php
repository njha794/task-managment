<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Task</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $task->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $task->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="priority" value="Priority" />
                        <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach(\App\Models\Task::PRIORITIES as $p)
                                <option value="{{ $p }}" {{ old('priority', $task->priority) === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach(\App\Models\Task::STATUSES as $s)
                                <option value="{{ $s }}" {{ old('status', $task->status) === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="assigned_to" value="Assign To" />
                        <select id="assigned_to" name="assigned_to" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">— Unassigned —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('assigned_to', $task->assigned_to) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                    </div>
                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_date" value="Start Date" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $task->start_date?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="due_date" value="Due Date" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', $task->due_date?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <x-primary-button>Update Task</x-primary-button>
                        <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
