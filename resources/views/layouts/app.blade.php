<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Academic System') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/school-logo.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-magenta: #b93173;
            --brand-green:   #118a3b;
            --sidebar-width: 240px;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f3f4f6;
        }

        .app-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-main {
            flex: 1;
            display: flex;
            min-height: 0;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: #111827;
            color: #d1d5db;
        }

        .sidebar-brand {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #1f2933;
        }

        .sidebar-brand .logo-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.4);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: .5rem;
        }

        .sidebar-brand img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: .5rem 0 1rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem 1.25rem;
            font-size: 0.88rem;
            color: #9ca3af;
            text-decoration: none;
        }

        .sidebar-nav a:hover {
            background: #1f2937;
            color: #fff;
        }

        .sidebar-nav a.active {
            background: #b93173;
            color: #fff;
        }

        .sidebar-nav i.bi {
            font-size: 1rem;
        }

        .sidebar-section-title {
            padding: .75rem 1.25rem .25rem;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: #6b7280;
        }

        /* TOPBAR */
        .topbar {
            height: 56px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            justify-content: space-between;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .topbar-title i.bi {
            color: var(--brand-magenta);
        }

        .topbar-user {
            font-size: .85rem;
        }

        /* CONTENT */
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .content-inner {
            padding: 1rem 1rem 1.5rem;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }
            .content-wrapper {
                width: 100%;
            }
        }

        /* Small pagination styling */
        .pagination {
            font-size: 11px !important;
        }
        .pagination .page-link {
            padding: 2px 6px !important;
            margin: 0 2px !important;
            border-radius: 4px !important;
        }
        .pagination .page-item.disabled .page-link {
            color: #bbb !important;
        }
        .pagination .page-item .page-link {
            color: #0069d9 !important;
        }
        .pagination .page-item.active .page-link {
            background-color: #0069d9 !important;
            border-color: #0069d9 !important;
            color: #fff !important;
        }

        /* Make arrows smaller */
        .pagination .page-item .page-link svg {
            width: 10px !important;
            height: 10px !important;
        }
    </style>

    @stack('styles')

    <style>
        /* Hide system UI elements on print */
        @media print {
            /* hide top navbar */
            nav.navbar,
            .navbar,
            header,
            .topbar,
            .top-nav,
            .app-header {
                display: none !important;
            }

            /* hide sidebar */
            .sidebar,
            .app-sidebar,
            .side-nav,
            #sidebarMenu {
                display: none !important;
            }

            /* body full width */
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            /* card should fill the page */
            .card {
                border: none !important;
                box-shadow: none !important;
            }

            /* remove page padding */
            .container,
            .container-fluid,
            .content-wrapper,
            main {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* page break clean */
            .page-break {
                page-break-after: always;
            }

            /* links become plain text */
            a {
                color: black !important;
                text-decoration: none !important;
            }

            .table td.text-end {
    white-space: nowrap;
        }
    </style>

</head>
<body>
@php
    $user = auth()->user();
@endphp

<div class="app-wrapper">
    <div class="app-main">
        {{-- SIDEBAR --}}
        <aside class="sidebar d-none d-lg-block">
            <div class="sidebar-brand d-flex align-items-center">
                <div class="logo-circle">
                    <img src="{{ asset('images/school-logo.png') }}" alt="Logo">
                </div>
                <div class="lh-sm">
                    <div class="small fw-bold text-white">Ibadhi Islamic</div>
                    <div class="small text-muted">Pre & Primary</div>
                </div>
            </div>

            <ul class="sidebar-nav">
                @if($user && $user->hasRole('admin'))
                    <li class="sidebar-section-title">Admin</li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.classes.index') ?? '#' }}"
                           class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                            <i class="bi bi-collection"></i>
                            <span>Classes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students.index') ?? '#' }}"
                           class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subjects.index') ?? '#' }}"
                           class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark"></i>
                            <span>Subjects</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.exams.index') ?? '#' }}"
                           class="{{ request()->routeIs('admin.exams.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Exams</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="{{ route('admin.users.index') ?? '#' }}"
                           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-person-gear"></i>
                            <span>Users</span>
                        </a>
                    </li>
                @endif

                @if($user && $user->hasRole('teacher'))
                    <li class="sidebar-section-title">Teacher</li>
                    <li>
                        <a href="{{ route('teacher.dashboard') }}"
                           class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-pencil-square"></i>
                            <span>Enter Results</span>
                        </a>
                    </li>
                @endif

                @if($user && $user->hasRole('parent'))
                    <li class="sidebar-section-title">Parent</li>
                    <li>
                        <a href="{{ route('parent.dashboard') }}"
                           class="{{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-heart"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>My Children Results</span>
                        </a>
                    </li>
                @endif

                @if($user && $user->hasRole('academic'))
                    <li class="sidebar-section-title">Academic</li>
                    <li>
                        <a href="{{ route('academic.dashboard') }}"
                           class="{{ request()->routeIs('academic.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-mortarboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    {{-- add more menus as needed --}}
                @endif
            </ul>
        </aside>

        {{-- RIGHT SIDE: TOPBAR + CONTENT --}}
        <div class="content-wrapper">
            {{-- TOPBAR --}}
            <header class="topbar">
                <div class="topbar-title">
                    <i class="bi bi-layout-text-sidebar-reverse"></i>
                    <span>@yield('page_title', 'Dashboard')</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    @if($user)
                        <div class="topbar-user text-end">
                            <div class="fw-semibold small">{{ $user->name }}</div>
                            <div class="small text-muted">
                                @if($user->hasRole('admin')) Admin @endif
                                @if($user->hasRole('teacher')) Teacher @endif
                                @if($user->hasRole('parent')) Parent @endif
                                @if($user->hasRole('academic')) Academic @endif
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- MAIN CONTENT --}}
            <main class="content-inner">
                @yield('content')
            </main>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
