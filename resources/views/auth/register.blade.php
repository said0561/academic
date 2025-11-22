@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="mb-4 text-center d-md-none">
        {{-- For mobile branding --}}
        <h4 class="fw-bold mb-1">{{ config('app.name', 'Academic System') }}</h4>
        <p class="mb-0 small text-muted">School Results Management System</p>
    </div>

    <h5 class="fw-bold mb-1">Create your account 📝</h5>
    <p class="text-muted mb-4 small">
        Register to access the Ibadhi Islamic school results portal.
    </p>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        {{-- Full Name --}}
        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold">Full Name</label>
            <input
                id="name"
                type="text"
                class="form-control form-control-sm @error('name') is-invalid @enderror"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="Enter your full name"
            >
            @error('name')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>


        {{-- Phone Number (Main Login Field) --}}
        <div class="mb-3">
            <label for="phone" class="form-label small fw-semibold">Phone Number (e.g 255743123456)</label>
            <input
                id="phone"
                type="text"
                class="form-control form-control-sm @error('phone') is-invalid @enderror"
                name="phone"
                value="{{ old('phone') }}"
                required
                placeholder="255XXXXXXXXX"
            >
            @error('phone')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>


        {{-- Email (Optional) --}}
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold">
                Email (optional)
            </label>
            <input
                id="email"
                type="email"
                class="form-control form-control-sm @error('email') is-invalid @enderror"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                placeholder="you@example.com"
            >
            @error('email')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>


        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label small fw-semibold">Password</label>
            <input
                id="password"
                type="password"
                class="form-control form-control-sm @error('password') is-invalid @enderror"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Create a password"
            >
            @error('password')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>


        {{-- Confirm Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label small fw-semibold">Confirm Password</label>
            <input
                id="password_confirmation"
                type="password"
                class="form-control form-control-sm"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Repeat password"
            >
        </div>


        {{-- Submit --}}
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-sm py-2">
                Create Account
            </button>
        </div>


        {{-- Already have account --}}
        <p class="small text-muted mb-1 text-center">
            Already registered?
            <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a>.
        </p>


        {{-- Back to home --}}
        <p class="small text-muted mt-2 mb-0 text-center">
            <a href="{{ url('/') }}" class="text-decoration-none">
                &larr; Back to school website
            </a>
        </p>

    </form>
@endsection
