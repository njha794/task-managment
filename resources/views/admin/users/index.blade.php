@extends('layouts.admin')

@section('title', 'Users & Roles')

@push('styles')
<style>
    .admin-page-header { border-left: 4px solid var(--tf-primary); }
    .card-users { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); overflow: hidden; }
    .card-users .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 1rem 1.25rem; }
    .card-users .table thead th { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; background: #f9fafb; padding: .75rem 1.25rem; border-bottom: 1px solid #eee; }
    .card-users .table tbody td { padding: .875rem 1.25rem; vertical-align: middle; }
    .card-users .table tbody tr { transition: background .15s ease; }
    .card-users .table tbody tr:hover { background: #f9fafb; }
    .badge-role { background: var(--tf-primary-light); color: var(--tf-primary); font-weight: 500; padding: .35em .65em; border-radius: 999px; font-size: .8rem; }
    .link-tf { color: var(--tf-primary); font-weight: 500; text-decoration: none; }
    .link-tf:hover { color: var(--tf-primary-dark); }
    .alert-tf-success { background: var(--tf-primary-light); border: 1px solid rgba(46,125,50,.2); color: #1b5e20; border-radius: .75rem; }
    .alert-tf-error { background: #fef2f2; border: 1px solid rgba(185,28,28,.2); color: #b91c1c; border-radius: .75rem; }
    .btn-tf-sm { padding: .35em .75em; font-size: .875rem; font-weight: 500; border-radius: .5rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5">
    <header class="admin-page-header bg-white rounded-2 shadow-sm mb-4 px-4 py-4">
        <h1 class="h4 mb-1 fw-bold text-dark">Manage Users & Roles</h1>
        <p class="text-muted small mb-0">Edit user details and assign roles</p>
    </header>

    @if (session('success'))
        <div class="alert alert-tf-success d-flex align-items-center gap-2 mb-4" role="alert">
            <svg class="bi flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-tf-error d-flex align-items-center gap-2 mb-4" role="alert">
            <svg class="bi flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if($users->total() > 0)
        <p class="text-muted small mb-3"><span class="fw-medium text-dark">{{ $users->total() }}</span> {{ $users->total() === 1 ? 'user' : 'users' }}</p>
    @endif

    <div class="card card-users">
        <div class="card-header">Users</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="fw-medium text-dark">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td><span class="badge-role">{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</span></td>
                            <td class="text-end">
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-tf-primary btn-tf-sm">Edit / Assign Role</a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-tf-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <div class="py-3">
                                    <div class="rounded-2 bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="text-secondary"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <p class="mb-0">No users.</p>
                                </div>
                            </td>
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
