@extends('layouts.app')

@section('title', 'Edit Student')
@section('page_title', 'Edit Student')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Edit Student</h6>

            <form method="POST" action="{{ route('admin.students.update', $student) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">First Name</label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name', $student->first_name) }}"
                               class="form-control form-control-sm @error('first_name') is-invalid @enderror">
                        @error('first_name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Middle Name (optional)</label>
                        <input type="text" name="middle_name"
                               value="{{ old('middle_name', $student->middle_name) }}"
                               class="form-control form-control-sm @error('middle_name') is-invalid @enderror">
                        @error('middle_name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Last Name</label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name', $student->last_name) }}"
                               class="form-control form-control-sm @error('last_name') is-invalid @enderror">
                        @error('last_name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Gender</label>
                        <select name="gender" class="form-select form-select-sm @error('gender') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="M" @selected(old('gender', $student->gender) == 'M')>Male</option>
                            <option value="F" @selected(old('gender', $student->gender) == 'F')>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Date of Birth</label>
                        <input type="date" name="dob"
                               value="{{ old('dob', $student->dob) }}"
                               class="form-control form-control-sm @error('dob') is-invalid @enderror">
                        @error('dob')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Class</label>
                        <select name="class_id"
                                class="form-select form-select-sm @error('class_id') is-invalid @enderror"
                                required>
                            <option value="">Select class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected(old('class_id', $student->class_id) == $class->id)>
                                    {{ $class->name }} {{ $class->stream }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Back
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
