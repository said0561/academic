@extends('layouts.app')

@section('title', 'Create User')
@section('page_title', 'Add New User')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h6 class="fw-bold mb-3">New User</h6>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name"
                               value="{{ old('name') }}"
                               class="form-control form-control-sm @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Phone Number (e.g 255743123456)</label>
                        <input type="text" name="phone"
                               value="{{ old('phone') }}"
                               placeholder="255XXXXXXXXX"
                               class="form-control form-control-sm @error('phone') is-invalid @enderror"
                               required>
                        @error('phone')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email (Optional) --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email (optional)</label>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               class="form-control form-control-sm @error('email') is-invalid @enderror"
                               placeholder="example@mail.com">
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password"
                               class="form-control form-control-sm @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="role"
                                class="form-select form-select-sm @error('role') is-invalid @enderror"
                                required>
                            <option value="">Select role</option>

                            @foreach ($roles as $role)
                                <option value="{{ $role->slug }}"
                                    {{ old('role') == $role->slug ? 'selected' : '' }}>
                                    {{ ucfirst($role->slug) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Back
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        Save User
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
