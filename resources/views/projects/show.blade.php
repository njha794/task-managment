<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 tracking-tight">{{ $project->name }}</h2>
                <p class="mt-0.5 text-sm text-gray-500">Project details and milestones</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('update', $project)
                    <a href="{{ route('projects.members', $project) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-[#2E7D32]/40">Members</a>
                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-[#2E7D32]/40">Edit</a>
                @endcan
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-red-700 bg-white border border-red-200 hover:bg-red-50">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-xl bg-[#e8f5e9] border border-[#2E7D32]/20 px-4 py-3 text-[#1b5e20] text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Project overview card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    <p class="text-gray-600">{{ $project->description ?: 'No description.' }}</p>
                    <p class="text-sm text-gray-500 mt-3">Created by <span class="font-medium text-gray-700">{{ $project->creator->name }}</span></p>

                    {{-- Progress bar section --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-sm font-medium text-gray-700">Overall progress</span>
                            <span class="text-sm font-semibold text-gray-900 tabular-nums">{{ number_format($project->progress_percentage ?? 0, 1) }}%</span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-gray-100 overflow-hidden" role="progressbar" aria-valuenow="{{ (int) round($project->progress_percentage ?? 0) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="h-full rounded-full bg-[#2E7D32] transition-all duration-500 ease-out" style="width: {{ min(100, max(0, (float) ($project->progress_percentage ?? 0))) }}%"></div>
                        </div>
                    </div>

                    @if($project->members->count() > 0)
                        <p class="text-sm text-gray-500 mt-4"><span class="font-medium text-gray-700">Members:</span> {{ $project->members->pluck('name')->join(', ') }}</p>
                    @endif
                </div>
            </div>

            {{-- Milestones section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-lg font-semibold text-gray-900">Milestones</h3>
                @can('create', \App\Models\Milestone::class)
                    <a href="{{ route('milestones.create', $project) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Milestone
                    </a>
                @endcan
            </div>

            @foreach($project->milestones as $milestone)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $milestone->title }}</h4>
                                @if($milestone->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->description }}</p>
                                @endif
                                <p class="text-sm text-gray-500 mt-2">Due: {{ $milestone->due_date?->format('M d, Y') ?? '—' }}</p>
                                {{-- Milestone progress bar --}}
                                <div class="mt-3 flex items-center gap-2">
                                    <div class="flex-1 max-w-[200px] sm:max-w-xs h-2 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-[#2E7D32] transition-all duration-300" style="width: {{ min(100, max(0, (float) ($milestone->progress_percentage ?? 0))) }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 tabular-nums">{{ number_format($milestone->progress_percentage ?? 0, 0) }}%</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 shrink-0">
                                @can('update', $milestone)
                                    <a href="{{ route('milestones.edit', $milestone) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-[#2E7D32] hover:bg-[#e8f5e9]">Edit</a>
                                @endcan
                                @can('delete', $milestone)
                                    <form action="{{ route('milestones.destroy', $milestone) }}" method="POST" class="inline" onsubmit="return confirm('Delete this milestone?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
                                    </form>
                                @endcan
                                @can('create', \App\Models\Task::class)
                                    <a href="{{ route('tasks.create', $milestone) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-[#2E7D32] hover:bg-[#e8f5e9]">Add Task</a>
                                @endcan
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                            @foreach($milestone->tasks as $task)
                                <div class="flex flex-wrap items-center justify-between gap-2 py-2 border-b border-gray-50 last:border-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('tasks.show', $task) }}" class="font-medium text-[#2E7D32] hover:text-[#237026]">{{ $task->title }}</a>
                                        <span class="text-sm text-gray-500 ml-2">— {{ ucfirst(str_replace('_', ' ', $task->status)) }} · {{ $task->assignee?->name ?? 'Unassigned' }}</span>
                                    </div>
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Edit</a>
                                    @endcan
                                </div>
                            @endforeach
                            @if($milestone->tasks->isEmpty())
                                <p class="text-sm text-gray-500 py-2">No tasks in this milestone.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($project->milestones->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    <p class="text-gray-500 mb-4">No milestones yet.</p>
                    @can('create', \App\Models\Milestone::class)
                        <a href="{{ route('milestones.create', $project) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026]">Add your first milestone</a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
