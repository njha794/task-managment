@extends('layouts.admin')

@section('title', 'Projects')

@push('styles')
<style>
    .admin-page-header { border-left: 4px solid var(--tf-primary); }
    .card-projects { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
    .card-projects .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
    .card-projects .table thead th { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; background: #f9fafb; padding: .75rem 1.25rem; border-bottom: 1px solid #eee; }
    .card-projects .table tbody td { padding: .875rem 1.25rem; vertical-align: middle; }
    .card-projects .table tbody tr { transition: background .15s ease; }
    .card-projects .table tbody tr:hover { background: #f9fafb; }
    .link-tf { color: var(--tf-primary); font-weight: 500; text-decoration: none; }
    .link-tf:hover { color: var(--tf-primary-dark); }
    .alert-tf-success { background: var(--tf-primary-light); border: 1px solid rgba(46,125,50,.2); color: #1b5e20; border-radius: .75rem; }
    .btn-tf-sm { padding: .35em .75em; font-size: .875rem; font-weight: 500; border-radius: .5rem; }
    .progress-wrap { max-width: 100px; height: 8px; border-radius: 999px; background: #e5e7eb; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 999px; background: var(--tf-primary); transition: width .3s ease; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5">
    <header class="admin-page-header bg-white rounded-2 shadow-sm mb-4 px-4 py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h1 class="h4 mb-1 fw-bold text-dark">Projects</h1>
                <p class="text-muted small mb-0">View and manage all projects</p>
            </div>
            @can('create', \App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="btn btn-tf-primary btn-sm d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    New Project
                </a>
            @endcan
        </div>
    </header>

    @if (session('success'))
        <div class="alert alert-tf-success d-flex align-items-center gap-2 mb-4" role="alert">
            <svg class="bi flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($projects->total() > 0)
        <p class="text-muted small mb-3"><span class="fw-medium text-dark">{{ $projects->total() }}</span> {{ $projects->total() === 1 ? 'project' : 'projects' }}</p>
    @endif

    <div class="card card-projects">
        <div class="card-header">
            <span>All Projects</span>
            @can('create', \App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="link-tf small">New Project →</a>
            @endcan
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Creator</th>
                        <th>Milestones</th>
                        <th>Progress</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                <a href="{{ route('projects.show', $project) }}" class="link-tf fw-medium">{{ $project->name }}</a>
                            </td>
                            <td class="text-muted">{{ $project->creator->name ?? '—' }}</td>
                            <td class="text-muted">{{ $project->milestones->count() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-wrap flex-grow-1">
                                        <div class="progress-fill" style="width: {{ min(100, max(0, (float) ($project->progress_percentage ?? 0))) }}%"></div>
                                    </div>
                                    <span class="small fw-medium">{{ number_format($project->progress_percentage ?? 0, 0) }}%</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-tf-primary btn-tf-sm">View</a>
                                    @can('update', $project)
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary btn-tf-sm">Edit</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <div class="py-3">
                                    <div class="rounded-2 bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="text-secondary"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                    </div>
                                    <p class="mb-2">No projects yet.</p>
                                    @can('create', \App\Models\Project::class)
                                        <a href="{{ route('projects.create') }}" class="btn btn-tf-primary btn-sm">Create your first project</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
            <div class="card-footer bg-light border-0 py-3">{{ $projects->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection
