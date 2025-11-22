@extends('layouts.app')

@section('title', 'Class Subjects')
@section('page_title', 'Class Subjects & Teachers')

@section('content')
    <div class="mb-3">
        <h6 class="fw-bold mb-1">
            Class: {{ $class->name }} {{ $class->stream }}
        </h6>
        <p class="small text-muted mb-0">
            Assign subjects and their teachers for this class.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success py-2 small">
            {{ session('status') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <form method="POST" action="{{ route('admin.classes.subjects.update', $class) }}">
                @csrf

                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;"></th>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th style="width: 220px;">Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            @php
                                $checked = array_key_exists($subject->id, $assignments);
                                $assignedTeacherId = $assignments[$subject->id] ?? null;
                            @endphp
                            <tr>
                                {{-- Checkbox: include subject in this class --}}
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input subject-toggle"
                                        name="subject_ids[]"
                                        value="{{ $subject->id }}"
                                        id="sub_{{ $subject->id }}"
                                        {{ $checked ? 'checked' : '' }}
                                    >
                                </td>

                                <td>
                                    <label for="sub_{{ $subject->id }}" class="small mb-0">
                                        {{ $subject->name }}
                                    </label>
                                </td>

                                <td class="small">
                                    {{ $subject->code }}
                                </td>

                                <td class="small">
                                    {{ $subject->department?->name ?? '—' }}
                                </td>

                                {{-- Teacher dropdown --}}
                                <td>
                                    <select
                                        name="teacher_ids[{{ $subject->id }}]"
                                        class="form-select form-select-sm teacher-select"
                                        data-subject-id="{{ $subject->id }}"
                                    >
                                        <option value="">— Select teacher —</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                {{ (int)$assignedTeacherId === $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} ({{ $teacher->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center small text-muted py-3">
                                    No active subjects found. Please add subjects first.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top bg-light">
                    <a href="{{ route('admin.classes.index') ?? '#' }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Back to classes
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        Save Assignments
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Optional JS: disable teacher select when subject unchecked --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.subject-toggle');

            function syncRowState() {
                toggles.forEach(function (checkbox) {
                    const tr = checkbox.closest('tr');
                    const select = tr.querySelector('.teacher-select');

                    if (!select) return;

                    if (checkbox.checked) {
                        select.disabled = false;
                        select.classList.remove('bg-light');
                    } else {
                        select.disabled = true;
                        select.classList.add('bg-light');
                        // huta-clear value ili uki-check tena ibaki kama ilivyokuwa
                    }
                });
            }

            toggles.forEach(cb => {
                cb.addEventListener('change', syncRowState);
            });

            syncRowState();
        });
    </script>
@endsection
