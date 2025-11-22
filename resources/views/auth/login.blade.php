@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mb-4 text-center d-md-none">
        {{-- For mobile --}}
        <h4 class="fw-bold mb-1">{{ config('app.name', 'Academic System') }}</h4>
        <p class="mb-0 small text-muted">School Results Management System</p>
    </div>

    <h5 class="fw-bold mb-1">Welcome back 👋</h5>
    <p class="text-muted mb-4 small">
        Please sign in using your phone number.
    </p>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- PHONE --}}
        <div class="mb-3">
            <label for="phone" class="form-label small fw-semibold">Phone Number</label>
            <input
                id="phone"
                type="text"
                name="phone"
                value="{{ old('phone') }}"
                required
                placeholder="255743123456"
                class="form-control form-control-sm @error('phone') is-invalid @enderror"
            >
            @error('phone')
                <div class="invalid-feedback small">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label small fw-semibold mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <input
                id="password"
                type="password"
                class="form-control form-control-sm @error('password') is-invalid @enderror"
                name="password"
                required
                placeholder="••••••••"
            >
            @error('password')
                <div class="invalid-feedback small">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="mb-3 form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="remember"
                id="remember" {{ old('remember') ? 'checked' : '' }}
            >
            <label class="form-check-label small" for="remember">
                Remember me
            </label>
        </div>

        {{-- Submit --}}
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-sm py-2">
                Sign in
            </button>
        </div>

        {{-- Register link --}}
        @if (Route::has('register'))
            <p class="small text-muted mb-1 text-center">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-decoration-none">Create one</a>.
            </p>
        @endif

        {{-- Back to home --}}
        <p class="small text-muted mt-2 mb-0 text-center">
            <a href="{{ url('/') }}" class="text-decoration-none">
                &larr; Back to school website
            </a>
        </p>
    </form>
@endsection
