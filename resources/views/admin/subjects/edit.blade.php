@extends('layouts.app')

@section('title', 'Edit Subject')
@section('page_title', 'Edit Subject')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h6 class="fw-bold mb-3">Edit Subject</h6>

            <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Subject Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $subject->name) }}"
                               class="form-control form-control-sm @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Code --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Subject Code</label>
                        <input type="text" name="code"
                               value="{{ old('code', $subject->code) }}"
                               class="form-control form-control-sm @error('code') is-invalid @enderror"
                               required>
                        @error('code')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Department</label>
                        <select name="department_id"
                                class="form-select form-select-sm @error('department_id') is-invalid @enderror"
                                required>
                            <option value="">Select department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $subject->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }} ({{ $department->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Active flag --}}
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-3">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="is_active">
                                Active subject
                            </label>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Back
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        Update Subject
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
