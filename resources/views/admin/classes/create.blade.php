@extends('layouts.app')

@section('title', 'New Class')
@section('page_title', 'Create Class')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-bold mb-3">New Class</h6>

            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold">Class Name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control form-control-sm @error('name') is-invalid @enderror"
                           required>
                    @error('name')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="stream" class="form-label small fw-semibold">Stream (optional)</label>
                    <input type="text"
                           id="stream"
                           name="stream"
                           value="{{ old('stream') }}"
                           class="form-control form-control-sm @error('stream') is-invalid @enderror"
                           placeholder="A, B, C ...">
                    @error('stream')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Back
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Save Class
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
