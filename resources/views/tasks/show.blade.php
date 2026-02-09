<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 tracking-tight">{{ $task->title }}</h2>
                <p class="mt-0.5 text-sm text-gray-500">Task details</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-[#2E7D32]/40">Edit</a>
                @endcan
                @can('delete', $task)
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-red-700 bg-white border border-red-200 hover:bg-red-50">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-xl bg-[#e8f5e9] border border-[#2E7D32]/20 px-4 py-3 text-[#1b5e20] text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    @if($task->description)
                        <p class="text-gray-600">{{ $task->description }}</p>
                    @else
                        <p class="text-gray-500 italic">No description.</p>
                    @endif

                    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Project / Milestone</dt>
                            <dd class="mt-1">
                                <a href="{{ route('projects.show', $task->milestone->project) }}" class="text-sm font-medium text-[#2E7D32] hover:text-[#237026]">{{ $task->milestone->project->name }}</a>
                                <span class="text-sm text-gray-600"> → {{ $task->milestone->title }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                    @if($task->status === 'completed') bg-[#e8f5e9] text-[#2E7D32]
                                    @elseif($task->status === 'in_progress') bg-blue-50 text-blue-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ ucfirst($task->priority) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned to</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $task->assignee?->name ?? 'Unassigned' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created by</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $task->creator->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Start / Due date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $task->start_date?->format('M d, Y') ?? '—' }} / {{ $task->due_date?->format('M d, Y') ?? '—' }}</dd>
                        </div>
                    </dl>

                    @can('updateStatus', $task)
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-700 mb-3">Update status</p>
                            <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex flex-wrap gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="redirect" value="{{ route('tasks.show', $task) }}">
                                @foreach(\App\Models\Task::STATUSES as $s)
                                    @if($task->status !== $s)
                                        <button type="submit" name="status" value="{{ $s }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium border transition-colors
                                            @if($s === 'completed') border-[#2E7D32] text-[#2E7D32] hover:bg-[#e8f5e9]
                                            @else border-gray-200 text-gray-700 hover:bg-gray-50
                                            @endif">
                                            Mark {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </button>
                                    @endif
                                @endforeach
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
