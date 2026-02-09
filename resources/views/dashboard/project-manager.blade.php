<x-app-layout>
    @push('styles')
    <style>
        .pm-card { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
        .pm-card .card-header { background: var(--app-primary-light); color: var(--app-primary); font-weight: 600; padding: 0.875rem 1.25rem; border-bottom: 2px solid rgba(13, 148, 136, 0.2); }
        .pm-project-card { border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; transition: box-shadow 0.2s ease, border-color 0.2s ease; }
        .pm-project-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); border-color: rgba(13, 148, 136, 0.3); }
        .pm-link { color: var(--app-primary); font-weight: 600; text-decoration: none; }
        .pm-link:hover { color: var(--app-primary-dark); }
        .pm-btn { background: var(--app-primary); color: #fff; border: none; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .pm-btn:hover { background: var(--app-primary-dark); color: #fff; }
        .pm-btn-outline { background: transparent; color: var(--app-primary); border: 1px solid var(--app-primary); }
        .pm-btn-outline:hover { background: var(--app-primary-light); color: var(--app-primary-dark); border-color: var(--app-primary); }
        .pm-progress { height: 8px; border-radius: 9999px; background: #e5e7eb; overflow: hidden; }
        .pm-progress-fill { height: 100%; border-radius: 9999px; background: var(--app-primary); transition: width 0.3s ease; }
        .pm-alert { background: var(--app-primary-light); border: 1px solid rgba(13, 148, 136, 0.25); color: #0f766e; border-radius: 0.75rem; }
        .pm-empty { color: #6b7280; }
    </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
            <div>
                <h2 class="h5 mb-1 fw-bold text-dark">{{ __('Project Manager Dashboard') }}</h2>
                <p class="text-muted small mb-0">Create and manage your projects</p>
            </div>
            <a href="{{ route('projects.create') }}" class="pm-btn btn btn-sm text-nowrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                New Project
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4 px-lg-5">
        @if (session('success'))
            <div class="pm-alert alert mb-4 d-flex align-items-center gap-2" role="alert">
                <svg class="flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="pm-card card">
            <div class="card-header d-flex align-items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                My Projects
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    @forelse($projects as $project)
                        <div class="col-12">
                            <div class="pm-project-card d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                <div class="flex-grow-1 min-w-0">
                                    <a href="{{ route('projects.show', $project) }}" class="pm-link fs-5 d-block mb-1">{{ $project->name }}</a>
                                    @if($project->description)
                                        <p class="text-muted small mb-2 mb-md-0">{{ Str::limit($project->description, 120) }}</p>
                                    @endif
                                    <span class="badge bg-light text-dark border border-secondary border-opacity-25">{{ $project->milestones_count }} milestone(s)</span>
                                </div>
                                <div class="d-flex flex-column align-items-md-end gap-2 w-100 w-md-auto" style="min-width: 180px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small fw-medium text-secondary">Progress</span>
                                        <span class="small fw-semibold" style="color: var(--app-primary);">{{ $project->progress_percentage }}%</span>
                                    </div>
                                    <div class="pm-progress w-100" style="min-width: 120px;">
                                        <div class="pm-progress-fill" style="width: {{ min(100, (int) $project->progress_percentage) }}%;"></div>
                                    </div>
                                    <div class="d-flex gap-2 mt-1">
                                        <a href="{{ route('projects.show', $project) }}" class="pm-btn pm-btn-outline btn btn-sm text-decoration-none">View</a>
                                        <a href="{{ route('projects.edit', $project) }}" class="pm-btn btn btn-sm text-decoration-none">Edit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 pm-empty">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-light" style="width: 64px; height: 64px;">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                </span>
                            </div>
                            <p class="mb-1 fw-medium">No projects yet</p>
                            <p class="small mb-3">Create your first project to get started.</p>
                            <a href="{{ route('projects.create') }}" class="pm-btn btn btn-sm text-decoration-none">New Project</a>
                        </div>
                    @endforelse
                </div>
                @if($projects->hasPages())
                    <div class="mt-4 pt-3 border-top">{{ $projects->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
