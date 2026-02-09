<x-app-layout>
    @push('styles')
    <style>
        .tl-card { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
        .tl-card .card-header { background: var(--app-primary-light); color: var(--app-primary); font-weight: 600; padding: 0.875rem 1.25rem; border-bottom: 2px solid rgba(13, 148, 136, 0.2); }
        .tl-link { color: var(--app-primary); font-weight: 500; text-decoration: none; }
        .tl-link:hover { color: var(--app-primary-dark); }
        .tl-btn { background: var(--app-primary); color: #fff; border: none; font-weight: 500; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; }
        .tl-btn:hover { background: var(--app-primary-dark); color: #fff; }
        .tl-btn-outline { background: transparent; color: var(--app-primary); border: 1px solid var(--app-primary); }
        .tl-btn-outline:hover { background: var(--app-primary-light); color: var(--app-primary-dark); }
        .tl-table thead th { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; background: #f9fafb; padding: 0.75rem 1.25rem; border-bottom: 1px solid #e5e7eb; }
        .tl-table tbody td { padding: 0.875rem 1.25rem; vertical-align: middle; }
        .tl-table tbody tr { transition: background 0.15s ease; }
        .tl-table tbody tr:hover { background: #f9fafb; }
        .tl-badge { font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.6rem; border-radius: 9999px; }
        .tl-badge-pending { background: #f3f4f6; color: #4b5563; }
        .tl-badge-progress { background: #fef3c7; color: #b45309; }
        .tl-badge-done { background: var(--app-primary-light); color: var(--app-primary-dark); }
        .tl-alert { background: var(--app-primary-light); border: 1px solid rgba(13, 148, 136, 0.25); color: #0f766e; border-radius: 0.75rem; }
        .tl-empty { color: #6b7280; }
    </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
            <div>
                <h2 class="h5 mb-1 fw-bold text-dark">{{ __('Team Lead Dashboard') }}</h2>
                <p class="text-muted small mb-0">Tasks assigned to you and your team</p>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4 px-4 px-lg-5">
        @if (session('success'))
            <div class="tl-alert alert mb-4 d-flex align-items-center gap-2" role="alert">
                <svg class="flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="tl-card card">
            <div class="card-header d-flex align-items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                Tasks (assigned to me & my team)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover tl-table mb-0">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Project / Milestone</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Due</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <a href="{{ route('tasks.show', $task) }}" class="tl-link">{{ $task->title }}</a>
                                    </td>
                                    <td class="text-secondary small">{{ $task->milestone->project->name }} <span class="text-muted">/</span> {{ $task->milestone->title }}</td>
                                    <td>{{ $task->assignee?->name ?? '—' }}</td>
                                    <td>
                                        @php $status = $task->status; @endphp
                                        <span class="tl-badge @if($status === 'completed') tl-badge-done @elseif($status === 'in_progress') tl-badge-progress @else tl-badge-pending @endif">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $task->due_date?->format('M d, Y') ?? '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('tasks.edit', $task) }}" class="tl-btn tl-btn-outline btn btn-sm text-decoration-none">Edit</a>
                                        @if($task->status !== 'completed')
                                            <form action="{{ route('tasks.status', $task) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                                <button type="submit" class="tl-btn btn btn-sm">Complete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 tl-empty">
                                        <div class="mb-3">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-light" style="width:56px;height:56px;">
                                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                            </span>
                                        </div>
                                        <p class="mb-0 fw-medium">No tasks</p>
                                        <p class="small mb-0 mt-1">Tasks assigned to you or your team will appear here.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tasks->hasPages())
                    <div class="border-top bg-light bg-opacity-50 px-4 py-3">{{ $tasks->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
