<x-app-layout>
    @push('styles')
    <style>
        .ud-card { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
        .ud-card .card-header { background: var(--app-primary-light); color: var(--app-primary); font-weight: 600; padding: 0.875rem 1.25rem; border-bottom: 2px solid rgba(13, 148, 136, 0.2); }
        .ud-tabs .nav-link { font-weight: 500; border: none; border-bottom: 2px solid transparent; border-radius: 0; color: #6b7280; padding: 0.75rem 1rem; }
        .ud-tabs .nav-link:hover { color: var(--app-primary); }
        .ud-tabs .nav-link.active { color: var(--app-primary); border-bottom-color: var(--app-primary); background: transparent; }
        .ud-form-control:focus { border-color: var(--app-primary); box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.2); }
        .ud-btn { background: var(--app-primary); color: #fff; border: none; font-weight: 500; }
        .ud-btn:hover { background: var(--app-primary-dark); color: #fff; }
        .ud-btn-outline { background: transparent; color: var(--app-primary); border: 1px solid var(--app-primary); }
        .ud-btn-outline:hover { background: var(--app-primary-light); color: var(--app-primary-dark); border-color: var(--app-primary); }
        .ud-link { color: var(--app-primary); font-weight: 600; text-decoration: none; }
        .ud-link:hover { color: var(--app-primary-dark); }
        .ud-task-row { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; transition: background 0.15s ease; }
        .ud-task-row:hover { background: #f9fafb; }
        .ud-task-row:last-child { border-bottom: none; }
        .ud-icon-box { width: 40px; height: 40px; border-radius: 0.75rem; background: var(--app-primary-light); color: var(--app-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ud-badge { font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.6rem; border-radius: 9999px; }
        .ud-badge-pending { background: #f3f4f6; color: #4b5563; }
        .ud-badge-progress { background: #fef3c7; color: #b45309; }
        .ud-badge-done { background: var(--app-primary-light); color: var(--app-primary-dark); }
        .ud-alert { background: var(--app-primary-light); border: 1px solid rgba(13, 148, 136, 0.25); color: #0f766e; border-radius: 0.75rem; }
        .ud-empty { color: #6b7280; }
    </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">
            <div>
                <h2 class="h5 mb-1 fw-bold text-dark">My Tasks</h2>
                <p class="text-muted small mb-0">View and update your assigned tasks</p>
            </div>
            <form method="get" action="{{ route('dashboard') }}" class="d-flex flex-wrap align-items-center gap-2 justify-content-lg-end">
                <input type="hidden" name="tab" value="{{ $tab ?? 'pending' }}">
                <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Search tasks..." class="form-control form-control-sm ud-form-control" style="width: 160px;">
                <select name="priority" class="form-select form-select-sm ud-form-control" style="width: auto; min-width: 100px;">
                    <option value="">Priority</option>
                    @foreach(\App\Models\Task::PRIORITIES as $p)
                        <option value="{{ $p }}" {{ ($priority ?? '') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                <select name="project" class="form-select form-select-sm ud-form-control" style="width: auto; min-width: 120px;">
                    <option value="">Project</option>
                    @foreach($projectsForFilter ?? [] as $proj)
                        <option value="{{ $proj->id }}" {{ (string)($projectId ?? '') === (string)$proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="ud-btn btn btn-sm d-inline-flex align-items-center gap-1">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Apply
                </button>
            </form>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4 px-lg-5">
        @if (session('success'))
            <div class="ud-alert alert mb-4 d-flex align-items-center gap-2" role="alert">
                <svg class="flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <ul class="nav ud-tabs border-bottom mb-4">
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? 'pending') === 'pending' ? 'active' : '' }}" href="{{ route('dashboard', ['tab' => 'pending'] + request()->only('search', 'priority', 'project')) }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'all' ? 'active' : '' }}" href="{{ route('dashboard', ['tab' => 'all'] + request()->only('search', 'priority', 'project')) }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($tab ?? '') === 'completed' ? 'active' : '' }}" href="{{ route('dashboard', ['tab' => 'completed'] + request()->only('search', 'priority', 'project')) }}">Completed</a>
            </li>
        </ul>

        <div class="ud-card card">
            <div class="card-header d-flex align-items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @if(($tab ?? 'pending') === 'pending')
                    Pending & in progress
                @elseif(($tab ?? '') === 'completed')
                    Completed tasks
                @else
                    All tasks
                @endif
            </div>
            <div class="card-body p-0">
                @forelse($tasks as $task)
                    <div class="ud-task-row d-flex flex-column flex-sm-row align-items-start justify-content-between gap-3">
                        <div class="d-flex gap-3 flex-grow-1 min-w-0">
                            <div class="ud-icon-box">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('tasks.show', $task) }}" class="ud-link d-block mb-1">{{ $task->title }}</a>
                                <p class="text-muted small mb-1">{{ $task->milestone->project->name }} · {{ $task->milestone->title }}</p>
                                <div class="small text-secondary">
                                    Due {{ $task->due_date?->format('M d, Y') ?? '—' }} · {{ ucfirst($task->priority) }} priority
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <span class="ud-badge @if($task->status === 'completed') ud-badge-done @elseif($task->status === 'in_progress') ud-badge-progress @else ud-badge-pending @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                            @if($task->status !== 'completed')
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}?{{ request()->getQueryString() }}">
                                    <button type="submit" class="ud-btn ud-btn-outline btn btn-sm">Start</button>
                                </form>
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}?{{ request()->getQueryString() }}">
                                    <button type="submit" class="ud-btn btn btn-sm">Complete</button>
                                </form>
                            @else
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-secondary text-decoration-none">View</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 ud-empty">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width: 64px; height: 64px; background: var(--app-primary-light); color: var(--app-primary);">
                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                            </span>
                        </div>
                        <p class="mb-1 fw-medium">
                            @if(($tab ?? 'pending') === 'pending')
                                No pending tasks
                            @elseif(($tab ?? '') === 'completed')
                                No completed tasks yet
                            @else
                                No tasks assigned
                            @endif
                        </p>
                        <p class="small mb-0">Tasks assigned to you will appear here.</p>
                        @if($search || $priority || $projectId)
                            <a href="{{ route('dashboard', ['tab' => $tab ?? 'pending']) }}" class="ud-link mt-3 d-inline-block small">Clear filters</a>
                        @endif
                    </div>
                @endforelse
                @if($tasks->hasPages())
                    <div class="border-top bg-light bg-opacity-50 px-4 py-3">{{ $tasks->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
