@extends('layouts.admin')

@section('title', 'Edit User — ' . $user->name)

@push('styles')
<style>
    .admin-page-header { border-left: 4px solid var(--tf-primary); }
    .card-edit { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .form-control:focus { border-color: var(--tf-primary); box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25); }
    .form-check-input:checked { background-color: var(--tf-primary); border-color: var(--tf-primary); }
    .form-check-input:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25); }
    .invalid-feedback { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 px-lg-5">
    <header class="admin-page-header bg-white rounded-2 shadow-sm mb-4 px-4 py-4">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none" style="color: var(--tf-primary);">Users & Roles</a></li>
                <li class="breadcrumb-item active">Edit user</li>
            </ol>
        </nav>
        <h1 class="h4 mb-1 fw-bold text-dark">Edit User — {{ $user->name }}</h1>
        <p class="text-muted small mb-0">Update name, email, and role assignment</p>
    </header>

    <div class="card card-edit">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-medium">Name</label>
                    <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Email</label>
                    <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <span class="form-label fw-medium d-block">Roles</span>
                    <p class="text-muted small mb-3">Select one or more roles. Super Admin has full access.</p>
                    <div class="d-flex flex-column gap-2">
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->name }}" class="form-check-input" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                <label for="role-{{ $role->name }}" class="form-check-label">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="btn btn-tf-primary">Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
