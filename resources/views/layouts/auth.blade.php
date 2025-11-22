<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Academic System') }} - @yield('title', 'Login')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-magenta: #b93173;   /* ring ya logo */
            --brand-green:   #118a3b;   /* SHINYANGA text */
            --brand-gold:    #f5a623;   /* dome */
            --brand-blue:    #1e88e5;   /* kalamu */
        }

        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, var(--brand-magenta) 0, #020617 45%, #020617 100%);
            color: #fff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .auth-card {
            border-radius: 1.25rem;
            overflow: hidden;
        }
        .brand-block {
            background: linear-gradient(135deg, var(--brand-magenta), var(--brand-blue));
            color: #fff;
        }
        .brand-logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.7);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .brand-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .brand-subtitle {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            opacity: .9;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg auth-card border-0">
                <div class="row g-0">
                    {{-- Left side – logo & school name --}}
                    <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center brand-block">
                        <div class="text-center p-4">
                            <div class="brand-logo-circle mb-3">
                                <img src="{{ asset('images/school-logo.png') }}" alt="School logo">
                            </div>
                            <h5 class="fw-bold mb-1 text-uppercase">
                                IBADHI ISLAMIC
                            </h5>
                            <div class="brand-subtitle mb-2">
                                PRE & PRIMARY SCHOOL
                            </div>
                            <div class="small">
                                <span class="badge bg-light text-dark fw-semibold">
                                    SHINYANGA
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Right side – content from child views --}}
                    <div class="col-md-7 bg-white text-dark">
                        <div class="p-4 p-md-5">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Global auth messages --}}
            @if (session('status'))
                <div class="alert alert-success mt-3 mb-0">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mt-3 mb-0">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
