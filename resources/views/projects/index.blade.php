<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 tracking-tight">Projects</h2>
                <p class="mt-0.5 text-sm text-gray-500">View and manage your projects</p>
            </div>
            @can('create', \App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    New Project
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="min-h-[60vh] bg-gradient-to-b from-gray-50 to-white py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-xl bg-[#e8f5e9] border border-[#2E7D32]/20 px-4 py-3 text-[#1b5e20] text-sm font-medium flex items-center gap-2 mb-6 shadow-sm" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($projects->total() > 0)
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#e8f5e9] text-[#2E7D32] font-semibold text-sm">{{ $projects->total() }}</span>
                    <span class="text-sm text-gray-600">{{ $projects->total() === 1 ? 'project' : 'projects' }}</span>
                </div>
            @endif

            {{-- Mobile: card list --}}
            <div class="block md:hidden space-y-4">
                @forelse($projects as $project)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-5 hover:shadow-lg hover:border-[#2E7D32]/20 transition-all duration-200">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('projects.show', $project) }}" class="font-semibold text-gray-900 truncate block hover:text-[#2E7D32] focus:outline-none focus:underline">
                                    {{ $project->name }}
                                </a>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $project->creator->name ?? '—' }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-[#e8f5e9] text-[#2E7D32]">
                                {{ $project->progress_percentage ?? 0 }}%
                            </span>
                        </div>
                        <div class="mt-3 flex items-center gap-3 text-sm text-gray-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                {{ $project->milestones->count() }} {{ $project->milestones->count() === 1 ? 'milestone' : 'milestones' }}
                            </span>
                        </div>
                        <div class="mt-3 h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#2E7D32] transition-all duration-300" style="width: {{ min($project->progress_percentage ?? 0, 100) }}%"></div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2">
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 text-sm font-medium text-[#2E7D32] hover:text-[#237026] focus:outline-none focus:underline">View project →</a>
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none focus:underline">Edit</a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-8 text-center">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-[#e8f5e9] flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">No projects yet</h3>
                        <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Create a project to add milestones and tasks and collaborate with your team.</p>
                        @can('create', \App\Models\Project::class)
                            <a href="{{ route('projects.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Create your first project
                            </a>
                        @endcan
                    </div>
                @endforelse
            </div>

            {{-- Desktop: table card --}}
            <div class="hidden md:block">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" role="table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Project</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Creator</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Milestones</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Progress</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($projects as $project)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-4 sm:px-6 py-4">
                                            <a href="{{ route('projects.show', $project) }}" class="font-medium text-[#2E7D32] hover:text-[#237026] focus:outline-none focus:underline">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $project->creator->name ?? '—' }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $project->milestones->count() }}</td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="flex items-center gap-2 max-w-[140px]">
                                                <div class="flex-1 h-2.5 rounded-full bg-gray-100 overflow-hidden" role="progressbar" aria-valuenow="{{ $project->progress_percentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="h-full rounded-full bg-[#2E7D32] transition-all duration-300" style="width: {{ min($project->progress_percentage ?? 0, 100) }}%"></div>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 tabular-nums w-8">{{ $project->progress_percentage ?? 0 }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-right">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-[#2E7D32] text-white hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2 transition-colors">
                                                    View
                                                </a>
                                                @can('update', $project)
                                                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors">
                                                        Edit
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 sm:px-6 py-12 text-center">
                                            <div class="max-w-md mx-auto">
                                                <div class="w-16 h-16 mx-auto rounded-2xl bg-[#e8f5e9] flex items-center justify-center mb-4">
                                                    <svg class="w-8 h-8 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                                </div>
                                                <h3 class="font-semibold text-gray-900">No projects yet</h3>
                                                <p class="text-sm text-gray-500 mt-1">Create a project to add milestones and tasks and collaborate with your team.</p>
                                                @can('create', \App\Models\Project::class)
                                                    <a href="{{ route('projects.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#2E7D32] hover:bg-[#237026] focus:ring-2 focus:ring-[#2E7D32]/50 focus:ring-offset-2 shadow-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                        Create your first project
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($projects->hasPages())
                        <div class="px-4 sm:px-6 py-3 border-t border-gray-100 bg-gray-50/80">{{ $projects->links() }}</div>
                    @endif
                </div>
            </div>

            @if($projects->hasPages() && $projects->total() > 0)
                <div class="block md:hidden mt-4">{{ $projects->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
