<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bs-primary: #2E7D32;
            --bs-primary-rgb: 46, 125, 50;
            --tf-heading: 'Figtree', system-ui, sans-serif;
        }
        body { font-family: 'Figtree', system-ui, sans-serif; min-height: 100vh; display: flex; flex-direction: column; background-color: #fafafa; }
        .navbar { background: #fff !important; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .navbar-brand { font-weight: 700; color: #2E7D32 !important; font-size: 1.35rem; }
        .nav-link { font-weight: 500; color: #333 !important; padding: 0.5rem 1rem !important; border-radius: 0.5rem; }
        .nav-link:hover { background-color: rgba(0,0,0,.06); color: #2E7D32 !important; }
        .btn-tf-primary { background: #2E7D32; color: #fff; font-weight: 600; padding: 0.6rem 1.25rem; border-radius: 0.5rem; border: none; }
        .btn-tf-primary:hover { background: #237026; color: #fff; }
        .btn-tf-outline { background: transparent; color: #2E7D32; font-weight: 600; padding: 0.6rem 1.25rem; border-radius: 0.5rem; border: 2px solid #2E7D32; }
        .btn-tf-outline:hover { background: rgba(46, 125, 50, 0.08); color: #2E7D32; border-color: #2E7D32; }
        .hero { background: linear-gradient(180deg, #f0f7f0 0%, #fff 100%); padding: 4rem 0 4.5rem; }
        .hero h1 { font-size: clamp(2rem, 5vw, 3rem); font-weight: 700; color: #1a1a1a; letter-spacing: -0.02em; line-height: 1.2; }
        .hero .lead { color: #555; font-size: 1.15rem; max-width: 640px; margin-left: auto; margin-right: auto; }
        .section-head { font-weight: 700; color: #1a1a1a; margin-bottom: 0.5rem; }
        .section-sub { color: #666; max-width: 560px; margin-left: auto; margin-right: auto; }
        .feature-card { border: 1px solid #e8e8e8; border-radius: 12px; background: #fff; transition: box-shadow 0.2s ease, transform 0.2s ease; height: 100%; }
        .feature-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-2px); }
        .feature-card .num { width: 48px; height: 48px; border-radius: 10px; background: #e8f5e9; color: #2E7D32; font-weight: 700; font-size: 1.25rem; display: flex; align-items: center; justify-content: center; }
        .feature-card h3 { font-size: 1.2rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem; }
        .feature-card p { color: #555; font-size: 0.95rem; margin-bottom: 0; line-height: 1.5; }
        .feature-card.alt { background: #f8f9fa; border-color: #eee; }
        .flow-card { border: 2px solid #e0e0e0; border-radius: 12px; background: #fff; padding: 1.5rem; text-align: center; transition: border-color 0.2s, box-shadow 0.2s; }
        .flow-card:hover { border-color: #2E7D32; box-shadow: 0 4px 16px rgba(46, 125, 50, 0.12); }
        .flow-card .flow-title { font-weight: 700; color: #2E7D32; font-size: 1.35rem; }
        .flow-arrow { color: #999; font-size: 1.5rem; }
        .role-pill { background: #f5f5f5; border: 1px solid #eee; border-radius: 10px; padding: 0.85rem 1rem; transition: background 0.2s, transform 0.15s; }
        .role-pill:hover { background: #e8f5e9; transform: translateX(4px); }
        .role-pill strong { color: #1a1a1a; }
        .role-pill span { color: #666; font-size: 0.9rem; }
        .footer-tf { background: #1a1a1a; color: #aaa; padding: 3rem 0 2rem; }
        .footer-tf h6 { color: #fff; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; }
        .footer-tf a { color: #aaa; text-decoration: none; }
        .footer-tf a:hover { color: #fff; text-decoration: underline; }
        .footer-tf .copy { border-top: 1px solid #333; padding-top: 1.5rem; margin-top: 2rem; font-size: 0.875rem; color: #666; }
    </style>
</head>
<body>
    {{-- Navbar (Zoho-style clean header) --}}
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <span class="rounded bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;font-size:1rem;">T</span>
                TaskFlow
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto gap-1">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Log in</a></li>
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="btn btn-tf-primary ms-2" href="{{ route('register') }}">Register</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        {{-- Hero (like Zoho: Manage Tasks Effectively) --}}
        <section class="hero text-center">
            <div class="container">
                <h1 class="mb-3">Manage Tasks Effectively</h1>
                <p class="lead mb-4">
                    Manage both simple and complex projects by breaking them down into milestones and tasks. Track progress with role-based dashboards and keep your team aligned — all in one place.
                </p>
                @guest
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('login') }}" class="btn btn-tf-primary btn-lg">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-tf-outline btn-lg">Get Started</a>
                        @endif
                    </div>
                @endguest
            </div>
        </section>

        {{-- Task Management (Zoho-style feature blocks) --}}
        <section class="py-5 py-md-5 bg-white" id="features">
            <div class="container py-4">
                <h2 class="text-center section-head display-6">Task Management</h2>
                <p class="text-center section-sub mb-5">Projects, milestones, and tasks with role-based access and progress tracking.</p>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <article class="feature-card p-4 h-100">
                            <div class="num mb-3">1</div>
                            <h3>Tasks</h3>
                            <p>Assign tasks to users, set priority and due dates, and track them as they are finished. Add title, description, status (Pending, In Progress, Completed), and assign to team members.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <article class="feature-card p-4 h-100">
                            <div class="num mb-3">2</div>
                            <h3>Milestones</h3>
                            <p>A milestone is an important progress point along the timeline of your project. Give each milestone start and end dates and track completion from tasks.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <article class="feature-card p-4 h-100">
                            <div class="num mb-3">3</div>
                            <h3>Projects</h3>
                            <p>Create and manage projects with description, managers and team leads. Break projects into milestones and view overall progress in one dashboard.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <article class="feature-card alt p-4 h-100">
                            <h3>Role-based dashboards</h3>
                            <p>Every role sees a different dashboard: Admin (all stats & users), Project Manager (my projects), Manager/HR (assigned projects & tasks), Team Lead (team tasks), User (my tasks & status).</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <article class="feature-card alt p-4 h-100">
                            <h3>Progress tracking</h3>
                            <p>Project and milestone progress is calculated from task completion. Update task status to see progress bars update automatically.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <article class="feature-card alt p-4 h-100">
                            <h3>Users & permissions</h3>
                            <p>Super Admin can manage users and assign roles. Permissions control who can create, edit, delete projects, milestones, and tasks, and who can assign or update status.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- How it works (Project → Milestones → Tasks) --}}
        <section class="py-5 py-md-5 bg-light">
            <div class="container py-4">
                <h2 class="text-center section-head display-6">How it works</h2>
                <p class="text-center section-sub mb-5">Simple flow from project to delivery.</p>
                <div class="row align-items-center justify-content-center g-4">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="flow-card">
                            <div class="flow-title">Project</div>
                            <p class="text-muted small mb-0 mt-1">Create project, add members</p>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block flow-arrow">→</div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="flow-card">
                            <div class="flow-title">Milestones</div>
                            <p class="text-muted small mb-0 mt-1">Phases with due dates</p>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block flow-arrow">→</div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="flow-card">
                            <div class="flow-title">Tasks</div>
                            <p class="text-muted small mb-0 mt-1">Assign & track status</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Roles --}}
        <section class="py-5 py-md-5 bg-white">
            <div class="container py-4">
                <h2 class="text-center section-head display-6">Roles we support</h2>
                <p class="text-center section-sub mb-4">Access tailored to your role.</p>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="role-pill d-flex align-items-center gap-2 flex-wrap">
                            <strong>Super Admin</strong>
                            <span>— Full access, users & roles</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="role-pill d-flex align-items-center gap-2 flex-wrap">
                            <strong>Project Manager</strong>
                            <span>— Create projects, milestones, assign members</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="role-pill d-flex align-items-center gap-2 flex-wrap">
                            <strong>Manager / HR</strong>
                            <span>— Assigned projects, create & assign tasks</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="role-pill d-flex align-items-center gap-2 flex-wrap">
                            <strong>Team Lead</strong>
                            <span>— Team tasks, assign & complete</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="role-pill d-flex align-items-center gap-2 flex-wrap">
                            <strong>User (Employee)</strong>
                            <span>— My tasks, update status & deadlines</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Bottom CTA (Zoho-style: Manage all your tasks) --}}
        <section class="py-5 bg-light text-center">
            <div class="container">
                <h2 class="section-head display-6 mb-3">Manage all your tasks with TaskFlow</h2>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-tf-primary btn-lg">Get Started</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-tf-primary btn-lg">Go to Dashboard</a>
                @endguest
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="footer-tf">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <h6>Task Management</h6>
                    <p class="small mb-0">Projects, milestones, and tasks with role-based dashboards, progress tracking, and user/role management.</p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h6>What we provide</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1">Projects (create, edit, members)</li>
                        <li class="mb-1">Milestones (per project)</li>
                        <li class="mb-1">Tasks (priority, status, assignee)</li>
                        <li class="mb-1">Dashboards by role</li>
                        <li>Reports & progress %</li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h6>Quick links</h6>
                    <ul class="list-unstyled small mb-0">
                        @auth
                            <li class="mb-1"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('projects.index') }}">Projects</a></li>
                        @else
                            <li class="mb-1"><a href="{{ route('login') }}">Log in</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}">Register</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
                <div class="col-md-6 col-lg-3">
                    <h6>System</h6>
                    <p class="small mb-0">Laravel 12 · MySQL · Role-based access · Secure authentication</p>
                </div>
            </div>
            <div class="copy text-center">&copy; {{ date('Y') }} {{ config('app.name') }}. Task & project management system.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
