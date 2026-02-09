<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        :root { --tf-primary: #2E7D32; --tf-primary-dark: #237026; --tf-primary-light: #e8f5e9; }
        body { font-family: 'Figtree', system-ui, sans-serif; background: #f4f6f9; min-height: 100vh; }
        .navbar-admin { background: #fff !important; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .navbar-admin .navbar-brand { font-weight: 700; color: var(--tf-primary) !important; font-size: 1.25rem; }
        .navbar-admin .nav-link { font-weight: 500; color: #374151 !important; padding: 0.5rem 0.75rem !important; border-radius: 0.5rem; }
        .navbar-admin .nav-link:hover { color: var(--tf-primary) !important; background: rgba(46, 125, 50, 0.08); }
        .navbar-admin .nav-link.active { color: var(--tf-primary) !important; background: var(--tf-primary-light); }
        .dropdown-item:active { background-color: var(--tf-primary-light); }
        .btn-tf-primary { background: var(--tf-primary); color: #fff; font-weight: 600; border: none; }
        .btn-tf-primary:hover { background: var(--tf-primary-dark); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-admin sticky-top">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <span class="rounded bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center fw-bold" style="width:34px;height:34px;font-size:0.95rem;">T</span>
                TaskFlow
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">Projects</a>
                    </li>
                    @if(Auth::user()?->hasRole('Super Admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users & Roles</a>
                        </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle bg-secondary bg-opacity-25 d-inline-block" style="width:28px;height:28px;"></span>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Log out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
