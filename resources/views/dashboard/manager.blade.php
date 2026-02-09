<x-app-layout>
    @push('styles')
    <style>
        .mgr-card { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
        .mgr-card .card-header { background: var(--app-primary-light); color: var(--app-primary); font-weight: 600; padding: 0.875rem 1.25rem; border-bottom: 2px solid rgba(13, 148, 136, 0.2); }
        .mgr-project-card { border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem; transition: box-shadow 0.2s ease, border-color 0.2s ease; }
        .mgr-project-card:last-child { margin-bottom: 0; }
        .mgr-project-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); border-color: rgba(13, 148, 136, 0.3); }
        .mgr-link { color: var(--app-primary); font-weight: 600; text-decoration: none; }
        .mgr-link:hover { color: var(--app-primary-dark); }
        .mgr-link-sm { color: var(--app-primary); font-weight: 500; text-decoration: none; font-size: 0.875rem; }
        .mgr-link-sm:hover { color: var(--app-primary-dark); }
        .mgr-progress { height: 6px; border-radius: 9999px; background: #e5e7eb; overflow: hidden; }
        .mgr-progress-fill { height: 100%; border-radius: 9999px; background: var(--app-primary); transition: width 0.3s ease; }
        .mgr-milestone { border-left: 3px solid var(--app-primary); padding-left: 1rem; margin-top: 0.75rem; }
        .mgr-alert { background: var(--app-primary-light); border: 1px solid rgba(13, 148, 136, 0.25); color: #0f766e; border-radius: 0.75rem; }
        .mgr-badge { font-size: 0.7rem; font-weight: 500; padding: 0.2rem 0.5rem; border-radius: 9999px; }
        .mgr-badge-pending { background: #f3f4f6; color: #4b5563; }
        .mgr-badge-progress { background: #fef3c7; color: #b45309; }
        .mgr-badge-done { background: var(--app-primary-light); color: var(--app-primary-dark); }
        .mgr-empty { color: #6b7280; }
    </style>
    @endpush

    <x-slot name="header">
        <div>
            <h2 class="h5 mb-1 fw-bold text-dark">{{ __('Manager / HR Dashboard') }}</h2>
            <p class="text-muted small mb-0">Projects assigned to you</p>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4 px-lg-5">
        @if (session('success'))
            <div class="mgr-alert alert mb-4 d-flex align-items-center gap-2" role="alert">
                <svg class="flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="mgr-card card">
            <div class="card-header d-flex align-items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                Assigned Projects
            </div>
            <div class="card-body p-4">
                @forelse($projects as $project)
                    <div class="mgr-project-card">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                            <div>
                                <a href="{{ route('projects.show', $project) }}" class="mgr-link fs-5">{{ $project->name }}</a>
                                <p class="text-muted small mb-0 mt-1">By {{ $project->creator->name }} · {{ $project->milestones_count }} milestone(s)</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small fw-semibold" style="color: var(--app-primary);">{{ $project->progress_percentage }}%</span>
                                <div class="mgr-progress" style="width: 100px;">
                                    <div class="mgr-progress-fill" style="width: {{ min(100, (int) $project->progress_percentage) }}%;"></div>
                                </div>
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-secondary text-decoration-none">View</a>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-secondary border-opacity-25">
                            @foreach($project->milestones as $milestone)
                                <div class="mgr-milestone">
                                    <span class="fw-medium text-dark">{{ $milestone->title }}</span>
                                    <span class="text-muted small">({{ $milestone->tasks->count() }} tasks)</span>
                                    @foreach($milestone->tasks->take(3) as $task)
                                        <div class="d-flex flex-wrap align-items-center gap-1 mt-1 small">
                                            <a href="{{ route('tasks.show', $task) }}" class="mgr-link-sm">{{ $task->title }}</a>
                                            <span class="mgr-badge @if($task->status === 'completed') mgr-badge-done @elseif($task->status === 'in_progress') mgr-badge-progress @else mgr-badge-pending @endif">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                        </div>
                                    @endforeach
                                    @if($milestone->tasks->count() > 3)
                                        <div class="small text-muted mt-1">+ {{ $milestone->tasks->count() - 3 }} more</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 mgr-empty">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-light" style="width: 64px; height: 64px;">
                                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                            </span>
                        </div>
                        <p class="mb-0 fw-medium">No assigned projects</p>
                        <p class="small mb-0 mt-1">Projects assigned to you will appear here.</p>
                    </div>
                @endforelse
                @if($projects->hasPages())
                    <div class="mt-4 pt-3 border-top">{{ $projects->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
