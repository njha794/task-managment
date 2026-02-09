@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .admin-page-header { border-left: 4px solid var(--tf-primary); }
    .stat-card {
        border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08);
        transition: transform .2s ease, box-shadow .2s ease; overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.1); }
    .stat-card .stat-icon {
        width: 52px; height: 52px; border-radius: 12px; background: var(--tf-primary-light);
        color: var(--tf-primary); display: flex; align-items: center; justify-content: center;
    }
    .stat-card .stat-bar { height: 4px; background: var(--tf-primary); opacity: .85; }
    .stat-card:nth-child(2) .stat-bar { opacity: .75; }
    .stat-card:nth-child(3) .stat-bar { opacity: .65; }
    .stat-card:nth-child(4) .stat-bar { opacity: .9; }
    .card-table {
        border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden;
    }
    .card-table .card-header {
        background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 1rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;
    }
    .card-table .table { margin-bottom: 0; }
    .card-table .table thead th {
        font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;
        color: #6b7280; background: #f9fafb; padding: .75rem 1.25rem; border-bottom: 1px solid #eee;
    }
    .card-table .table tbody td { padding: .875rem 1.25rem; vertical-align: middle; }
    .card-table .table tbody tr { transition: background .15s ease; }
    .card-table .table tbody tr:hover { background: #f9fafb; }
    .card-table .table .progress-wrap { max-width: 100px; height: 8px; border-radius: 999px; background: #e5e7eb; overflow: hidden; }
    .card-table .table .progress-fill { height: 100%; border-radius: 999px; background: var(--tf-primary); transition: width .3s ease; }
    .link-tf { color: var(--tf-primary); font-weight: 500; text-decoration: none; }
    .link-tf:hover { color: var(--tf-primary-dark); }
    .badge-role { background: var(--tf-primary-light); color: var(--tf-primary); font-weight: 500; padding: .35em .65em; border-radius: 999px; font-size: .8rem; }
    .alert-tf-success { background: var(--tf-primary-light); border: 1px solid rgba(46,125,50,.2); color: #1b5e20; border-radius: .75rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5">
    {{-- Page header --}}
    <header class="admin-page-header bg-white rounded-2 shadow-sm mb-4 px-4 py-4">
        <h1 class="h4 mb-1 fw-bold text-dark">Admin Dashboard</h1>
        <p class="text-muted small mb-0">Overview of projects, tasks, and users</p>
    </header>

    @if (session('success'))
        <div class="alert alert-tf-success d-flex align-items-center gap-2 mb-4" role="alert">
            <svg class="bi flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small fw-medium text-muted mb-1">Projects</p>
                            <h2 class="h3 fw-bold text-dark mb-0">{{ $stats['projects'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-bar"></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small fw-medium text-muted mb-1">Milestones</p>
                            <h2 class="h3 fw-bold text-dark mb-0">{{ $stats['milestones'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-bar"></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small fw-medium text-muted mb-1">Tasks</p>
                            <h2 class="h3 fw-bold text-dark mb-0">{{ $stats['tasks'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-bar"></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase small fw-medium text-muted mb-1">Users</p>
                            <h2 class="h3 fw-bold text-dark mb-0">{{ $stats['users'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-bar"></div>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('projects.create') }}" class="btn btn-tf-primary btn-sm d-inline-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            New Project
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="text-success"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Manage Users & Roles
        </a>
    </div>

    {{-- Projects table --}}
    <div class="card card-table mb-4">
        <div class="card-header">
            <span>All Projects</span>
            <a href="{{ route('projects.index') }}" class="link-tf small">View all →</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th class="d-none d-md-table-cell">Creator</th>
                        <th>Milestones</th>
                        <th>Progress</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td><span class="fw-medium text-dark">{{ $project->name }}</span></td>
                            <td class="d-none d-md-table-cell text-muted">{{ $project->creator->name ?? '—' }}</td>
                            <td class="text-muted">{{ $project->milestones->count() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress-wrap flex-grow-1">
                                        <div class="progress-fill" style="width: {{ min($project->progress_percentage ?? 0, 100) }}%"></div>
                                    </div>
                                    <span class="small fw-medium">{{ $project->progress_percentage ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('projects.show', $project) }}" class="link-tf me-2">View</a>
                                <a href="{{ route('projects.edit', $project) }}" class="link-tf text-secondary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">No projects yet. Create your first project to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
            <div class="card-footer bg-light border-0 py-3">{{ $projects->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- Users table --}}
    <div class="card card-table">
        <div class="card-header">
            <span>Users</span>
            <a href="{{ route('admin.users.index') }}" class="link-tf small">Manage all →</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td class="fw-medium text-dark">{{ $u->name }}</td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td><span class="badge-role">{{ $u->roles->pluck('name')->join(', ') ?: '—' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $u) }}" class="link-tf">Edit / Assign Role</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">No users.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-light border-0 py-3">{{ $users->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection
