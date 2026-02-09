<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --app-primary: #0d9488;
            --app-primary-dark: #0f766e;
            --app-primary-light: #ccfbf1;
        }
        body { font-family: 'Figtree', system-ui, sans-serif; background: #f4f6f9; min-height: 100vh; }
        .navbar-app { background: #fff !important; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .navbar-app .navbar-brand { font-weight: 700; color: var(--app-primary) !important; font-size: 1.25rem; }
        .navbar-app .nav-link { font-weight: 500; color: #374151 !important; padding: 0.5rem 0.75rem !important; border-radius: 0.5rem; }
        .navbar-app .nav-link:hover { color: var(--app-primary) !important; background: rgba(13, 148, 136, 0.08); }
        .navbar-app .nav-link.active { color: var(--app-primary) !important; background: var(--app-primary-light); }
        .dropdown-item:active { background-color: var(--app-primary-light); }
        .btn-app-primary { background: var(--app-primary); color: #fff; font-weight: 600; border: none; }
        .btn-app-primary:hover { background: var(--app-primary-dark); color: #fff; }
        .page-header-app { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.08); border-left: 4px solid var(--app-primary); }
    </style>

    @stack('styles')
    <!-- Vite (Tailwind for page content if needed) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-app sticky-top">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <span class="rounded d-inline-flex align-items-center justify-content-center fw-bold text-white" style="width:34px;height:34px;font-size:0.95rem;background:var(--app-primary);">T</span>
                {{ config('app.name', 'TaskFlow') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav" aria-controls="appNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="appNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                    </li>
                    @if(Auth::user()?->hasRole('Super Admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">{{ __('Users & Roles') }}</a>
                        </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle d-inline-block" style="width:28px;height:28px;background:var(--app-primary-light);"></span>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">{{ __('Log Out') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @isset($header)
        <header class="page-header-app border-bottom mb-0">
            <div class="container-fluid py-4 px-4 px-lg-5">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="min-vh-100">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
