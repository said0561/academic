@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h6 class="fw-bold mb-3">Edit User</h6>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name) }}"
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
                               value="{{ old('phone', $user->phone) }}"
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
                               value="{{ old('email', $user->email) }}"
                               class="form-control form-control-sm @error('email') is-invalid @enderror"
                               placeholder="example@mail.com">
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password (optional) --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">
                            New Password
                            <span class="text-muted small">(leave blank to keep current)</span>
                        </label>
                        <input type="password" name="password"
                               class="form-control form-control-sm @error('password') is-invalid @enderror">
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
                                    {{ old('role', $currentRoleSlug) == $role->slug ? 'selected' : '' }}>
                                    {{ ucfirst($role->slug) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Children (only meaningful for parents) --}}
{{-- Children (only meaningful for parents) --}}
<div class="col-12 mt-2">
    <label class="form-label small fw-semibold">
        Children (for Parent role)
    </label>

    <p class="small text-muted mb-2">
        Select a class first, then tick the students who belong to this parent/guardian.
    </p>

    {{-- Class filter --}}
    <div class="row g-2 mb-2">
        <div class="col-md-6">
            <select id="classFilter" class="form-select form-select-sm">
                <option value="">— Select class —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">
                        {{ $class->name }} {{ $class->stream }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Students list (filtered by class using JS) --}}
    <div class="border rounded p-2"
         style="max-height: 260px; overflow-y: auto; background:#f9fafb;"
         id="studentsList">
        @forelse($students as $student)
            @php
                $fullName = collect([
                    $student->first_name,
                    $student->middle_name,
                    $student->last_name
                ])->filter()->join(' ');
            @endphp

            <div class="form-check small student-row"
                 data-class-id="{{ $student->class?->id ?? '' }}">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="children_ids[]"
                    id="child_{{ $student->id }}"
                    value="{{ $student->id }}"
                    {{ in_array($student->id, old('children_ids', $childrenIds)) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="child_{{ $student->id }}">
                    {{ $fullName }}
                    @if($student->class)
                        <span class="text-muted">
                            — {{ $student->class->name }} {{ $student->class->stream }}
                        </span>
                    @endif
                </label>
            </div>
        @empty
            <p class="small text-muted mb-0">
                No students found. Please add students first.
            </p>
        @endforelse
    </div>
</div>




                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Back
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        Update User
                    </button>
                </div>
            </form>

        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const classSelect = document.getElementById('classFilter');
    const rows = document.querySelectorAll('.student-row');

    function applyFilter() {
        const classId = classSelect.value;

        rows.forEach(row => {
            const rowClassId = row.dataset.classId || '';

            if (!classId) {
                row.style.display = 'none';
            } else if (rowClassId === classId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (classSelect) {
        classSelect.addEventListener('change', applyFilter);
        // mwanzo: ficha wote mpaka achague class
        applyFilter();
    }
});

</script>




@endsection
