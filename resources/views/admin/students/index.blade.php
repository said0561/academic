@extends('layouts.app')

@section('title', 'Students')
@section('page_title', 'Students')

@section('content')
    @php
        $currentClass = $classes->firstWhere('id', $classId);
    @endphp

    {{-- HEADER --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2 py-2">
            <div>
                <h5 class="fw-bold mb-0">Students</h5>
                <div class="small text-muted">
                    @if($currentClass)
                        Class: {{ $currentClass->name }} {{ $currentClass->stream }}
                    @else
                        All Classes
                    @endif
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">

                {{-- Bulk Upload --}}
                <a href="{{ route('admin.students.import') }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-upload me-1"></i>
                    Bulk Upload
                </a>

                {{-- Add Student --}}
                <a href="{{ route('admin.students.create') }}"
                   class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Student
                </a>

                {{-- Class Exam Report --}}
                @if($currentClass)
                    <a href="{{ route('admin.classes.exam-report', $currentClass->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-graph-up-arrow me-1"></i>
                        Class Report
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.students.index') }}" class="row g-2 align-items-end small">

                <div class="col-md-4">
                    <label for="class_id" class="form-label mb-1 fw-semibold">Filter by Class</label>
                    <select name="class_id" id="class_id" class="form-select form-select-sm">
                        <option value="">-- All Classes --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ (string)$classId === (string)$class->id ? 'selected' : '' }}>
                                {{ $class->name }} {{ $class->stream }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4"></div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary mt-3">
                        <i class="bi bi-filter-circle me-1"></i>
                        Apply Filter
                    </button>

                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                        <i class="bi bi-x-circle me-1"></i>
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- STUDENTS TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Class</th>
                        <th style="width: 120px;" class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $fullName = collect([
                                $student->first_name,
                                $student->middle_name,
                                $student->last_name,
                            ])->filter()->join(' ');
                        @endphp

                        <tr>
                            <td>
                                {{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}
                            </td>

                            <td>{{ $fullName }}</td>

                            <td>{{ $student->gender ?? '—' }}</td>

                            <td>{{ $student->dob ?? '—' }}</td>

                            <td>
                                @if($student->class)
                                    {{ $student->class->name }} {{ $student->class->stream }}
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="text-end">

                                {{-- Edit --}}
                                <a href="{{ route('admin.students.edit', $student) }}"
                                   class="btn btn-sm btn-outline-primary me-1"
                                   title="Edit Student">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.students.destroy', $student) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            title="Delete Student">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted small py-2">
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-2">
            {{ $students->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
