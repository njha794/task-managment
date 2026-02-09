@extends('layouts.admin')

@section('title', $project->name)

@push('styles')
<style>
    .admin-page-header { border-left: 4px solid var(--tf-primary); }
    .card-project { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
    .card-project .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 1rem 1.25rem; }
    .progress-bar-wrap { height: 10px; border-radius: 999px; background: #e5e7eb; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 999px; background: var(--tf-primary); transition: width .3s ease; }
    .link-tf { color: var(--tf-primary); font-weight: 500; text-decoration: none; }
    .link-tf:hover { color: var(--tf-primary-dark); }
    .alert-tf-success { background: var(--tf-primary-light); border: 1px solid rgba(46,125,50,.2); color: #1b5e20; border-radius: .75rem; }
    .milestone-card { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .milestone-card .progress-sm { height: 6px; border-radius: 999px; background: #e5e7eb; overflow: hidden; max-width: 180px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5">
    <header class="admin-page-header bg-white rounded-2 shadow-sm mb-4 px-4 py-4">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none" style="color: var(--tf-primary);">Projects</a></li>
                <li class="breadcrumb-item active">{{ $project->name }}</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="h4 mb-1 fw-bold text-dark">{{ $project->name }}</h1>
                <p class="text-muted small mb-0">Project details and milestones</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @can('update', $project)
                    <a href="{{ route('projects.members', $project) }}" class="btn btn-outline-secondary btn-sm">Members</a>
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                @endcan
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="alert alert-tf-success d-flex align-items-center gap-2 mb-4" role="alert">
            <svg class="bi flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Project overview --}}
    <div class="card card-project mb-4">
        <div class="card-header">Overview</div>
        <div class="card-body p-4">
            <p class="text-muted mb-3">{{ $project->description ?: 'No description.' }}</p>
            <p class="small text-muted mb-0">Created by <strong class="text-dark">{{ $project->creator->name }}</strong></p>

            <div class="mt-4 pt-4 border-top">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-medium text-dark">Overall progress</span>
                    <span class="small fw-semibold">{{ number_format($project->progress_percentage ?? 0, 1) }}%</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width: {{ min(100, max(0, (float) ($project->progress_percentage ?? 0))) }}%;"></div>
                </div>
            </div>

            @if($project->members->count() > 0)
                <p class="small text-muted mt-3 mb-0"><strong class="text-dark">Members:</strong> {{ $project->members->pluck('name')->join(', ') }}</p>
            @endif
        </div>
    </div>

    {{-- Milestones --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="h5 fw-bold text-dark mb-0">Milestones</h2>
        @can('create', \App\Models\Milestone::class)
            <a href="{{ route('milestones.create', $project) }}" class="btn btn-tf-primary btn-sm d-inline-flex align-items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Add Milestone
            </a>
        @endcan
    </div>

    @foreach($project->milestones as $milestone)
        <div class="card milestone-card mb-3">
            <div class="card-body p-4">
                <div class="row align-items-start">
                    <div class="col">
                        <h3 class="h6 fw-semibold text-dark mb-1">{{ $milestone->title }}</h3>
                        @if($milestone->description)
                            <p class="small text-muted mb-2">{{ $milestone->description }}</p>
                        @endif
                        <p class="small text-muted mb-2">Due: {{ $milestone->due_date?->format('M d, Y') ?? '—' }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress-sm flex-grow-1">
                                <div class="progress-bar-fill" style="width: {{ min(100, max(0, (float) ($milestone->progress_percentage ?? 0))) }}%;"></div>
                            </div>
                            <span class="small fw-medium">{{ number_format($milestone->progress_percentage ?? 0, 0) }}%</span>
                        </div>
                    </div>
                    <div class="col-auto d-flex flex-wrap gap-1">
                        @can('update', $milestone)
                            <a href="{{ route('milestones.edit', $milestone) }}" class="btn btn-sm btn-link link-tf p-0 me-2">Edit</a>
                        @endcan
                        @can('delete', $milestone)
                            <form action="{{ route('milestones.destroy', $milestone) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this milestone?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0 me-2">Delete</button>
                            </form>
                        @endcan
                        @can('create', \App\Models\Task::class)
                            <a href="{{ route('tasks.create', $milestone) }}" class="btn btn-tf-primary btn-sm">Add Task</a>
                        @endcan
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    @foreach($milestone->tasks as $task)
                        <div class="d-flex flex-wrap align-items-center justify-content-between py-2 border-bottom border-light {{ $loop->last ? 'border-bottom-0' : '' }}">
                            <div>
                                <a href="{{ route('tasks.show', $task) }}" class="link-tf fw-medium">{{ $task->title }}</a>
                                <span class="small text-muted ms-2">— {{ ucfirst(str_replace('_', ' ', $task->status)) }} · {{ $task->assignee?->name ?? 'Unassigned' }}</span>
                            </div>
                            @can('update', $task)
                                <a href="{{ route('tasks.edit', $task) }}" class="small link-tf">Edit</a>
                            @endcan
                        </div>
                    @endforeach
                    @if($milestone->tasks->isEmpty())
                        <p class="small text-muted mb-0 py-2">No tasks in this milestone.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    @if($project->milestones->isEmpty())
        <div class="card card-project">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">No milestones yet.</p>
                @can('create', \App\Models\Milestone::class)
                    <a href="{{ route('milestones.create', $project) }}" class="btn btn-tf-primary btn-sm">Add your first milestone</a>
                @endcan
            </div>
        </div>
    @endif
</div>
@endsection
